<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Traits\ApiResponse;
use App\Service\ImageService;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Http\Requests\ImageRequest;

class UserController extends Controller
{
    use ApiResponse;

    public function __construct(ImageService $service) 
    {
        $this->imageService = $service;
    }

    public function profileUpload(ImageRequest $request, $user)
    {
        try {
            $response = User::findOrFail($user);
            $imageUri = $this->imageService->imageUpload($request->image, $path = 'profile/');
            User::where('id',$user)->update(['photo' => $imageUri]);         
            return $this->success('profile updated successfully');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function getUser($user)
    {
        try {
            $response = new UserResource(User::findOrFail($user));
            return $this->success($response);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function update(UserRequest $request, $user)
    {
        try {
            User::findOrfail($user);
            $response = User::where('id', $user)->update([
                'name' => $request->description,
                'country' => $request->country,
                'address' => $request->address,
                'phone' => $request->phone,
                'facebook_username' => $request->username,
                'facebook_link' => $request->facebook_link,
                'instagram_username' => $request->instagram_username,
                'instagram_link' => $request->instagram_link,
                'youtube_link' => $request->youtube_link,
                'youtube_username' => $request->youtube_username,
            ]);
            return $this->success($response, 'User updated successfully');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }
}
