<?php
namespace App\Http\Requests\Tweet;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class UpdateTweetRequest extends FormRequest
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
            'new_tweet_body' => 'required|string|max:280|min:1'
        ];
    }

    public function messages(){
        return [
            'new_tweet_body.exists' => 'Cannot update this tweet. It does not exist.',
            'new_tweet_body.max' => 'Tweet body cannot be more than 280 characters.',
            'new_tweet_body.min' => 'Tweet body cannot be less than 1 character.',
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
