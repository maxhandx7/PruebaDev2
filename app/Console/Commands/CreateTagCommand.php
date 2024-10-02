<?php

namespace App\Console\Commands;

use App\Models\Tag;
use Illuminate\Console\Command;

class CreateTagCommand extends Command
{
    protected $signature = 'tag:create {name}';

   
    protected $description = 'Crea un nuevo tag';

    public function handle()
    {
        // Obtener el nombre del tag desde los argumentos del comando
        $name = $this->argument('name');

        // Validar si el tag ya existe
        if (Tag::where('name', $name)->exists()) {
            $this->error('El tag "' . $name . '" ya existe.');
            return;
        }

        // Crear el nuevo tag
        $tag = Tag::create(['name' => $name]);

        $this->info('Tag creado: ' . $tag->name);
    }
}
