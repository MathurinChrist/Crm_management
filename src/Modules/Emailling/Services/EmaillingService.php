<?php

namespace App\Modules\Emailling\Services;

use App\Factory\EmailingFactory;
use App\Helpers\HelperAction;
use Symfony\Component\Mailer\MailerInterface;

class EmaillingService
{
    public function __construct(
        private readonly EmailingFactory $emailingFactory,
        private readonly MailerInterface $mailer,
    ){
    }

    public function sendTestMail(string $subject, string $path, ?array $context):void
    {
        $email = $this->emailingFactory->createTemplateEmail();
        $email->from(HelperAction::getEnvVar('MAILER_FROM_ADDRESS'))
            ->to($context['userEmail'])
            ->subject($subject)
            ->htmlTemplate($path)
            ->context($context)
        ;
        $this->mailer->send($email);
    }
}
