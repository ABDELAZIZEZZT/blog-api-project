<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\ReactionOnCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\DeleteCommentRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Reaction;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    public function getAllCommentsInBlog($blog_id): JsonResponse
    {
        $comments = Comment::where('blog_id', $blog_id)
            ->where('parent_id', null)
            ->orderBy('created_at', 'desc')
            ->with('user')
            ->get();
        return response()->json($comments);
    }

    public function store(CommentRequest $request): JsonResponse
    {
        $data=$request->validated();
        $data['user_id'] = auth()->user()->id;
        $comment = Comment::create($data);
        return response()->json($comment);
    }

    public function getReplies($comment_id): JsonResponse{
        $comment=Comment::findOrFail($comment_id);
        $replies = Comment::where('parent_id', $comment_id)
            ->with('user')
            ->get();
        return response()->json($replies);
    }

    public function destroy(DeleteCommentRequest $request,Comment $comment): JsonResponse
    {
        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }

    public function update(UpdateCommentRequest $request,Comment $comment): JsonResponse
    {
        $data = $request->validated();
        $comment->update($data);
        return response()->json($comment,200);
    }

 public function react(ReactionOnCommentRequest $request,$comment_id):JsonResponse
    {
        $data=$request->validated();
        $comment = Comment::findOrFail($comment_id);
        $reaction = Reaction::where('reactionable_type', 'App\Models\Comment')
                ->where('user_id', auth()->user()->id)
                ->where('reactionable_id', $comment->id)
                ->first();

        if ($reaction) {
            if($data['type'] == $reaction->type){
                $reaction->delete();
                return response()->json(['message' => 'Reaction deleted successfully'], 200);
            }
            $reaction->type = $data['type'];
            $reaction->save();
            return response()->json(['message' => 'Reaction updated successfully with '.$data['type']], 200);
        }
        $data['user_id'] = auth()->user()->id;
        $data['reactionable_id'] = $comment->id;
        $data['reactionable_type'] = 'App\Models\Comment';
        Reaction::createOrUpdate($data);
        return response()->json(['message' => 'Comment '.$data['type'] .' successfully'], 200);
    }
}
