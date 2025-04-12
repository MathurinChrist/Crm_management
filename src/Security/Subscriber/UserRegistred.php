<?php

namespace App\Security\Subscriber;

use App\Modules\Emailling\Services\EmaillingService;
use App\Security\Event\OnUserCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserRegistred implements EventSubscriberInterface
{
    public function __construct(
        private readonly EmaillingService $emailingService
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OnUserCreatedEvent::class => 'onUserRegistred',
        ];
    }

    public function onUserRegistred(OnUserCreatedEvent $event): void
    {
       if ($event->getUser() !== null) {
           $context = [];
           $context['name'] = $event->getUser()->getFullName();
           $context['userEmail'] = $event->getUser()->getEmail();
           $context['password'] = $event->getPassword();

           $this->emailingService->sendTestMail(
               'je suis entrain de travailler',
               'email/accountRegister.html.twig',
               $context
           );
       }
    }
}
