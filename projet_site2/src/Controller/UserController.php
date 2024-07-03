<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/index', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/{id}/ban', name: 'app_user_ban', methods: ['POST'])]
    public function banAction(User $user, EntityManagerInterface $entityManager): Response
    {
        if ($user) {
            $roles = $user->getRoles();
            if (!in_array('ROLE_BAN', $roles)) {
                if (($key = array_search('ROLE_USER', $roles)) !== false) {
                    unset($roles[$key]);
                }
                $roles[] = 'ROLE_BAN';
                $user->setRoles($roles);
                $entityManager->flush();
                $this->addFlash('success', 'Ln\'utilisateur a bien été banni.');
            } else {
                $this->addFlash('warning', 'Ln\'utilisateur est déja banni.');
            }
        } else {
            $this->addFlash('error', 'Ln\'utilisateur nn\a pas été trouvé.');
        }

        return $this->redirectToRoute('app_user_index');
    }
}
