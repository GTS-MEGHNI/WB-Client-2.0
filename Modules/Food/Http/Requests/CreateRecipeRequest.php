<?php

namespace Modules\Food\Http\Requests;

use App\Responses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Modules\Food\Rules\IngredientQuantityUnitRule;
use Modules\Food\Rules\RecipePhotoRule;

class CreateRecipeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            "name" => ['required', 'string', 'max:100'],
            "description" => ['string', 'max:500'],
            'measurement' => ['required', 'array'],
            'measurement.quantity' => ['required', 'array'],
            'measurement.quantity.amount' => ['required', 'numeric'],
            'measurement.quantity.unit' => ['required', 'string', Rule::in(config('food.quantity_units'))],
            'measurement.weight' => ['array'],
            'measurement.weight.amount' => ['numeric'],
            'measurement.weight.unit' => ['string', Rule::in(config('food.weight_units'))],
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
            'preparationSteps' => ['required', 'array'],
            'preparationSteps.*.order' => ['required', 'integer', 'distinct'],
            'preparationSteps.*.content' => ['required', 'string', 'max:500'],
            'cooking' => ['required', 'array'],
            'cooking.method' => ['string', Rule::in(config('food.cooking_method'))],
            'cooking.time' => ['required', 'array'],
            'cooking.time.amount' => ['required', 'string'],
            'cooking.time.unit' => ['required', Rule::in(config('food.time_unit'))],
            'notes' => ['array'],
            'notes.*' => ['string', 'max:500', 'distinct'],
            'ingredients' => ['required', 'array'],
            'ingredients.*.id' => ['required', 'string', 'distinct', 'exists:food.ingredients,food_id'],
            'ingredients.*.quantity' => ['array'],
            'ingredients.*.quantity.amount' => ['required_with:ingredients.*.quantity', 'string'],
            'ingredients.*.quantity.unit' => ['required_with:ingredients.*.quantity', 'string',
                Rule::in(config('food.quantity_units')), new IngredientQuantityUnitRule()
            ],
            'photo' => ['required', 'array'],
            'photo.data' => ['required', 'string', new RecipePhotoRule()]
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
