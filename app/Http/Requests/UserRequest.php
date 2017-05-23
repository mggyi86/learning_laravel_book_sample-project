<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\AuthTraits\OwnsRecord;

class UserRequest extends FormRequest
{
    use OwnsRecord;
    public function authorize()
    {
        if( ! $this->allowUserUpdate($this->user)){
            return false;
        }
        return true;
    }


    public function rules()
    {
        return [
            'name' => 'required|string|max:20|unique:users,name,'. $this->user,
            'email' => 'required|email|unique:users,email,'. $this->user,
            'is_subscribed' => 'boolean',
            'is_admin' => 'boolean',
            'status_id' => 'in:7,10',
        ];
    }
}
