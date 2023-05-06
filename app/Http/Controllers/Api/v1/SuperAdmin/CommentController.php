<?php

namespace App\Http\Controllers\Api\v1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Comment\CommentCollection;
use App\Http\Resources\V1\Comment\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {

        $commentPiginate = Comment::paginate(20);
        return new CommentCollection($commentPiginate);
    }
    public function show($id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response([
                'message' => "کامنت مورد نظر یافت نشد",
                'status' => 'failed'
            ], 400);
        };
        return new CommentResource($comment);

    }

    public function confirm($id)
    {
        $comment = Comment::where('id', $id)->first();
        if (!$comment) {
            return response([
                'message' => "کامنت مورد نظر یافت نشد",
                'status' => 'failed'
            ], 400);
        };
        if ($comment->approved) {
            return response([
                'message' => "این کامنت قبلا تایید شده است",
                'status' => 'failed'
            ], 400);
        }
        $comment->update(['approved' => 1]);
        return response([
            'message' => "کامنت مورد نظر تایید شد",
            'status' => 'success'
        ], 200);
    }

    public function cancellation_approval($id): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $comment = Comment::where('id', $id)->first();
        if (!$comment) {
            return response([
                'message' => "کامنت مورد نظر یافت نشد",
                'status' => 'failed'
            ], 400);
        };
        if (!($comment->approved)) {
            return response([
                'message' => "این کامنت قبلا تایید نشده است",
                'status' => 'failed'
            ], 400);
        }
        $comment->update(['approved' => 0]);
        return response([
            'message' => "کامنت مورد نظر لغو تایید شد",
            'status' => 'success'
        ], 200);
    }

    public function unverified_comments()
    {
        $commentPiginate = Comment::where('approved', 0)->paginate(2);
        return new CommentCollection($commentPiginate);
    }

    public function reply_comment($id, Request $request)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response([
                'message' => "کامنت مورد نظر یافت نشد",
                'status' => 'failed'
            ], 400);
        };
        $data = $request->validate([
            'comment' => 'string|required',
        ]);
        $reply_comment = $comment->comments()->create([
            'creator_id' => auth()->user()->id,
            'name' => 'ادمین',
            'approved' => true,
            'comment' => $data['comment'],
            'commentable_type' => $comment->commentable_type,
            'commentable_id' => $comment->commentable_id
        ]);
        return response([
            'data' => [
                'id' => $reply_comment->id
            ],
            'message' => "کامنت مورد نظر توسط شما جواب داده شد",
            'status' => 'success'
        ], 200);
//        return new CommentResource($reply_comment);
    }
}
