<?php

namespace App\Modules\Emailling\Services;

use App\Factory\EmailingFactory;
use App\Security\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class EmailingService
{
    public function __construct(
        private readonly EmailingFactory $emailingFactory
    )
    {
    }

    public function sendTestMail(string $subject, $path, ?array $context):void
    {
        $email = $this->emailingFactory->createTemplateEmail();
        $email->from('no-reply@crm_management.com')
            ->subject($subject)
            ->htmlTemplate('email/accountRegister.html.twig')
            ->htmlTemplate($path)
            ->context($context)
        ;

        $this->mailer->send($email);
    }

}
