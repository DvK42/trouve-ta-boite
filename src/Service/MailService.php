<?php

namespace App\Service;

use App\Entity\Offer;
use Twig\Environment;
use App\Entity\Student;
use App\Entity\Application;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

class MailService
{
    private MailerInterface $mailer;
    private Environment $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendWelcomeEmail(string $toEmail, string $username, string $type): void
    {
        $template = $type === 'student' ? 'emails/welcome_student.html.twig' : 'emails/welcome_company.html.twig';

        $emailContent = $this->twig->render($template, [
            'username' => $username,
        ]);

        $email = (new Email())
            ->from('noreply@trouvetaboite.com')
            ->to($toEmail)
            ->subject('Bienvenue sur Trouve ta Boîte')
            ->html($emailContent);

        $this->mailer->send($email);
    }

        public function sendApplicationNotificationToCompany(Offer $offer, Student $student, Application $application): void
    {
        $company = $offer->getCompany();
        $companyEmail = $company->getEmail();

        $emailContent = $this->twig->render('emails/application_notification.html.twig', [
            'company' => $company,
            'offer' => $offer,
            'student' => $student,
            'application' => $application,
        ]);

        $email = (new Email())
            ->from('no-reply@trouveta-boite.com')
            ->to($companyEmail)
            ->subject('Nouvelle candidature pour votre offre : ' . $offer->getName())
            ->html($emailContent);

        $this->mailer->send($email);
    }

    public function sendApplicationConfirmationToStudent(Offer $offer, Student $student): void
    {
        $studentEmail = $student->getEmail();

        $emailContent = $this->twig->render('emails/application_confirmation.html.twig', [
            'student' => $student,
            'offer' => $offer,
        ]);

        $email = (new Email())
            ->from('no-reply@trouveta-boite.com')
            ->to($studentEmail)
            ->subject('Confirmation de votre candidature : ' . $offer->getName())
            ->html($emailContent);

        $this->mailer->send($email);
    }

}
