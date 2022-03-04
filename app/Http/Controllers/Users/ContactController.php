<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Models\Contact;

class ContactController extends Controller
{
    use ApiResponse;

    public function create(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'fullname' => 'required|string',
                'message' => 'required|string',
                'subject' => 'required|string',
                'phone' => 'required|string'
            ]);

            $response = Contact::create([
                'email' => $request->email,
                'fullname' => $request->fullname,
                'message' => $request->message,
                'subject' => $request->subject,
                'phone' => $request->phone
            ]);

            return $this->success($response, 'successful');
            
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }
}
