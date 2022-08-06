<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class MailerService
{
    public function __construct(
        private MailerInterface $mailer,
        private EntityManagerInterface $entityManager,
        private ParameterBagInterface $parameterBag)
    {
    }

    public function TemplateEmail( TemplatedEmail $templatedEmail, array $options =[]): Bool
    {

//        $messagerEmail->getRecipient()
        $f= $templatedEmail->getHeaders()->toArray();
        $ContactMail = new ContactMail();
        $ContactMail
            ->setRecipient($f[0] )
            ->setSender($f[1])
            //->cc('cc@example.com')
            //            ->Bcc('info@xxxxx.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->setSubject($f[2])
            ->setMessage($templatedEmail->getTextBody())
//            ->html($ContactMail->getMessage())
            ->setTemplate($templatedEmail->getHtmlTemplate())
        ;

        try {
            $this->mailer->send($templatedEmail);
        } catch (TransportExceptionInterface $e) {
            // some error prevented the email sending; display an
            // error message or try to resend the message
            return false;
        }

        // add in dataBase
        $this->entityManager->persist($ContactMail);
        $this->entityManager->flush();

        return true;
    }
}
