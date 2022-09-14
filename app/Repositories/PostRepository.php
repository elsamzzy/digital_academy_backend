<?php
namespace App\Repositories;

use App\Http\Requests\PostRequests\AddPostRequest;
use App\Interfaces\PostInterface\PostInterface;
use App\Models\Post;
use App\Models\Skill;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostRepository implements PostInterface
{
    use ApiResponse;

    public function all()
    {
        DB::beginTransaction();
        try {
            $posts = Post::with('owner', 'comments')->latest()->get();
            for($i=0; $i < count($posts); $i++) {
                for ($j=0; $j < count($posts[$i]->comments); $j++) {
                    $owner = User::where('id', $posts[$i]->comments[$j]->created_by)->first();
                    $posts[$i]->comments[$j]->owner = $owner;
                }
            }
            DB::commit();
            return $this->success('Fetched all posts', [
                'posts' => $posts,
            ], 200);
        } catch (\Exception $exception) {
            DB::rollback();
            Log::error($exception);
            return $this->error('There was an error', 500);
        }
    }

    public function add(AddPostRequest $addPostRequest)
    {
        DB::beginTransaction();
        try {
            $auth = Auth::user();
            Post::create([
                'post' => $addPostRequest['post'],
                'created_by' => $auth->id
            ]);
            $posts = Post::with('owner', 'comments')->latest()->get();
            for($i=0; $i < count($posts); $i++) {
                for ($j=0; $j < count($posts[$i]->comments); $j++) {
                    $owner = User::where('id', $posts[$i]->comments[$j]->created_by)->first();
                    $posts[$i]->comments[$j]->owner = $owner;
                }
            }
            DB::commit();
            return $this->success('Added post', [
                'posts' => $posts,
            ], 200);
        } catch (\Exception $exception) {
            DB::rollback();
            Log::error($exception);
            return $this->error('There was an error', 500);
        }
    }

    public function edit(AddPostRequest $addPostRequest, $id)
    {
        DB::beginTransaction();
        try {
            $auth = Auth::user();
            $post = Post::where('id', $id)->first();
            if ($post->created_by !== $auth->id) {
                return $this->error('Unauthorized', 401);
            }
            $post->post = $addPostRequest['post'];
            $post->updated_at = now();
            $post->save();
            $posts = Post::with('owner', 'comments')->latest()->get();
            for($i=0; $i < count($posts); $i++) {
                for ($j=0; $j < count($posts[$i]->comments); $j++) {
                    $owner = User::where('id', $posts[$i]->comments[$j]->created_by)->first();
                    $posts[$i]->comments[$j]->owner = $owner;
                }
            }
            DB::commit();
            return $this->success('Updated post', [
                'posts' => $posts,
            ], 200);
        } catch (\Exception $exception) {
            DB::rollback();
            Log::error($exception);
            return $this->error('There was an error', 500);
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $auth = Auth::user();
            $post = Post::where('id', $id)->latest()->first();
            if ($post->created_by !== $auth->id) {
                return $this->error('Unauthorized', 401);
            }
            $post->delete();
            $posts = Post::with('owner', 'comments')->get();
            for($i=0; $i < count($posts); $i++) {
                for ($j=0; $j < count($posts[$i]->comments); $j++) {
                    $owner = User::where('id', $posts[$i]->comments[$j]->created_by)->first();
                    $posts[$i]->comments[$j]->owner = $owner;
                }
            }
            DB::commit();
            return $this->success('Deleted post', [
                'posts' => $posts,
            ], 200);
        } catch (\Exception $exception) {
            DB::rollback();
            Log::error($exception);
            return $this->error('There was an error', 500);
        }
    }
}
