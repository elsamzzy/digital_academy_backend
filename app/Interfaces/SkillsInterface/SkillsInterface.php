<?php

namespace App\Interfaces\SkillsInterface;


use App\Http\Requests\SkillRequests\AddSkillRequest;

interface SkillsInterface
{
    public function all();
    public function add(AddSkillRequest $addSkillRequest);
    public function edit(AddSkillRequest $addSkillRequest, $id);
    public function delete($id);
}
