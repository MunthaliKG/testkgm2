<?php
namespace AppBundle\Helpers;

use Doctrine\DBAL\Connection;

class DataConverter{
	private $connection;

	function __construct(Connection $connection){
		$this->connection = $connection;
	}
	//convert an array of need ids to a string of comma separated ids
	function convertToCommaString($array, $removeDelimitingQuotes = false){
		$escaped = array_map(array($this->connection, 'quote'), $array); //escape each element of the array

		if($removeDelimitingQuotes){
			$quote = "'";
			$escaped = $this->arrayRemoveQuotes($escaped);
		}
		return implode(',', $escaped); //convert the array to a string of comma separated values
	}
	//convert a string of comma separated values to an array
	function convertToArray($commaString){
		return explode(',', $commaString);
	}
	//removing surrounding quotes from every element of an array
	function arrayRemoveQuotes($array){
		$quote = "'";
		$unquoted = array_map(
		/*remove surrounding quotes from every element of the array*/
		function($item) use ($quote) { 
    		return trim($item, $quote); 
		}, 
		$array );
		return $unquoted;
	}
	function countArray($array, $key, $value){
		$count = 0;
		foreach($array as $element){
			if(is_array($element)){
				if($element[$key] == $value)
					$count++;
			}
		}
		return $count;
	}
	function selectFromArray($array, $key, $value){
		$array = array();
		foreach($array as $element){
			if(is_array($element)){
				if($element[$key] == value){
					$array[] = $element;
				}
			}
		}
		return $array;
	}

}

?>