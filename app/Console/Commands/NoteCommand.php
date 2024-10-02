<?php

namespace App\Console\Commands;

use App\Models\Note;
use Illuminate\Console\Command;


class NoteCommand extends Command
{
    protected $signature = 'note {action} {--id=} {--title=} {--description=} {--due_date=}  {--tags=} {--userId=}';
    protected $description = 'Gestión de notas A traves de artisan';

    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'create':
                $this->createNote();
                break;
            case 'update':
                $this->updateNote();
                break;
            case 'list':
                $this->listNotes();
                break;
            case 'delete':
                $this->deleteNote();
                break;
            default:
                $this->error('Error');
        }
    }

    protected function createNote()
    {
        $note = Note::create([
            'title' => $this->option('title'),
            'description' => $this->option('description'),
            'due_date' => $this->option('due_date'),
            'user_id' => $this->option('userId'), 
            'tags' => $this->option('tags') ? $this->option('tags') : null,
        ]);

        $this->info('Nota creada: ' . $note->title);
    }

    protected function updateNote()
    {
        $note = Note::find($this->option('id'));

        if (!$note) {
            $this->error('Error al actualizar.');
            return;
        }

        $note->update([
            'title' => $this->option('title') ?? $note->title,
            'description' => $this->option('description') ?? $note->description,
            'due_date' => $this->option('due_date') ?? $note->due_date,
            'tags' => $this->option('tags') ? json_decode($this->option('tags')) : $note->tags,
        ]);

        $this->info('Nota actualizada: ' . $note->title);
    }

    protected function listNotes()
    {
        $notes = Note::all();

        if ($notes->isEmpty()) {
            $this->info('No hay notas.');
            return;
        }

        foreach ($notes as $note) {
            $this->line("ID: {$note->id}, Title: {$note->title}, Due Date: {$note->due_date}");
        }
    }

    protected function deleteNote()
    {
        $note = Note::find($this->option('id'));

        if (!$note) {
            $this->error('Error al eliminar.');
            return;
        }

        $note->delete();
        $this->info('Nota eliminada: ' . $note->title);
    }
}
