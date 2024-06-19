<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/category/{slug}', name: 'post_category_view', methods: ["GET"])]
    public function postViewByCategory(Category $category, PostRepository $postRepository, PaginatorInterface $paginatorInterface, Request $request): Response
    {
        $data = $postRepository->findByCategory($category);
        $posts = $paginatorInterface->paginate($data, $request->query->getInt('page', 1), 3);

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }
}
