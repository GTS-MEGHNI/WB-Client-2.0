<?php

namespace Modules\Authentication\Http\Requests;

use App\GlobalRegex;
use App\Responses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use JetBrains\PhpStorm\ArrayShape;

class RecoverResetPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
     #[ArrayShape(['email' => "string[]", 'passcode' => "array", 'password' => "string[]"])]
     public function rules(): array
    {
        return [
            'password' => array('required', 'string', 'regex:' . GlobalRegex::PASSWORD_PATTERN),
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(Responses::DebugResponseError($validator->messages(),
                $validator->messages()->first())));
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

    #[ArrayShape(['password.*' => "string", '*' => "string"])]
    public function messages(): array
    {
        return [
            'password.*' => Responses::WRONG_PASSWORD,
            '*' => Responses::GENERAL_ERROR_FR
        ];
    }
}
