<?php

namespace App\Modules\Comments\Services;

use App\Modules\Comments\Entity\Comment;
use App\Modules\Comments\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;

class CommentServices
{
    public function __construct(
        private readonly CommentRepository $commentRepository,
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    public function getAllCommentByUser(): array
    {
        return $this->commentRepository->findAll();
    }

    public function getCommentForTask(int $id): array
    {
        return $this->commentRepository->findBy(['task' => $id]);
    }

    public function addCommentOnTask(Comment $comment): void
    {
        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }

    public function updateCommentOnTask(Comment $comment): void
    {
        $this->entityManager->flush();
    }

    public function deleteCommentOnTAsk(Comment $comment): void
    {
        $this->entityManager->remove($comment);
        $this->entityManager->flush();
    }
}
