<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Models\Admin;
use App\Http\Requests\AdminRequest;
use App\Service\ImageService;
use App\Http\Requests\ImageRequest;
use App\Models\Contact;

class AdminController extends Controller
{
    use ApiResponse;

    public function __construct(ImageService $service) 
    {
        $this->imageService = $service;
    }

    public function store(AdminRequest $request)
    {
        try {
            $admin = new Admin();
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->password = bcrypt($request->password);
            $admin->username = $request->username;
            $admin->roles = $request->role;
            $token = $admin->createToken('Authorization Token');
            $admin->save();
            return $this->success($token, 'Successfully created');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function update(Request $request, $admin)
    {
        try {
            Admin::findOrfail($admin);
            $response = Admin::where('id', $admin)->update([
                'roles' => $request->role,
                'name' => $request->name,
                'country' => $request->country,
                'phone' => $request->phone,
            ]);
            return $this->success($response, 'Updated successfully');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function profileUpload(ImageRequest $request, $admin)
    {
        try {
            $response = Admin::findOrFail($admin);
            $imageUri = $this->imageService->imageUpload($request->image, $path = 'profile/');   
            Admin::where('id',$admin)->update(['photo' => $imageUri]);         
            return $this->success($response, 'profile updated successfully');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function queryAll()
    {
        try {
            $admins = Admin::all();
            return $this->success($admins);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function querySingle($admin)
    {
        try {
            $admins = Admin::findOrFail($admin);
            return $this->success($admins);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function destory($admin)
    {
        try {
            $admin = Admin::findOrFail($admin);
            $admin->delete();
            return $this->success($admins, 'Deleted Successfully');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    public function supports()
    {
        try {
            $response = Contact::all();
            return $this->success($response);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }
}
