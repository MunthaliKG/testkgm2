<?php
namespace AppBundle\Helpers;

use Doctrine\DBAL\Connection;

class DataConverter{
	private $connection;

	function __construct(Connection &$connection){
		$this->connection = $connection;
	}
	//convert an array of need ids to a string of comma separated ids
	function convertToCommaString($array){
		$quoted = array_map(array($this->connection, 'quote'), $array); //escape each element of the array
		return implode(',', $quoted); //convert the array to a string of comma separated values
	}
	//convert a string of comma separated values to an array
	function convertToArray($commaString){
		return explode(',', $commaString);
	}

}

?>