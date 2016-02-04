<?php

namespace AppBundle\Services;

Class Random
{
	private $first;
	private $last;
	
	public function __construct($first, $last){
		$this->first = $first;
		$this->last = $last;
	}
	
	
	public function generateRandomNumbers($first = null, $last = null)
	{
		if ($first === null){
			$first = $this->first;
		}
		if ($last === null){
			$first = $this->last;
		}
		$tableau = array();
		for ($i = 0; $i <10 ; $i++){
			array_push($tableau, rand($first, $last));
		}
		return $tableau;
	}
}