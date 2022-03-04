<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['string', 'required'],
            'phone' => ['string', 'required'],
            'address' => ['string', 'required'],
            'country' => ['string', 'required'],
            'facebook_username' => ['string', 'required'],
            'facebook_link' => ['string', 'required'],
            'instagram_username' => ['string', 'required'],
            'instagram_link' => ['string', 'required'],
            'youtube_username' => ['string', 'required'],
            'youtube_link' => ['string', 'required'],
        ];
    }
}
