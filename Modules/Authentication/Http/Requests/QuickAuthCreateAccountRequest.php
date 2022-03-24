<?php

namespace Modules\Authentication\Http\Requests;

use App\GlobalRegex;
use App\Responses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use JetBrains\PhpStorm\ArrayShape;

class QuickAuthCreateAccountRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape(['firstname' => "string[]", 'lastname' => "string[]", 'email' => "string[]"])]
    public function rules(): array
    {
        return [
            'firstname' => array('required', 'regex:' . GlobalRegex::NAME_PATTERN),
            'lastname' => array('required', 'regex:' . GlobalRegex::NAME_PATTERN),
            'email' => array('required', 'email:rfc,dns'),
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

    #[ArrayShape(['firstname.*' => "string", 'lastname.*' => "string", 'email.unique' => "string", 'email.*' => "string", 'password.*' => "string"])]
    public function messages(): array
    {
        return [
            'firstname.*' => Responses::INVALID_FIRST_NAME,
            'lastname.*' => Responses::INVALID_LAST_NAME,
            'email.*' => Responses::INVALID_EMAIL,
        ];
    }
}
