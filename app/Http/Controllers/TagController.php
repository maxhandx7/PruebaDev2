<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    // Obtener todas las etiquetas
    public function index()
    {
        $tags = Tag::all();
        return response()->json($tags);
    }

    // Crear una nueva etiqueta
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $tag = Tag::create([
            'name' => $request->name,
        ]);

        return response()->json($tag, 201);
    }

    // Eliminar una etiqueta
    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();

        return response()->json(['message' => 'Etiqueta eliminada correctamente.']);
    }
}
