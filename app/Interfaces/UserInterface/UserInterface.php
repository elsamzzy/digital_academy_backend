<?php

namespace App\Interfaces\UserInterface;

use App\Http\Requests\UserRequests\EditProfileRequest;
use App\Http\Requests\UserRequests\Login;
use App\Http\Requests\UserRequests\Register;

interface UserInterface
{
    public function register(Register $register);
    public function login(Login $login);
    public function getUserProfile();
    public function editUserProfile(EditProfileRequest $editProfileRequest);
}
