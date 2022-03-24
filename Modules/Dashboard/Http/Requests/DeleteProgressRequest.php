<?php

namespace Modules\Dashboard\Http\Requests;

use App\Responses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use JetBrains\PhpStorm\ArrayShape;
use Modules\Dashboard\Rules\ProgressRule;

class DeleteProgressRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape(['id' => "array"])] public function
    rules(): array
    {
        return [
            'id' => ['bail', 'required', 'integer', 'exists:metrics.progresses', new ProgressRule()],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(Responses::DebugResponseError($validator->errors(),
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

    #[ArrayShape(['exists' => "mixed"])]
    public function messages(): array
    {
        return [
            'exists' => Responses::PROGRESS_DELETED
        ];
    }

}
