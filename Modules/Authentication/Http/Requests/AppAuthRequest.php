<?php

namespace Modules\Authentication\Http\Requests;

use App\GlobalRegex;
use App\Responses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AppAuthRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @noinspection PhpArrayShapeAttributeCanBeAddedInspection
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email:rfc,dns', 'exists:App\Models\User,email'],
            'password' => ['required', 'regex:'.GlobalRegex::PASSWORD_PATTERN]
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(Responses::DebugResponseError($validator->messages(),
                $validator->messages()->first()))
        );
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /** @noinspection PhpArrayShapeAttributeCanBeAddedInspection */
    public function messages(): array
    {
        return [
            'email.required' => Responses::EMAIL_REQUIRED,
            'email.email' => Responses::INVALID_EMAIL,
            'email.exists' => Responses::EMAIL_NOT_FOUND,
            'password.*' => Responses::WRONG_PASSWORD
        ];
    }
}
