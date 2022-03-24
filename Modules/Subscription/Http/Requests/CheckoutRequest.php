<?php

namespace Modules\Subscription\Http\Requests;

use App\GlobalRegex;
use App\Responses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Modules\Subscription\Rules\CheckoutPlanRule;
use Modules\Subscription\Rules\WeightGoalRule;
use App\Dictionary;

class CheckoutRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'diet' => ['required', 'array'],
            'diet.program' => ['required', 'string', Rule::in(config('subscription.diets'))],
            'diet.type' => ['required_if:diet.program,' . Dictionary::BALANCED_DIET, 'string',
                Rule::in(config('subscription.diet_types'))],
            'diet.plan' => ['required', 'string', Rule::in(config('subscription.plans')),
                new CheckoutPlanRule($this->get('diet'))],
            'body' => ['required', 'array'],
            'body.gender' => ['required', 'string', Rule::in(config('subscription.genders'))],
            'body.age' => ['required', 'integer', 'between:6,100'],
            'body.weight' => ['required', 'numeric', 'between:20,400'],
            'body.height' => ['required', 'numeric', 'between:80,270'],
            'fitness' => ['required', 'array'],
            'fitness.isPractisingSport' => ['required', 'boolean'],
            'fitness.sportType' => ['required_if:fitness.isPractisingSport,==,true', 'string', 'max:200'],
            'fitness.sportPracticeTimes' => ['required_if:fitness.isPractisingSport,==,true', 'integer', 'max:20'],
            'fitness.isUsingSupplements' => ['required', 'boolean'],
            'fitness.hasDietExperience' => ['required', 'boolean'],
            'fitness.dietExperience' => ['required_if:fitness.hasDietExperience,==,true', 'string', 'max:200'],
            'fitness.hasFoodConsiderations' => ['required', 'boolean'],
            'fitness.foodConsiderations' => ['required_if:fitness.hasFoodConsiderations,==,true', 'string', 'max:400'],
            'health' => ['required', 'array'],
            'health.hasPhysicalHealthIssues' => ['required', 'boolean'],
            'health.physicalHealthIssues' => ['required_if:hasPhysicalHealthIssues,==,true', 'string',
                'max:200'],
            'health.isTakingMedications' => ['required_if:hasPhysicalHealthIssues,==,true', 'boolean'],
            'health.medications' => ['required_if:isTakingMedications,==,true', 'string',
                'max:200'],
            'health.hasMentalHealthIssues' => ['required', 'boolean'],
            'health.mentalHealthState' => ['required_if:hasMentalHealthIssues,==,true', 'string',
                'max:200'],
            'health.isPregnant' => ['required_if:body.gender,==,' . Dictionary::FEMALE, 'boolean'],
            'health.pregnancyMonth' => ['required_if:health.isPregnant,==,true', 'integer', 'between:1,9'],
            'health.isBreastfeeding' => ['required_if:body.gender,==,' . Dictionary::FEMALE, 'boolean'],
            'health.lactationMonth' => ['required_if:health.isBreastfeeding,==,true', 'integer', 'between:1,36'],
            'vision' => ['required', 'array'],
            'vision.weightGoal' => ['required_if:diet.program,' . Dictionary::WEIGHT_GAIN_DIET . ',' . Dictionary::FAT_LOSS_DIET,
                new WeightGoalRule()],
            'vision.objectives' => ['required', 'string', 'max:200'],
            'vision.budget' => ['required', 'string', Rule::in(config('subscription.budgets'))],
            'vision.willPractiseSport' => ['required', 'boolean'],
            'vision.sportPracticeTimes' => ['required_if:vision.willPractiseSport,==,true', 'integer', 'between:1,7'],
            'vision.hasSpecialRequest' => ['required', 'boolean'],
            'vision.specialRequest' => ['required_if:vision.hasSpecialRequest,==,true', 'string', 'max:200'],
            'personal' => ['required', 'array'],
            'personal.firstname' => ['required', 'string', 'regex:' . GlobalRegex::NAME_PATTERN],
            'personal.lastname' => ['required', 'string', 'regex:' . GlobalRegex::NAME_PATTERN],
            'personal.country' => ['required', 'string', 'max:50'],
            'personal.province' => ['required', 'string', 'max:50']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(Responses::DebugResponseError($validator->messages(),
                Responses::GENERAL_ERROR_FR))
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
