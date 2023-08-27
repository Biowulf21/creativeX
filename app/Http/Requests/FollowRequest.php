<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class FollowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'follower_user_id' => 'required|integer|exists:users,id',
            'following_user_id' => 'required|integer|exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            'follower_user_id.exists' => 'The follower_user_id must be an existing user',
            'following_user_id.exists' => 'The user following must be an existing user',
        ];

    }


    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], Response::HTTP_BAD_REQUEST));
    }
}
