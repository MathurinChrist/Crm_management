<?php

namespace App\Modules\Comments\Services;

use App\Modules\Comments\Entity\Comments;
use App\Modules\Comments\Repository\CommentsRepository;
use Doctrine\ORM\EntityManagerInterface;

class CommentServices
{
    public function __construct(
        private readonly CommentsRepository     $commentsRepository,
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    public function getAllCommentsByUser(): array
    {
        return $this->commentsRepository->findAll();
    }

    public function getCommentForTask(int $id): array
    {
        return $this->commentsRepository->findBy(['task' => $id]);
    }

    public function addCommentOnTask(Comments $comment): void
    {
        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }

    public function updateCommentOnTask(Comments $comment): void
    {
        $this->entityManager->flush();
    }

    public function deleteCommentOnTAsk(Comments $comment): void
    {
        $this->entityManager->remove($comment);
        $this->entityManager->flush();
    }
}
