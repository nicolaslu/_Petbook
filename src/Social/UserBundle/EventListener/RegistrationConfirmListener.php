<?php
	class RegistrationConfirmListener implements EventSubscriberInterface
	{
	    private $router;

	    public function __construct(UrlGeneratorInterface $router)
	    {
	        $this->router = $router;
	    }

	    /**
	     * {@inheritDoc}
	     */
	    public static function getSubscribedEvents()
	    {
	        return array(
	                FOSUserEvents::REGISTRATION_CONFIRM => 'onRegistrationConfirm'
	        );
	    }

	    public function onRegistrationConfirm(GetResponseUserEvent $event)
	    {
	        $url = $this->router->generate('wall_profile');

	        $event->setResponse(new RedirectResponse($url));
	    }
	}
?>