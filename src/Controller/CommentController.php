<?php

namespace App\Controller;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CommentController extends AbstractController
{
    #[Route('/comment/{id}', name: 'comment_delete', requirements: ['id'=>'\d+'])]
    public function delete(Comment $comment, EntityManagerInterface $em, Request $request): Response
    {
        $user = $this->getUser();

        if ($comment->getUser() !== $user && !in_array('ROLE_ADMIN', $user->getRoles())) {
            throw new AccessDeniedException('Vous n\'êtes pas autorisé à supprimer ce commentaire.');
        }
        
        $params = ['slug' => $comment->getPost()->getSlug()];
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $em->remove($comment);
            $em->flush();
        }

        $this->addFlash('success', 'Le commentaire a bien été supprimé');

        return $this->redirectToRoute('post_view', $params);
    }
}
