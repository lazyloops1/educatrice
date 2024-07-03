<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisType;
use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

#[Route('/avis')]
class AvisController extends AbstractController
{
    #[Route('/avis/index', name: 'app_avis_index', methods: ['GET'])]
    public function index(AvisRepository $avisRepository): Response
    {
        return $this->render('avis/index.html.twig', [
            'avis' => $avisRepository->findAll(),
        ]);
    }

    #[Route('/avis/new', name: 'app_avis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $avi = new Avis();
        $form = $this->createForm(AvisType::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avi->setNom($this->getUser()->getNom());
            $entityManager->persist($avi);
            $entityManager->flush();

            return $this->redirectToRoute('home.index');
        }

        return $this->renderForm('avis/new.html.twig', [
            'avi' => $avi,
            'form' => $form,
        ]);
    }

    #[Route('/avis/{id}', name: 'app_avis_show', methods: ['GET'])]
    public function show(Avis $avi): Response
    {
        return $this->render('avis/show.html.twig', [
            'avi' => $avi,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_avis_edit', methods: ['GET', 'POST'])]
    #[ParamConverter('avis', class: 'App\Entity\Avis')]
    public function edit(Request $request, Avis $avis, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_avis_user');
        }

        return $this->render('avis/edit.html.twig', [
            'avis' => $avis,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/avis/{id}', name: 'app_avis_delete', methods: ['POST'])]
    public function delete(Request $request, Avis $avi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$avi->getId(), $request->request->get('_token'))) {
            $entityManager->remove($avi);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Avis supprimé avec succès.');

        return $this->redirectToRoute('app_avis_index', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/avis/{id}/publish-homepage', name: 'app_avis_publish_homepage', methods: ['POST'])]
    #[ParamConverter('avi', class: 'App\Entity\Avis')]
    public function publishToHomepage(Avis $avi, EntityManagerInterface $entityManager): Response
    {
        $avi->setOnHomepage(true);
        $entityManager->flush();

        $this->addFlash('success', 'Avis mis en ligne sur la page d\'accueil avec succès.');

        return $this->redirectToRoute('app_avis_index');
    }
    
    #[Route('/avis/{id}/remove-from-homepage', name: 'app_avis_remove_from_homepage', methods: ['POST'])]
    public function removeFromHomepage(Avis $avi, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('remove_from_homepage' . $avi->getId(), $request->request->get('_token'))) {
            $avi->setOnHomepage(false);
            $entityManager->flush();

            $this->addFlash('success', 'Avis supprimé de la page d\'accueil avec succès.');
        }

        return $this->redirectToRoute('home.index');
    }

    #[Route('/avis/user', name: 'app_avis_user', methods: ['GET'])]
    public function userAvis(AvisRepository $avisRepository): Response
    {
        $user = $this->getUser();
        $userAvis = $avisRepository->findByAvis($user->getNom());

        return $this->render('avis/user_avis.html.twig', [
            'userAvis' => $userAvis,
        ]);
    }
}
