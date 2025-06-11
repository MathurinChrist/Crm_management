<?php

namespace App\Security\Voter;

use App\Modules\Project\Entity\Project;
use App\Security\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class ProjectVoter extends Voter
{
    public const CREATE = 'PROJECT_CREATE';
    public const EDIT = 'PROJECT_EDIT';
    public const VIEW = 'PROJECT_VIEW';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof Project;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var Project $subject */
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        if (in_array('ROLE_SUPER_ADMIN', $user->getRoles())) {
            return true;
        }

        switch ($attribute) {
            case self::CREATE:
                return in_array('ROLE_SUPER_ADMIN', $user->getRoles());

            case self::EDIT:
                return $subject->getCreatedBy()->getId() === $user->getId();

            case self::VIEW:
                return $subject->getCreatedBy()->getId() === $user->getId();

                return $subject->getManager() === $user || $subject->getTeam()->contains($user);
        }

        return false;
    }
}
