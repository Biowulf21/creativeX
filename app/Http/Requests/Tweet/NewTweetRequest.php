<?php

namespace App\Http\Requests\Tweet;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class NewTweetRequest extends FormRequest
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
            'tweet_body' => 'required|string|max:280',
            'replying_to' => 'nullable|exists:tweets,id',
            'user_id' => 'required|exists:users,id',
            'is_retweet' => 'boolean',
        ];
    }

    public function messages(){
        return [
            'user_id.exists' => 'The user_id must be an existing user',
            'replying_to.exists'=> 'You can only reply to an existing tweet'
        ];

    }


   /**
     * Handle a failed validation attempt.
     *
     * @param  Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], Response::HTTP_BAD_REQUEST));
    }
}
