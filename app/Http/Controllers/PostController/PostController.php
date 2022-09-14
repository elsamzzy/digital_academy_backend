<?php

namespace App\Http\Controllers\PostController;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequests\AddPostRequest;
use App\Interfaces\PostInterface\PostInterface;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $postInterface;

    public function __construct(PostInterface $postInterface)
    {
        $this->postInterface = $postInterface;
    }

    public function all() {
        return $this->postInterface->all();
    }

    public function add(AddPostRequest $addPostRequest) {
        return $this->postInterface->add($addPostRequest);
    }

    public function edit(AddPostRequest $addPostRequest, $id) {
        return $this->postInterface->edit($addPostRequest, $id);
    }

    public function delete($id) {
        return $this->postInterface->delete($id);
    }
}
