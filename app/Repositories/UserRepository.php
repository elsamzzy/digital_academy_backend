<?php
namespace App\Repositories;

use App\Http\Requests\UserRequests\EditProfileRequest;
use App\Http\Requests\UserRequests\Login;
use App\Http\Requests\UserRequests\Register;
use App\Interfaces\UserInterface\UserInterface;
use App\Models\Post;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserRepository implements UserInterface
{
    use ApiResponse;

    public function register(Register $register)
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $register['name'],
                'username' => $register['username'],
                'email' => $register['email'],
                'phone_number' => $register['phone_number'],
                'password' => Hash::make($register['password']),
                'active' => false,
                'activation_token' => bin2hex(random_bytes(4)),
            ]);
            DB::commit();
            return $this->success('Successfully Registered', [
                'user' => $user,
            ], 200);
        } catch (\Exception $exception) {
            DB::rollback();
            Log::error($exception);
            return $this->error('There was an error registering user', 500);
        }
    }

    public function Login(Login $login)
    {
        DB::beginTransaction();
        try {
            if ($this->checkEmail($login['credential'])) {
                $login['email'] = $login['credential'];
                if (!Auth::attempt($login->only(['email', 'password']))) {
                    return $this->error('Incorrect Credentials', 401);
                }
            } else {
                $login['phone_number'] = $login['credential'];
                if (!Auth::attempt($login->only(['phone_number', 'password']))) {
                    return $this->error('Incorrect Credentials', 401);
                }
            }
            $user = Auth::user();

            $tokenResult = $user->createToken('da');
            $token = $tokenResult->plainTextToken;
            if ($login['remember_me'])
                $tokenResult->expires_at = Carbon::now()->addWeeks(1);
            $user->update([
                'last_login_at' => now(),
            ]);
            $user['token'] = $token;
            DB::commit();
            return $this->success('You have successfully Logged In.', [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user'=> response()->json($user)->original
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return $this->error($exception->getMessage(), 500);
        }
    }

    public function getUserProfile()
    {
        DB::beginTransaction();
        try {
            $auth = Auth::user();
            $user = User::where('id', $auth->id)->first();
            $posts = Post::where('created_by', $auth->id)->with('comments')->get();
            $user['posts'] = $posts;
            DB::commit();
            return $this->success('Fetched user', [
                'user' => $user,
            ], 200);
        } catch (\Exception $exception) {
            DB::rollback();
            Log::error($exception);
            return $this->error('There was an error', 500);
        }
    }

    public function editUserProfile(EditProfileRequest $editProfileRequest)
    {
        DB::beginTransaction();
        try {
            $auth = Auth::user();
            User::where('id', $auth->id)->update([
                'username'  => $editProfileRequest['username'] ? $editProfileRequest['username'] : $auth->username,
                'name'  => $editProfileRequest['name'] ? $editProfileRequest['name'] : $auth->name,
                'title'  => $editProfileRequest['title'] ? $editProfileRequest['title'] : $auth->title,
                'updated_at' => now()
            ]);
            $user = User::where('id', $auth->id)->first();
            $posts = Post::where('created_by', $auth->id)->with('comments')->get();
            $user['posts'] = $posts;
            DB::commit();
            return $this->success('Successfully updated profile', [
                'user' => $user,
            ], 200);
        } catch (\Exception $exception) {
            DB::rollback();
            Log::error($exception);
            return $this->error('There was an error', 500);
        }
    }

    public function checkEmail($email) {
        $find1 = strpos($email, '@');
        $find2 = strpos($email, '.');
        return ($find1 !== false && $find2 !== false);
    }
}
