<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserPageController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/user/{username}_{id}', name: 'app_user_page')]
    public function user(User $user, Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $likes = $userRepository->getLikes($user->getId());
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Les informations ont été mises à jour.');

            return $this->redirectToRoute('app_user_page', [
                'username' => $user->getUsername(),
                'id' => $user->getId()
            ]);
        }

        return $this->render('user_page/index.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'likes' => $likes
        ]);
    }
}
