<?php

namespace Modules\DietPlan\Http\Requests;

use App\Responses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use JetBrains\PhpStorm\ArrayShape;

class FoodRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape(['id' => "string[]"])] public function rules(): array
    {
        return [
            'id' => ['required', 'string', 'exists:diet_plans.meals_foods,id']
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
}
