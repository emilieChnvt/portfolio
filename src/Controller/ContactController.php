<?php

namespace App\Controller;

use App\Form\ContactForm;
use App\Repository\ProjetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

final class ContactController extends AbstractController
{
    #[Route('/', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer, ProjetRepository $projetRepository): Response
    {
        $form = $this->createForm(ContactForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            // Vérifier que le captcha est rempli
            $captchaResponse = $request->request->get('g-recaptcha-response');
            if (!$captchaResponse) {
                $this->addFlash('error', 'Veuillez valider le captcha pour envoyer le formulaire.');
            } else {
                // Validation du captcha via Google
                $secretKey = $_ENV['RECAPTCHA_SECRET_KEY'] ?? null;
                if (!$secretKey) {
                    throw new \Exception('La clé secrète reCAPTCHA n’est pas définie dans .env');
                }

                $client = \Symfony\Component\HttpClient\HttpClient::create();
                $response = $client->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
                    'body' => [
                        'secret' => $secretKey,
                        'response' => $captchaResponse,
                    ],
                ]);
                $captchaData = $response->toArray();

                if (!$captchaData['success']) {
                    $this->addFlash('error', 'Captcha invalide, veuillez réessayer.');
                } else {
                    // Envoi de l'email
                    $email = (new Email())
                        ->from('contact@emiliechanavat.com')
                        ->replyTo($formData['email'])
                        ->to('contact@emiliechanavat.com')
                        ->subject('Formulaire portfolio - ' . $formData['sujet'])
                        ->text(
                            "Message reçu depuis le portfolio :\n\n" .
                            "Nom : " . $formData['nom'] . "\n" .
                            "Email : " . $formData['email'] . "\n\n" .
                            $formData['message']
                        );

                    $mailer->send($email);
                    $this->addFlash('success', 'Merci ! Votre message a bien été envoyé.');

                    // Réinitialiser le formulaire après envoi
                    return $this->redirectToRoute('app_contact');
                }
            }
        }

        // Rendu final du formulaire, avec flashes
        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'projets' => $projetRepository->findAll(),
        ]);
    }
}
