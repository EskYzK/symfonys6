<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TaskFileService
{
    private string $tasksDirectory;

    public function __construct(
        private Filesystem $filesystem,
        ParameterBagInterface $params
    ) {
        // On définit le dossier de stockage (public/tasks)
        $this->tasksDirectory = $params->get('kernel.project_dir') . '/public/tasks';

        // On s'assure que le dossier existe
        if (!$this->filesystem->exists($this->tasksDirectory)) {
            $this->filesystem->mkdir($this->tasksDirectory);
        }
    }

    public function createTask(string $title, string $description): void
    {
        $id = uniqid();
        $filename = $this->tasksDirectory . '/' . $id . '.txt';
        $date = new \DateTime();

        // Format du contenu du fichier
        $content = "Titre : " . $title . "\n";
        $content .= "Description : " . $description . "\n";
        $content .= "Date : " . $date->format('Y-m-d H:i:s');

        $this->filesystem->dumpFile($filename, $content);
    }

    public function updateTask(string $id, string $title, string $description): void
    {
        $filename = $this->tasksDirectory . '/' . $id . '.txt';

        if (!$this->filesystem->exists($filename)) {
            throw new \Exception("La tâche avec l'ID $id n'existe pas.");
        }

        // On réécrit le fichier avec les nouvelles infos (et une date mise à jour)
        $content = "Titre : " . $title . "\n";
        $content .= "Description : " . $description . "\n";
        $content .= "Date : " . (new \DateTime())->format('Y-m-d H:i:s');

        $this->filesystem->dumpFile($filename, $content);
    }

    public function listTasks(): array
    {
        // On scanne le dossier
        $files = scandir($this->tasksDirectory);
        $tasks = [];

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            if (pathinfo($file, PATHINFO_EXTENSION) !== 'txt') continue;

            $content = file_get_contents($this->tasksDirectory . '/' . $file);
            
            // Extraction simple du titre via Regex
            preg_match('/Titre : (.*)/', $content, $matches);
            $title = $matches[1] ?? 'Sans titre';
            
            $id = pathinfo($file, PATHINFO_FILENAME);
            
            $tasks[] = [
                'id' => $id, 
                'title' => $title
            ];
        }

        return $tasks;
    }

    public function getTask(string $id): array
    {
        $filename = $this->tasksDirectory . '/' . $id . '.txt';

        if (!$this->filesystem->exists($filename)) {
            throw new \Exception("La tâche n'existe pas.");
        }

        $content = file_get_contents($filename);
        $lines = explode("\n", $content);
        $data = [];

        // Parsing ligne par ligne
        foreach ($lines as $line) {
            if (str_starts_with($line, 'Titre : ')) {
                $data['title'] = substr($line, 8);
            } elseif (str_starts_with($line, 'Description : ')) {
                $data['description'] = substr($line, 14);
            } elseif (str_starts_with($line, 'Date : ')) {
                $data['date'] = substr($line, 7);
            }
        }
        
        return $data;
    }

    public function deleteTask(string $id): void
    {
        $filename = $this->tasksDirectory . '/' . $id . '.txt';

        if ($this->filesystem->exists($filename)) {
            $this->filesystem->remove($filename);
        } else {
             throw new \Exception("La tâche n'existe pas.");
        }
    }
}