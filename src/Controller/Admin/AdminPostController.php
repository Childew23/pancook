<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\EditPostType;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/admin', name: 'admin_')]
class AdminPostController extends AbstractController
{
    #[Route('/post', name: 'post_index')]
    public function listPost(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();

        return $this->render('admin/post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/post/activate/{id}', name: 'post_activate')]
    public function activatePost(Post $post, ManagerRegistry $doctrine): Response
    {
        $post->setActive(($post->isActive()) ? false : true);
        $em = $doctrine->getManager();
        $em->flush();

        return new Response("true");
    }

    #[Route('/post/delete/{id}', name: 'post_delete')]
    public function deletePost(Post $post, ManagerRegistry $doctrine, Request $request, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $token = new CsrfToken('delete', $request->query->get('_csrf_token'));
        if ($csrfTokenManager->isTokenValid($token)) {
            $em = $doctrine->getManager();
            $em->remove($post);
            $em->flush();
            $this->addFlash('success', 'Article supprimé !');
        } else {
            $this->addFlash('danger', 'Token absent ou invalide !');
        }
        return $this->redirectToRoute('admin_post_index');
    }

    #[Route('/post/detail/{id}', name: 'post_detail')]
    public function detailPost(Post $post): Response
    {
        return $this->render('admin/post/show.html.twig', [
            'post' => $post
        ]);
    }

    #[Route('/post/update/{id}', name: 'post_update')]
    public function updatePost(Post $post, Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(EditPostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->flush();
            $this->addFlash('success', 'Article modifié !');

            return $this->redirectToRoute('admin_post_index');
        }

        return $this->render('admin/post/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
