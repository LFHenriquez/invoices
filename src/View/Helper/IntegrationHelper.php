<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\View;

/**
 * IntegrationHelper helper
 */
class IntegrationHelper extends Helper
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public function cell($column, $row)
    {
    	return getNameFromNumber($column).$row;
    }

    private function getNameFromNumber($num) {
	    $numeric = ($num - 1) % 26;
	    $letter = chr(65 + $numeric);
	    $num2 = intval(($num - 1) / 26);
	    if ($num2 > 0) {
	        return getNameFromNumber($num2) . $letter;
	    } else {
	        return $letter;
	    }
	}
}
