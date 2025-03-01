<?php

namespace App\Modules\Comments\Controller;

use App\Helpers\HelperAction;
use App\Modules\Comments\Entity\Comment;
use App\Modules\Comments\Services\CommentServices;
use App\Modules\Task\Entity\Task;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/comments', name: 'comment')]
class CommentsController extends AbstractController
{
    public function __construct(
        private readonly CommentServices     $commentServices,
        private readonly SerializerInterface $serializer,
        private readonly HelperAction        $helperAction,
        private readonly ValidatorInterface  $validator,
        private readonly TranslatorInterface $translator

    )
    {
    }

    #[Route('/all', name: '_all_comments_task', methods: ["GET"])]
    public function getAllComments(): Response
    {
        $allComments = $this->commentServices->getAllCommentByUser();
        return $this->json([
            'total' => count($allComments),
            'data' => $allComments,
            'error' => []
        ], Response::HTTP_OK, [], ['groups' => ['comment:read']]);
    }

    #[Route('/all/{task}', name: '_comments_on_task', methods: ["GET"])]
    public function getCommentsOfTask(?Task $task, Request $request): Response
    {
        if ($task === null) {
            return $this->helperAction->jsonNotFoundOrError();
        }
        $allComments = $this->commentServices->getCommentForTask($task->getId());
        return $this->json([
            'total' => count($allComments),
            'data' => $allComments,
            'error' => []
        ], Response::HTTP_OK, [], ['groups' => ['comment:read']]);
    }

    #[Route('/addCommentOnTask/{task}', name: '_add_comment_task', methods: ["POST"])]
    public function addCommentOnTask(?Task $task, Request $request): Response
    {
        if ($task === null) {
            return $this->json(['result' => false, 'errors' => ['Bad request']], Response::HTTP_BAD_REQUEST);
        }
        $comment = $this->serializer->deserialize($request->getContent(), Comment::class, 'json',
            ['groups' => ['comment:read']]
        );
        $comment->setTask($task);
        return $this->sameLogic($comment, 'create');
    }

    public function sameLogic(Comment $comment, string $action = 'create', ?array $groups = ['comment:read']): Response
    {
        $errors = $this->validator->validate($comment);
        $errors = HelperAction::handleErrors($errors);
        if (count($errors) === 0) {
            $action === 'create' ?
                $this->commentServices->addCommentOnTask($comment) :
                $this->commentServices->updateCommentOnTask($comment);
        }
        return $this->json([
            'data' => $comment,
            'error' => $errors
        ], count($errors) === 0 ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST,
            [], ['groups' => $groups]);
    }

    #[Route('updateComment/{comment}', name: '_update_comment_task', methods: ["PUT", "PATCH"])]
    public function updateCommentOnTask(?Comment $comment, Request $request): Response
    {
        if ($comment === null) {
            return $this->json(['result' => false, 'errors' => ['Bad request']], Response::HTTP_BAD_REQUEST);
        }
        $this->serializer->deserialize($request->getContent(), Comment::class, 'json',
            [
                'groups' => ['comment:read', 'comment:update'],
                AbstractNormalizer::IGNORED_ATTRIBUTES => ['task'],
                AbstractNormalizer::OBJECT_TO_POPULATE => $comment
            ]
        );
        return $this->sameLogic($comment, 'update');
    }

    #[Route('/delete/{comment}', name: '_delete_comment_task', methods: ["DELETE"])]
    public function deleteCommentOnTAsk(?Comment $comment): Response
    {
        if ($comment === null) {
            return $this->json(['result' => false, 'errors' => ['Bad request']], Response::HTTP_BAD_REQUEST);
        }
        $this->commentServices->deleteCommentOnTAsk($comment);;

        return $this->json(['result' => true, 'error' => []], Response::HTTP_OK);
    }
}
