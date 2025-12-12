<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tasks')]
class TaskController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/', name: 'task_index', methods: ['GET'])]
    public function index(): Response
    {
        $tasks = $this->entityManager->getRepository(Task::class)->findAll();
        
        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    #[Route('/new', name: 'task_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $this->denyAccessUnlessGranted('TASK_CREATE'); 

        $task = new Task();
        
        $task->setAuthor($this->getUser());
        $task->setCreatedAt(new \DateTimeImmutable());

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->entityManager->persist($task);
            $this->entityManager->flush();

            $this->addFlash('success', 'La tâche a été créée avec succès.');

            return $this->redirectToRoute('task_index');
        }

        return $this->render('task/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'task_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Task $task): Response
    {
        $this->denyAccessUnlessGranted('TASK_EDIT', $task); 
        
        if (!$taskService->canEdit($task)) {
            $this->addFlash('error', 'Vous ne pouvez plus modifier cette tâche (créée il y a plus de 7 jours).');
            return $this->redirectToRoute('task_index');
        }

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $task->setUpdatedAt(new \DateTimeImmutable());
            $this->entityManager->flush();

            $this->addFlash('success', 'La tâche a été mise à jour avec succès.');

            return $this->redirectToRoute('task_index');
        }

        return $this->render('task/edit.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/{id}', name: 'task_view', methods: ['GET'])]
    public function view(Task $task): Response
    {
        return $this->render('task/view.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/{id}/delete', name: 'task_delete', methods: ['POST'])]
    public function delete(Request $request, Task $task): Response
    {
        $this->denyAccessUnlessGranted('TASK_DELETE', $task);

        if ($this->isCsrfTokenValid('delete' . $task->getId(), $request->request->get('_token'))) {
             $this->entityManager->remove($task);
             $this->entityManager->flush();

             $this->addFlash('success', 'La tâche a été supprimée avec succès.');
        } else {
             $this->addFlash('error', 'Token de sécurité invalide.');
        }

        return $this->redirectToRoute('task_index', [], Response::HTTP_SEE_OTHER);
    }
}