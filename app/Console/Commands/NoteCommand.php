<?php

namespace App\Console\Commands;

use App\Models\Note;
use Illuminate\Console\Command;


class NoteCommand extends Command
{
    // La firma del comando, que incluye el nombre del comando 'note' y sus argumentos y opciones
    protected $signature = 'note {action} {--id=} {--title=} {--description=} {--due_date=}  {--tags=} {--userId=}';
    protected $description = 'Gestión de notas A traves de artisan';

    // El método principal que se ejecuta cuando se llama al comando
    public function handle()
    {   
        // Obtiene el argumento 'action' (crear, actualizar, listar o eliminar)
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
         // Crea la nota con los datos recibidos desde las opciones del comando
        $note = Note::create([
            'title' => $this->option('title'),
            'description' => $this->option('description'),
            'due_date' => $this->option('due_date'),
            'user_id' => $this->option('userId'), 
            'tags' => $this->option('tags') ? $this->option('tags') : null,
        ]);

         // Si se han pasado etiquetas, las sincroniza con la nota
        if ($this->option('tags')) {
            $note->tags()->sync($this->option('tags'));
        }

        $this->info('Nota creada: ' . $note->title);
    }

    // Método para actualizar una nota existente
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
            'tags' => $this->option('tags') ? $this->option('tags') : $note->tags,
        ]);
        $note->tags()->sync($this->option('tags'));
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

    // Método para eliminar una nota existente
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
