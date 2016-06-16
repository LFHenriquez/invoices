<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;

/**
 * Integration component
 */
class IntegrationComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public function getNameFromNumber($num)
    {
	    $numeric = ($num - 1) % 26;
	    $letter = chr(65 + $numeric);
	    $num2 = intval(($num - 1) / 26);
	    if ($num2 > 0) {
	        return $this->getNameFromNumber($num2) . $letter;
	    } else {
	        return $letter;
        }
        var_dump($this);
    }

    public function cell($column, $row)
    {
        return $this->getNameFromNumber($column).$row;
    }

    private function compare($a, $b)
    {
        return $a['order'] - $b['order'];
    }

    public function sort_array($array)
    {
        usort($array, array(&$this, 'compare'));
        return $array;
    }
}
