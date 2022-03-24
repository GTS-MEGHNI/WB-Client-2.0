<?php

namespace Modules\Dashboard\Http\Requests;

use App\Responses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateDiaryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @noinspection PhpArrayShapeAttributeCanBeAddedInspection
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function rules()
    {
        return [
            'sleep' => ['required', 'string', Rule::in(config('dashboard.diary.sleep'))],
            'energy' => ['required', 'string', Rule::in(config('dashboard.diary.energy'))],
            'mood' => ['required', 'string', Rule::in(config('dashboard.diary.mood'))],
            'activities' => ['required', 'array'],
            'activities.*' => ['required', 'string', 'distinct',
                Rule::in(config('dashboard.diary.activities'))],
            'feelings' => ['required', 'array'],
            'feelings.*' => ['required', 'string', 'distinct',
                Rule::in(config('dashboard.diary.feelings'))],
            'title' => ['required', 'string', 'max:40'],
            'note' => ['string', 'max:400'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(Responses::DebugResponseError($validator->errors(),
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
