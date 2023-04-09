<?php

namespace App\Http\Controllers\Renter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Comment;
use App\CommentReply;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::where('user_id', Auth::id())->latest()->get();
        return view('user.comments.index', compact('comments'));
    }
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        if ($comment->user_id == Auth::id()) {
            // Delete replies
            $replies = CommentReply::where('comment_id', $id)->delete();
            $comment->delete();
            Toastr::success('Comment successfully deleted :)');
            return redirect()->back();
        } else {
            Toastr::error('You can not delete this comment :(');
            return redirect()->back();
        }
    }
}
