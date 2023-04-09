<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $house)
    {
        $this->validate($request, ['message' => 'required|max:1000']); //change comment field to message
        $comment = new Comment();
        $comment->house_id = $house;
        $comment->user_id = Auth::id();
        $comment->message = $request->message; //change comment field to message
        $comment->save();

        // Success message
        return redirect()->back()->with('success', 'The comment created successfully!');
    }
}
