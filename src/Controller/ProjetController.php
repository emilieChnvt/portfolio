<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Projet;
use App\Form\ImageForm;
use App\Form\ProjetForm;
use App\Repository\ProjetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProjetController extends AbstractController
{
    #[Route('/', name: 'projets')]
    public function index(ProjetRepository $projetRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'projets' => $projetRepository->findAll(),
        ]);
    }
    #[Route('/projet/new', name: 'app_projet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $projet = new Projet();
        $form = $this->createForm(ProjetForm::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($projet);
            $entityManager->flush();

            return $this->redirectToRoute('app_projet_new_image', ['id' => $projet->getId()]);
        }

        return $this->render('projet/new.html.twig', [
            'projet' => $projet,
            'form' => $form
        ]);
    }

    #[Route('/projet/addImage/{id}', name: 'app_projet_new_image') ]
    public function addImage(
        Request $request,
        EntityManagerInterface $entityManager,
        Projet $projet,
        ProjetRepository $projetRepository
    ): Response{
        if (!$projet) {
            return $this->redirectToRoute('app_projet_new');
        }

        $image = new Image();
        $form = $this->createForm(ImageForm::class, $image);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image->setProjet($projet);
            $entityManager->persist($image);
            $entityManager->flush();
            return $this->redirectToRoute('app_home', [
                'projets' => $projetRepository->findAll(),
            ]);
        }
        return $this->render('projet/addImage.html.twig', [

            'projet' => $projet,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/projet/{id}', name: 'app_projet_show', methods: ['GET'])]
    public function show(Projet $projet): Response
    {
        return $this->render('projet/show.html.twig', [
            'projet' => $projet
            ]);
    }

    #[Route('/projet/{id}/edit', name: 'app_projet_edit', methods: ['GET', 'POST'])]
    public function edit(
        ProjetRepository $projetRepository,
        Request $request, Projet $projet,
        EntityManagerInterface $entityManager
    ): Response{
        $form = $this->createForm(ProjetForm::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_home', [
                'projets' => $projetRepository->findAll()
            ]);
        }

        return $this->render('projet/edit.html.twig', [
            'projet' => $projet,
            'form' => $form,
        ]);
    }

    #[Route('/projet/{id}', name: 'app_projet_delete', methods: ['POST'])]
    public function delete(Request $request, Projet $projet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $projet->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($projet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_projet_index');
    }
}
