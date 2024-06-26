<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\Admin\UserType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin', name:'admin_')]
class AdminUserController extends AbstractController
{
    #[Route('/user', name: 'user_index')]
    public function listUser(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/delete/{id}', name: 'user_delete')]
    public function deleteUser(User $user, ManagerRegistry $doctrine, Request $request, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $token = new CsrfToken('delete', $request->query->get('_csrf_token'));
        if ($csrfTokenManager->isTokenValid($token)) {
            $em = $doctrine->getManager();
            $em->remove($user);
            $em->flush();
            $this->addFlash('success', 'Utilisateur supprimé !');
        } else {
            $this->addFlash('danger', 'Token absent ou invalide !');
        }
        return $this->redirectToRoute('admin_user_index');
    }

    #[Route('/user/update/{id}', name: 'user_update')]
    public function updateUser(User $user, Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->flush();

            $this->addFlash('success', 'Utilisateur modifié !');
            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/user/detail/{id}', name: 'user_detail')]
    public function detailUser(User $user, UserRepository $userRepository): Response
    {
        $likes = $userRepository->getLikes($user->getId());
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
            'likes' => $likes
        ]);
    }
}
