<?php

namespace Modules\Food\Http\Requests;

use App\GlobalRegex;
use App\Responses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use JetBrains\PhpStorm\ArrayShape;
use Modules\Food\Rules\FoodIDRule;

class GetFoodRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape(['id' => "string[]"])] public function rules(): array
    {
        return [
            'id' => ['bail', 'required', 'string', 'regex:'.GlobalRegex::FOOD_ID_PATTERN,
                new FoodIDRule()]
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(Responses::DebugResponseError(
                $validator->errors(), $validator->messages()->first()))
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
