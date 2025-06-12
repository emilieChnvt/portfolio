<?php

namespace App\Controller;

use App\Form\ContactForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

final class ContactController extends AbstractController
{
    #[Route('/', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $email = (new Email())
                ->from('contact@emiliechanavat.com') // mon adresse
                ->replyTo($data['email']) // l'adresse du destinataire si je veux rep
                ->to('contact@emiliechanavat.com') // le destinataire donc mon adresse
                ->subject('Formulaire portfolio - ' . $data['sujet'])
                ->text("Message reçu depuis le portfolio :\n\n" .
                    "Nom : " . $data['nom'] . "\n" .
                    "Email : " . $data['email'] . "\n\n" .
                    $data['message']);

            $mailer->send($email);

            $this->addFlash('success', 'Merci ! Votre message a bien été envoyé.');
            return $this->redirectToRoute('app_contact');

        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
