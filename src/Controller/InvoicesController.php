<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\I18n\Date;
use GoogleAuth\GoogleAuth;
use Spreadsheets\Spreadsheets;
use \Google_Client;
use \Google_Service_Sheets;
use \Google_Service_Drive;
use \Google_Auth_AssertionCredentials;
use \Google_Service_Sheets_ValueRange;
use \Google_Service_Drive_DriveFile;
use DebugKit;
/**
 * Invoices Controller
 *
 * @property \App\Model\Table\InvoicesTable $Invoices
 */
class InvoicesController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Integration');
    }

    public function index()
    {
    }

    public function callback()
    {
        if (isset($_GET['code'])) {
            $client = new GoogleAuth();
            $client->callback($_GET['code']);
        }
        $redirect_uri = Router::url([
            'action' => 'edit',
        ]);
        return $this->redirect(['action' => 'edit']);
    }

    public function edit()
    {
        $client = new GoogleAuth();
        if(!$client->connect())
            return $this->redirect($client->createAuthUrl());

        $spreadsheetId = Configure::read('google.spreadsheet_id');
        $spreadsheet = new Spreadsheets($client, $spreadsheetId);

        if (!isset($_POST['email']) ||
            !isset($_POST['password']) ||
            !($result = $spreadsheet->getUnique("data", "email", $_POST['email'])) ||
            $result['password'] != $_POST['password'])
        {
            $this->Flash->error('Erreur, l\'identifiant n\'existe pas ou le mot de passe est erroné!');
            return $this->redirect(['action' => 'index']);
        }

        $this->request->session()->write('id', $result['id']);
        $this->set('values', $result);

        $invoiceHeader = $spreadsheet->getValues('display');
        $this->set('header', $this->Integration->sort_array($invoiceHeader));
    }

    public function view()
    {
        unset($_POST['_method']);
        $this->request->session()->write('data', $_POST);
    }

    public function download()
    {
        $data = $this->request->session()->read('data');

        $date = new Date();
        $data['date'] = $date->format('Y-m-d');
        
        $payment_date = new Date('+1 month');
        $data['payment_date'] = $payment_date->format('Y-m-d');

        $filename = strtolower(substr($data['firstname'], 0, 1) . $data['lastname']) . '-' . $date->format('Y-m-d');
        $this->request->session()->write('filename', $filename);

        $CakePdf = new \CakePdf\Pdf\CakePdf();
        $CakePdf->template('view', 'default');
        $CakePdf->viewVars($data);
        $pdf = $CakePdf->output();
        $this->response->body($pdf);
        $this->response->type('pdf');
        $this->response->download($filename .'.pdf');
        return $this->response;
    }

    public function validation()
    {
        $filename = $this->request->session()->read('filename');
        if ($filename)
        {
            $completeFilename = ROOT . DS . 'files' . DS . $filename.'-sign.pdf';
            if(isset($_FILES['file']))
            move_uploaded_file($_FILES['file']['tmp_name'], $completeFilename);
            
            $cmd = 'java -jar '. ROOT . DS . 'bin/TBSVerifySignaturePDF.jar -in ' . $completeFilename .' 2>&1';
            $result = shell_exec(escapeshellcmd($cmd));
            $successText = "La v?rification s'est d?roul?e avec succ?s.";
            $validation['signed'] = (strpos($result, $successText) !== false)? true: false;

            preg_match("/Sujet: {E=\[[a-z0-9.@]*/", $result, $matches);
            $email = (isset($matches[0]))? substr($matches[0], 11) : "";

            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($completeFilename);
            $text = str_replace(' ', '', $pdf->getText());

            preg_match("/Total:\d*,?\d*€/", $text, $matches);
            $total = (isset($matches[0]))? floatval(str_replace(',','.',substr($matches[0],6,-1))) : -1;
            
            preg_match("/Datedelivraison:\d{4}-\d{2}-\d{2}/", $text, $matches);
            $temp = (isset($matches[0]))? explode(":", $matches[0]) : ['','0000-00-00'];
            $date = $temp[1];

            $email="leonard.henriquez@edhec.com";

            $client = new GoogleAuth();
            if(!$client->connect())
                return $this->redirect($client->createAuthUrl());
            $spreadsheetId = Configure::read('google.spreadsheet_id');
            $spreadsheet = new Spreadsheets($client, $spreadsheetId);
            $result = $spreadsheet->getUnique("data", "email", $email);

            $now = new Date();
            $datePdf = $now->format('Y-m-d');
            $validation['date'] = ($date == $datePdf)? true : false;

            $validation['integrity'] = ($total == floatval(str_replace(',','.', $result['price']))) ? true : false;

            $this->set('validation', $validation);

            if( $validation['date'] ||
                $validation['integrity'] ||
                $validation['signed'])
            {
                $service = new Google_Service_Drive($client);
                $file = new Google_Service_Drive_DriveFile();
                $file->setName($filename.'.pdf');
                $file->setMimeType('application/pdf');
                $result = $service->files->create(
                    $file,
                    array(
                        'data' => file_get_contents($completeFilename),
                        'mimeType' => 'application/octet-stream',
                        'uploadType' => 'media'
                        ));
                $userPermission = new \Google_Service_Drive_Permission(array(
                    'type' => 'anyone',
                    'role' => 'writer',
                    ));
                $service->permissions->create(
                    $result->id, $userPermission, array('fields' => 'id')
                    );
                $driveFile = 'https://googledrive.com/host/'.$result->id;

                $spreadsheet->setUnique('data', 'email', $email, 'file', $driveFile);
                $spreadsheet->setUnique('data', 'email', $email, 'sign_date', $date);
                $spreadsheet->setUnique('data', 'email', $email, 'status', 'done');
                $this->set('message', 'Merci, votre facture a bien été prise en compte.');
            }
            else
            {
                $this->set('message', 'La facture n\'est pas valide, veuillez recommencer.');
            }
        } else {
            $this->Flash->error('Erreur');
            $this->redirect(['action' => 'index']);
        }
    }
}
