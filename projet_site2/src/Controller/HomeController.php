<?php

namespace App\Controller;

use App\Repository\AvisRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController
{
    #[Route('/', name: 'home.index', methods: ['GET'])]
    public function index(AvisRepository $avisRepository): Response
    {
        $avis = $avisRepository->findAll();

        return $this->render('home.html.twig', [
            'avis' => $avis,
        ]);
    }
}
