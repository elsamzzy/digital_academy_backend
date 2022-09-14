<?php
namespace App\Repositories;

use App\Http\Requests\SkillRequests\AddSkillRequest;
use App\Http\Requests\UserRequests\EditProfileRequest;
use App\Http\Requests\UserRequests\Login;
use App\Http\Requests\UserRequests\Register;
use App\Interfaces\SkillsInterface\SkillsInterface;
use App\Interfaces\UserInterface\UserInterface;
use App\Models\Skill;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SkillsRepository implements SkillsInterface
{
    use ApiResponse;

    public function all()
    {
        DB::beginTransaction();
        try {
            $auth = Auth::user();
            $skills = Skill::where('created_by', $auth->id)->get();
            DB::commit();
            return $this->success('Fetched skills', [
                'skills' => $skills,
            ], 200);
        } catch (\Exception $exception) {
            DB::rollback();
            Log::error($exception);
            return $this->error('There was an error', 500);
        }
    }

    public function add(AddSkillRequest $addSkillRequest)
    {
        DB::beginTransaction();
        try {
            $auth = Auth::user();
            Skill::create([
                'skill_name' => $addSkillRequest['skill_name'],
                'skill_level' => $addSkillRequest['skill_level'],
                'created_by' => $auth->id
            ]);
            $skills = Skill::where('created_by', $auth->id)->get();
            DB::commit();
            return $this->success('Added skill', [
                'skills' => $skills,
            ], 200);
        } catch (\Exception $exception) {
            DB::rollback();
            Log::error($exception);
            return $this->error('There was an error', 500);
        }
    }

    public function edit(AddSkillRequest $addSkillRequest, $id)
    {
        DB::beginTransaction();
        try {
            $auth = Auth::user();
            $skill = Skill::where('created_by', $auth->id)->where('id', $id)->first();
            $skill->skill_name = $addSkillRequest['skill_name'];
            $skill->skill_level = $addSkillRequest['skill_level'];
            $skill->updated_at = now();
            $skill->save();
            $skills = Skill::where('created_by', $auth->id)->get();
            DB::commit();
            return $this->success('Updated skill', [
                'skills' => $skills,
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
            $skill = Skill::where('created_by', $auth->id)->where('id', $id);
            $skill->delete();
            $skills = Skill::where('created_by', $auth->id)->get();
            DB::commit();
            return $this->success('Deleted skill', [
                'skills' => $skills,
            ], 200);
        } catch (\Exception $exception) {
            DB::rollback();
            Log::error($exception);
            return $this->error('There was an error', 500);
        }
    }
}
