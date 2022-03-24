<?php

namespace Modules\Authentication\Http\Requests;

use App\Responses;
use App\Utility;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\Authentication\Rules\GoogleAuthTokenRule;

class GoogleAuthRequest extends FormRequest
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
            'token' => array('required', new GoogleAuthTokenRule())
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(Responses::DebugResponseError($validator->messages(),
                Responses::GENERAL_ERROR_FR)));
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
}
