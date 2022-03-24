<?php

namespace Modules\Food\Http\Requests;

use App\GlobalRegex;
use App\Responses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Modules\Food\Rules\FoodIDRule;

class CreateMeasurementRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'foodID' => ['bail', 'required', 'string', 'regex:' . GlobalRegex::FOOD_ID_PATTERN,
                new FoodIDRule()],
            'measurement' => ['required', 'array'],
            'measurement.quantity' => ['required', 'array'],
            'measurement.quantity.amount' => ['required', 'numeric'],
            'measurement.quantity.unit' => ['required', 'string', Rule::in(config('food.quantity_units'))],
            'measurement.weight' => ['required', 'array'],
            'measurement.weight.amount' => ['nullable', 'numeric'],
            'measurement.weight.unit' => ['nullable', 'string', Rule::in(config('food.weight_units'))],
            'measurement.facts' => ['required', 'array'],
            'measurement.facts.calories' => ['required', 'array'],
            'measurement.facts.calories.amount' => ['required', 'numeric'],
            'measurement.facts.calories.unit' => ['required', 'string', 'in:cal,kcal'],
            'measurement.facts.protein' => ['required', 'array'],
            'measurement.facts.protein.amount' => ['required', 'numeric'],
            'measurement.facts.protein.unit' => ['required', 'string', Rule::in(config('food.weight_units'))],
            'measurement.facts.fat' => ['required', 'array'],
            'measurement.facts.fat.amount' => ['required', 'numeric'],
            'measurement.facts.fat.unit' => ['required', 'string', Rule::in(config('food.weight_units'))],
            'measurement.facts.carbs' => ['required', 'array'],
            'measurement.facts.carbs.amount' => ['required', 'numeric'],
            'measurement.facts.carbs.unit' => ['required', 'string', Rule::in(config('food.weight_units'))],
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
