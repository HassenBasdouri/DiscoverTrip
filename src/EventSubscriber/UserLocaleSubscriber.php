<?php 
namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Stores the locale of the user in the session after the
 * login. This can be used by the LocaleSubscriber afterwards.
 */
class UserLocaleSubscriber implements EventSubscriberInterface
{
    private $session;
    private $em;

    public function __construct(SessionInterface $session, EntityManagerInterface $em)
    {
        $this->session = $session;
        $this->em=$em;
    }

    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
		$user = $event->getAuthenticationToken()->getUser();
        if (null !== $user->getLocale()) {
            $this->session->set('_locale', $user->getLocale());
        }
        else{
            $user->setlocale($this->session->get('_locale'));
            $this->em->persist($user);
			$this->em->flush();
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onInteractiveLogin',
        ];
    }
}