<?php

namespace App\Interfaces\PostInterface;


use App\Http\Requests\PostRequests\AddPostRequest;

interface PostInterface
{
    public function all();
    public function add(AddPostRequest $addPostRequest);
    public function edit(AddPostRequest $addPostRequest, $id);
    public function delete($id);

}
