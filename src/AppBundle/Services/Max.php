<?php

namespace AppBundle\Services;

use Symfony\Component\HttpFoundation\RequestStack;
Class Max
{
	private $randoms = array();
	protected $requestStack;
	
	public function __construct(array $randoms, RequestStack $requestStack)
	{
		$this->randoms = $randoms;
		$this->requestStack = $requestStack;
	}
	
	public function getMaxNumber()
	{
		return max($this->randoms);
	}
	
	public function changeRandoms(){
		$this->randoms = array(9,89);
	}
	public function getUtilisateur(){
		$request = $this->requestStack;
	}
}