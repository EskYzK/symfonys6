<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $user = $this->getReference('user-regular'); 

        $taskRecent = new Task();
        $taskRecent->setName('Tâche récente');
        $taskRecent->setDescription('Cette tâche a été créée aujourd\'hui, elle est modifiable.');
        $taskRecent->setCreatedAt(new \DateTimeImmutable());
        $taskRecent->setAuthor($user);
        $manager->persist($taskRecent);

        $taskOld = new Task();
        $taskOld->setName('Tâche ancienne');
        $taskOld->setDescription('Cette tâche a été créée il y a 10 jours, elle n\'est plus modifiable.');
        $taskOld->setCreatedAt((new \DateTimeImmutable())->modify('-10 days')); 
        $taskOld->setAuthor($user);
        $manager->persist($taskOld);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}