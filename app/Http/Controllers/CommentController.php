<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;  // tu modelo Comment
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|string',
            'texto' => 'required|string|max:1000',
        ]);

        $comment = new Comment();
        $comment->post_id = $request->post_id;
        $comment->usuario_id = Auth::id();  // asumiendo que el usuario está autenticado
        $comment->texto = $request->texto;
        $comment->fecha = now();

        $comment->save();

        return response()->json([
            'success' => true,
            'comment' => $comment,
            'user_name' => Auth::user()->name,
            'formatted_date' => $comment->fecha->format('d \d\e F \d\e Y, H:i'),
        ]);
    }
}
