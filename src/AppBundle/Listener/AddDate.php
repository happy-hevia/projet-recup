<?php

namespace AppBundle\Listener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
Class AddDate
{
	
	public function putDate(FilterResponseEvent $event)
	{
		if (!$event->isMasterRequest()) {
			 return;
			}
		
		$content = $event->getResponse()->getContent();
		$date = new \DateTime();
		$date = $date->format('Y-m-d');
		
		$content = $date.$content;
		$event->getResponse()->setContent($content);
	}
}