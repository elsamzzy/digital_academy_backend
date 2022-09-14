<?php

namespace App\Interfaces\CommentInterface;

use App\Http\Requests\CommentRequests\AddCommentRequest;

interface CommentInterface
{
    public function all($post_id);
    public function add(AddCommentRequest $addCommentRequest);
    public function edit(AddCommentRequest $addCommentRequest, $id);
    public function delete($post_id, $id);
}
