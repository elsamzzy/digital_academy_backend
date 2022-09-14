<?php

namespace App\Http\Controllers\UserController;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequests\EditProfileRequest;
use App\Http\Requests\UserRequests\Login;
use App\Http\Requests\UserRequests\Register;
use App\Interfaces\UserInterface\UserInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userInterface;

    public function __construct(UserInterface $userInterface)
    {
        $this->userInterface = $userInterface;
    }

    public function register(Register $register) {
        return $this->userInterface->register($register);
    }

    public function login(Login $login) {
        return $this->userInterface->login($login);
    }

    public function getUserProfile() {
        return $this->userInterface->getUserProfile();
    }

    public function editUserProfile(EditProfileRequest $editProfileRequest) {
        return $this->userInterface->editUserProfile($editProfileRequest);
    }
}
