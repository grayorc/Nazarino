<?php

namespace App\Http\Controllers;

use App\Models\Election;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Option;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'election_id' => ['required'],
            'comment' => ['required','string','max:255'],
            'option_id' => ['required','exists:options,id'],
        ]);

        $election = Election::find($request->input('election_id'));
// subscription
        if(!$election->has_comment){
            return response()->json([
                'status' => 'failed',
                'message' => 'خطا!',
                'description' => 'نمیتوانید در این نظرسنجی، نظر ثبت کنید.',
            ]);
        }

        $comment = new Comment();
        $comment->body = $request->comment;
        $comment->user_id = auth()->user()->id;
        $comment->commentable_id = $request->option_id;
        $comment->commentable_type = 'App\Models\Option';
        $comment->save();

        if ($request->hasHeader('HX-Request')) {
            return response()->json([
                'status' => 'success',
                'message' => 'نظر شما با موفقیت ثبت شد',
                'comment' => [
                    'id' => $comment->id,
                    'body' => $comment->body,
                    'created_at' => $comment->created_at->diffForHumans(),
                    'user_name' => $comment->user->name ?? 'کاربر'
                ]
            ]);
        }
        return redirect()->back();
    }
}
