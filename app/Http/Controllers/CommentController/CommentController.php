<?php

namespace App\Http\Controllers\CommentController;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequests\AddCommentRequest;
use App\Interfaces\CommentInterface\CommentInterface;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    private $commentInterface;

    public function __construct(CommentInterface $commentInterface)
    {
        $this->commentInterface = $commentInterface;
    }

    public function all($post_id) {
        return $this->commentInterface->all($post_id);
    }

    public function add(AddCommentRequest $addCommentRequest) {
        return $this->commentInterface->add($addCommentRequest);
    }

    public function edit(AddCommentRequest $addCommentRequest, $id) {
        return $this->commentInterface->edit($addCommentRequest, $id);
    }

    public function delete($post_id, $id) {
        return $this->commentInterface->delete($post_id, $id);
    }


}
