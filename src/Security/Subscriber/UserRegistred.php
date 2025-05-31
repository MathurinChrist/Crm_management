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
            OnUserCreatedEvent::class => 'onUserRegistredOrCreated',
        ];
    }

    public function onUserRegistredOrCreated(OnUserCreatedEvent $event): void
    {
        $template = 'email/accountRegister.html.twig';
        $message = 'Inscription au Crm';
       if ($event->getUser() !== null) {
           if (!in_array("ROLE_SUPER_ADMIN", $event->getUser()->getRoles())) {
               $message = "Un compte vient d'être crée avec cette adresse email";
           }
           $context = [];
           $context['name'] = $event->getUser()->getFullName();
           $context['userEmail'] = $event->getUser()->getEmail();
           $context['password'] = $event->getPassword();

           $this->emailingService->sendTestMail(
               $message,
               $template,
               $context
           );
       }
    }
}
