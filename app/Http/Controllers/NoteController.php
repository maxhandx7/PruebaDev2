<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api'); // Asegura que todas las rutas de este controlador requieran autenticación
    }
    public function index()
    {
        /* Obtener las notas por fecha de creación,
        y solo las notas del usuario auntenticado */

        return Note::with('tags')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')->get();
    }

    public function store(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'nullable|date',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        // Crear la nota
        $note = Note::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'user_id' => auth()->id(),
            'tags' => $request->tags ? $request->tags : null,
        ]);

        /*  Si hay etiquetas en la solicitud, las actualiza en la nota: agrega nuevas, 
         elimina las que faltan y mantiene las que siguen. */
        if ($request->tags) {
            $note->tags()->sync($request->tags);
        }
        
        return response()->json($note, 201);
    }

    public function update(Request $request, Note $note)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
        ]);

        $note->update($request->all());
        $note->tags()->sync($request->get('tags'));
        return $note;
    }

    public function edit($id)
    {
        $note = Note::findOrFail($id);
        return response()->json($note);
    }

    public function destroy(Note $note)
    {
        $note->delete();
        return response()->json(['message' => 'Nota eliminada']);
    }
}
