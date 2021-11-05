<?php

namespace App\Events;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordEncoderSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @return array[]
     * call before persist
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['encodingPassword', EventPriorities::PRE_WRITE]
        ];
    }

    /**
     * @param ViewEvent $event
     * Take event and request
     * verify method POST and if is a user object
     */
    public function encodingPassword(ViewEvent $event)
    {
        if (!$this->isRequestValid($event)) {
            return;
        }
        $this->hashUserPassword($event);
    }

    public function isRequestValid($event) {
        $method = $event->getRequest()->getMethod();
        $result = $event->getControllerResult();

        if ($method == "POST" && $result instanceof User) {
            return true;
        }

        return false;
    }

    public function hashUserPassword($event){
        $user =& $event->getControllerResult();
        $hash = $this->encoder->hashPassword($user, $user->getPassword());
        $user->setPassword($hash);
    }
}