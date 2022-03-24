<?php

namespace Modules\Authentication\Http\Requests;

use App\Responses;
use App\Utility;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use JetBrains\PhpStorm\Pure;
use Modules\Authentication\Rules\SignPasscodeRule;

class QuickAuthChallengeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    protected $stopOnFirstFailure = true;

    #[Pure] public function rules(): array
    {
        return Utility::remove_array_shape_tag([
            'passcode' => ['required', new SignPasscodeRule()],
            'newsletter' => ['bool']
        ]);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(Responses::DebugResponseError($validator->messages(),
                $validator->errors()->first()))
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
