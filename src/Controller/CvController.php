<?php

namespace App\Controller;

use App\Entity\EmailContact;
use App\Form\EmailForm;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

final class CvController extends AbstractController
{
    #[Route('/cv', name: 'app_cv')]
    public function index(EntityManagerInterface $entityManager, Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(EmailForm::class );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $emailData = $data->getEmail();


            $contact = new EmailContact();
            $contact->setEmail($emailData);
            $contact->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($contact);
            $entityManager->flush();

            $email = (new Email())
                ->from('contact@emiliechanavat.com')
                ->to($emailData)
                ->subject('Votre demande : Voici mon CV')
                ->text("Bonjour,\n\nMerci pour votre intérêt.\n\nVous trouverez en pièce jointe mon CV.\n\nCordialement,\nÉmilie Chanavat")
                ->html(
                    '<p>Bonjour,</p>' .
                    '<p>Merci pour votre intérêt.</p>' .
                    '<p>Vous trouverez en pièce jointe mon CV au format PDF.</p>' .
                    '<p>Pour toute question, n’hésitez pas à me contacter via ce mail.</p>' .
                    '<br>' .
                    '<p>Cordialement,<br><strong>Émilie Chanavat</strong></p>' .
                    '<p><a href="https://emiliechanavat.com" target="_blank" rel="noopener noreferrer">Mon site web</a></p>'
                )
                ->attachFromPath($this->getParameter('kernel.project_dir').'/public/images/Emilie CHANAVAT CV-3.pdf', 'Mon-CV.pdf');

            $mailer->send($email);
            $this->addFlash('success', 'Votre CV a été envoyé par mail !');

            return $this->redirectToRoute('app_cv');
        }

        return $this->render('cv/index.html.twig', [
            'emailForm' => $form->createView(),
        ]);
}}
