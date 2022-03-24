<?php

namespace Modules\Food\Http\Requests;

use App\Responses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class SearchFoodRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape(['search' => "string[]", 'search.keyword' => "string[]", 'length' => "string[]", 'filters' => "string[]", 'filters.type' => "string[]", 'filters.type.*' => "array"])]
    public function rules(): array
    {
        return [
            'search' => ['required', 'array'],
            'search.keyword' => ['nullable', 'string'],
            'length' => ['required', 'integer'],
            'filters' => ['required', 'array'],
            'filters.type' => ['required', 'array'],
            'filters.type.*' => ['required', 'string', Rule::in(config('food.types'))]
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(Responses::DebugResponseError($validator->errors(),
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
