<?php

namespace App\Http\Controllers\SkillController;

use App\Http\Controllers\Controller;
use App\Http\Requests\SkillRequests\AddSkillRequest;
use App\Interfaces\SkillsInterface\SkillsInterface;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    protected $skillsInterface;

    public function __construct(SkillsInterface $skillsInterface)
    {
        $this->skillsInterface = $skillsInterface;
    }

    public function all() {
        return $this->skillsInterface->all();
    }

    public function add(AddSkillRequest $addSkillRequest) {
        return $this->skillsInterface->add($addSkillRequest);
    }

    public function edit(AddSkillRequest $addSkillRequest, $id) {
        return $this->skillsInterface->edit($addSkillRequest, $id);
    }

    public function delete($id) {
        return $this->skillsInterface->delete($id);
    }


}
