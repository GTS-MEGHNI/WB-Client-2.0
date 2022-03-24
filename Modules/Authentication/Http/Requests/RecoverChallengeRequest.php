<?php

namespace Modules\Authentication\Http\Requests;

use App\GlobalRegex;
use App\Responses;
use App\Utility;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use JetBrains\PhpStorm\Pure;
use Modules\Authentication\Rules\RecoverPasscodeRule;

class RecoverChallengeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[Pure] public function rules(): array
    {
        return Utility::remove_array_shape_tag([
            'passcode' => ['required', new RecoverPasscodeRule()],
        ]);
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
}
