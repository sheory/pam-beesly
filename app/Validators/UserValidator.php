<?php


namespace App\Validators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserValidator
{
    public static function validate($data, Array $roles = [])
    {
        $id = $data['id'] ?? null;

        $validator = Validator::make($data, array_merge([
            'name'  => 'required|max:255',
            'email' => [
                'required',
                Rule::unique('users')->ignore($id),
            ],
            'password' => 'required|confirmed',
            'role_id'  => 'required|exists:roles,id',
            ],
            $roles));

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
