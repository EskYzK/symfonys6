<?php

namespace App\Security\Voter;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Bundle\SecurityBundle\Security;

class TaskVoter extends Voter
{
    // Définition des attributs de permission
    public const CREATE = 'TASK_CREATE';
    public const VIEW = 'TASK_VIEW';
    public const EDIT = 'TASK_EDIT';
    public const DELETE = 'TASK_DELETE';

    public function __construct(private Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        $supportsAttribute = in_array($attribute, [self::CREATE, self::VIEW, self::EDIT, self::DELETE]);
        
        $supportsSubject = $attribute === self::CREATE || $subject instanceof Task;

        return $supportsAttribute && $supportsSubject;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        if ($attribute === self::CREATE) {
            return $this->security->isGranted('ROLE_USER');
        }
        
        /** @var Task $task */
        $task = $subject;

        return match ($attribute) {
            self::VIEW => $this->canView($task, $user),
            self::EDIT => $this->canEdit($task, $user),
            self::DELETE => $this->canDelete($task, $user),
            default => false,
        };
    }

    private function canView(Task $task, User $user): bool
    {
        return $task->getAuthor() === $user;
    }

    private function canEdit(Task $task, User $user): bool
    {
        return $task->getAuthor() === $user;
    }

    private function canDelete(Task $task, User $user): bool
    {
        return false;
    }
}