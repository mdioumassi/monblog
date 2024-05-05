<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommentController extends AbstractController
{
    #[Route('/ajax/comments', name: 'comment_add')]
    public function add(Request $request, EntityManagerInterface $em, ArticleRepository $articleRepo, CommentRepository $commentRepo, UserRepository $userRepo): Response
    {
        $commentData = $request->request->all("comment");
        if (!$this->isCsrfTokenValid('comment-add', $commentData['_token'])) {
            return $this->json(['message' => 'Token CSRF invalide'], 400);
        }

        $article = $articleRepo->find($commentData['article']);
        if (!$article) {
            return $this->json(['message' => 'Article introuvable'], 404);
        }

        $comment = new Comment($article);
        $comment->setContent($commentData['content']);
        $comment->setCreatedAt(new \DateTime());
        $comment->setUser($userRepo->find(1));

        $em->persist($comment);
        $em->flush();

        $html = $this->renderView('comment/index.html.twig', [
            'comment' => $comment
        ]);

        return $this->json([
            'code' => 'COMMENT_ADDED_SUCCESSFULLY',
            'message' => $html,
            'numberOfComments' => $commentRepo->count(['article' => $article])
        ]);
    }
}
