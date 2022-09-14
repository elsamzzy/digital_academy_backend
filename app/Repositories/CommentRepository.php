<?php
namespace App\Repositories;

use App\Http\Requests\CommentRequests\AddCommentRequest;
use App\Interfaces\CommentInterface\CommentInterface;
use App\Models\Comment;
use App\Models\Post;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommentRepository implements CommentInterface
{
    use ApiResponse;

    public function all($post_id)
    {
        DB::beginTransaction();
        try {
            $comment = Comment::where('posts_id', $post_id)->get();
            DB::commit();
            return $this->success('Fetched comments for a post', [
                'comment' => $comment,
            ], 200);
        } catch (\Exception $exception) {
            DB::rollback();
            Log::error($exception);
            return $this->error('There was an error', 500);
        }
    }

    public function add(AddCommentRequest $addCommentRequest)
    {
        DB::beginTransaction();
        try {
            $auth = Auth::user();
            Comment::create([
                'comment' => $addCommentRequest['comment'],
                'posts_id' => $addCommentRequest['posts_id'],
                'created_by' => $auth->id
            ]);
            $comment = Comment::where('posts_id', $addCommentRequest['posts_id'])->with('owner')->get();
            DB::commit();
            return $this->success('Added Comment', [
                'comment' => $comment,
            ], 200);
        } catch (\Exception $exception) {
            DB::rollback();
            Log::error($exception);
            return $this->error('There was an error', 500);
        }
    }

    public function edit(AddCommentRequest $addCommentRequest, $id)
    {
        DB::beginTransaction();
        try {
            $auth = Auth::user();
            $comment = Comment::where('id', $id)->where('posts_id', $addCommentRequest['posts_id'])->first();
            if (! $comment) {
                return $this->error('Comment does not exist', 400);
            }
            if ($comment->created_by !== $auth->id) {
                return $this->error('Unauthorized', 401);
            }
            $comment->comment = $addCommentRequest['comment'];
            $comment->updated_at = now();
            $comment->save();
            $comments = Comment::where('posts_id', $addCommentRequest['posts_id'])->get();
            DB::commit();
            return $this->success('Edited Comment', [
                'comment' => $comments,
            ], 200);
        } catch (\Exception $exception) {
            DB::rollback();
            Log::error($exception);
            return $this->error('There was an error', 500);
        }
    }

    public function delete($post_id, $id)
    {
        DB::beginTransaction();
        try {
            $auth = Auth::user();
            $comment = Comment::where('id', $id)->first();
            if ($comment->created_by !== $auth->id) {
                return $this->error('Unauthorized', 401);
            }
            $comment->delete();
            $comments = Comment::where('posts_id', $post_id)->with('owner')->get();
            DB::commit();
            return $this->success('Deleted comment', [
                'comment' => $comments,
            ], 200);
        } catch (\Exception $exception) {
            DB::rollback();
            Log::error($exception);
            return $this->error('There was an error', 500);
        }
    }
}
