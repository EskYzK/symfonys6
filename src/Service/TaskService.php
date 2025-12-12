<?php

namespace App\Service;

use App\Entity\Task;

class TaskService
{
    public function canEdit(Task $task): bool
    {
        $createdAt = $task->getCreatedAt();
        
        if (!$createdAt) {
            return true;
        }

        $now = new \DateTimeImmutable();
        $interval = $now->diff($createdAt);

        return $interval->days < 7;
    }
}