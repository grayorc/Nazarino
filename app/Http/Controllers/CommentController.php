<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\User;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Option;
use Illuminate\Support\Facades\Auth;

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
        // TODO : move to policy
        if(!$election->has_comment){
            return response()->json([
                'status' => 'failed',
                'message' => 'خطا!',
                'description' => 'نمیتوانید در این نظرسنجی، نظر ثبت کنید.',
            ]);
        }

        $comment = Comment::create([
            'body' => $request->comment,
            'user_id' => auth()->user()->id,
            'commentable_id' => $request->option_id,
            'commentable_type' => Option::class,
        ]);

        $userName = auth()->user()->name ?? 'کاربر';

        if ($request->hasHeader('HX-Request')) {
            return response()->json([
                'status' => 'success',
                'message' => 'نظر شما با موفقیت ثبت شد',
                'comment' => [
                    'id' => $comment->id,
                    'body' => $comment->body,
                    'created_at' => $comment->created_at->diffForHumans(),
                    'user_name' => $userName
                ]
            ]);
        }
        return redirect()->back();
    }

    /**
     * Delete a comment
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        // TODO : move to policy
        if (Auth::user()->id !== $comment->user_id) {
            if ($comment->commentable instanceof Option) {
                $election = $comment->commentable->election;
                if ($election && $election->user_id !== Auth::user()->id) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'شما مجاز به حذف این نظر نیستید'
                    ], 403);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'شما مجاز به حذف این نظر نیستید'
                ], 403);
            }
        }

        $comment->delete();

        if (request()->hasHeader('HX-Request')) {
            return response('', 200);
        }

        return redirect()->back()->with('success', 'نظر با موفقیت حذف شد');
    }
}
