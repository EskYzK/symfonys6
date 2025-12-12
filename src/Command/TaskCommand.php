<?php

namespace App\Command;

use App\Service\TaskFileService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:task',
    description: 'Gestion des tâches via fichiers (create, update, list, get, delete)',
)]
class TaskCommand extends Command
{
    public function __construct(
        private TaskFileService $taskFileService
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('action', InputArgument::REQUIRED, 'Action à effectuer : create, update, list, get, delete')
            // Argument optionnel pour passer l'ID directement si besoin
            ->addArgument('id', InputArgument::OPTIONAL, 'ID de la tâche (pour get, update, delete)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $action = $input->getArgument('action');
        $id = $input->getArgument('id');

        try {
            switch ($action) {
                case 'create':
                    $io->section('Création d\'une nouvelle tâche');
                    $title = $io->ask('Titre de la tâche');
                    $description = $io->ask('Description');
                    
                    $this->taskFileService->createTask($title, $description);
                    $io->success('Tâche créée avec succès.');
                    break;

                case 'update':
                    $io->section('Modification d\'une tâche');
                    if (!$id) {
                        $id = $io->ask('ID de la tâche à modifier');
                    }

                    // On demande les nouvelles infos
                    $title = $io->ask('Nouveau titre');
                    $description = $io->ask('Nouvelle description');

                    $this->taskFileService->updateTask($id, $title, $description);
                    $io->success("Tâche $id mise à jour.");
                    break;

                case 'list':
                    $io->section('Liste des tâches');
                    $tasks = $this->taskFileService->listTasks();
                    
                    if (empty($tasks)) {
                        $io->warning('Aucune tâche trouvée.');
                    } else {
                        // Affiche un joli tableau dans la console
                        $io->table(['ID', 'Titre'], $tasks);
                    }
                    break;

                case 'get':
                    if (!$id) {
                        $id = $io->ask('ID de la tâche à afficher');
                    }
                    
                    $task = $this->taskFileService->getTask($id);
                    
                    $io->section("Détails de la tâche : $id");
                    $io->definitionList(
                        ['Titre' => $task['title'] ?? 'N/A'],
                        ['Description' => $task['description'] ?? 'N/A'],
                        ['Date' => $task['date'] ?? 'N/A']
                    );
                    break;

                case 'delete':
                    if (!$id) {
                        $id = $io->ask('ID de la tâche à supprimer');
                    }
                    
                    if ($io->confirm("Voulez-vous vraiment supprimer la tâche $id ?", false)) {
                        $this->taskFileService->deleteTask($id);
                        $io->success("Tâche $id supprimée.");
                    }
                    break;

                default:
                    $io->error("Action inconnue : $action. Les actions possibles sont : create, update, list, get, delete.");
                    return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}