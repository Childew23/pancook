<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Form\PostType;
use App\Form\SearchType;
use App\Model\SearchData;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class PostController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(PostRepository $postRepository, PaginatorInterface $paginatorInterface, Request $request): Response
    {
        $data = $postRepository->findPublished();
        $posts = $paginatorInterface->paginate($data, $request->query->getInt('page', 1), 3);

        $searchData = new SearchData();
        $form = $this->createForm(SearchType::class, $searchData);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $searchData->page = $request->query->getInt('page', 1);
            $posts = $postRepository->findBySearch($searchData);

            return $this->render('post/index.html.twig', [
                'form' => $form->createView(),
                'posts' => $posts,
            ]);
        }

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
            'form' => $form->createView()
        ]);
    }

    #[Route('/post/{slug}', name: 'post_view', methods: ["GET", "POST"])]
    public function postView(Post $post, Request $request, ManagerRegistry $doctrine): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($this->getUser());
            $comment->setPost($post);
            $em = $doctrine->getManager();
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('post_view', ['slug' => $post->getSlug()]);
        }

        return $this->render('post/post.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/post/add', name: 'post_add', priority: 10)]
    public function addPost(Request $request, ManagerRegistry $doctrine): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUser($this->getUser());
            $post->setActive(false);
            $em = $doctrine->getManager();
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('post/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
