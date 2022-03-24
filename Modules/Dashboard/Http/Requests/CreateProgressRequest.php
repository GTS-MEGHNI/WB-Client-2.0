<?php

namespace Modules\Dashboard\Http\Requests;

use App\Responses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\Dashboard\Rules\ImageRule;

class CreateProgressRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'weight' => ['required', 'numeric'],
            'bodyParts' => ['required', 'array'],
            'bodyParts.neck' => ['required', 'numeric'],
            'bodyParts.shoulders' => ['required', 'numeric'],
            'bodyParts.bust' => ['required', 'numeric'],
            'bodyParts.chest' => ['required', 'numeric'],
            'bodyParts.waist' => ['required', 'numeric'],
            'bodyParts.abs' => ['required', 'numeric'],
            'bodyParts.hips' => ['required', 'numeric'],
            'bodyParts.leftBicep' => ['required', 'numeric'],
            'bodyParts.rightBicep' => ['required', 'numeric'],
            'bodyParts.leftForearm' => ['required', 'numeric'],
            'bodyParts.rightForearm' => ['required', 'numeric'],
            'bodyParts.leftThigh' => ['required', 'numeric'],
            'bodyParts.rightThigh' => ['required', 'numeric'],
            'bodyParts.leftCalf' => ['required', 'numeric'],
            'bodyParts.rightCalf' => ['required', 'numeric'],
            'tracingPhotos' => ['required', 'array'],
            'tracingPhotos.front' => ['required', 'array'],
            'tracingPhotos.front.data' => ['required', 'string', new ImageRule()],
            'tracingPhotos.back' => ['required', 'array'],
            'tracingPhotos.back.data' => ['required', 'string', new ImageRule()],
            'tracingPhotos.left' => ['required', 'array'],
            'tracingPhotos.left.data' => ['required', 'string', new ImageRule()],
            'tracingPhotos.right' => ['required', 'array'],
            'tracingPhotos.right.data' => ['required', 'string', new ImageRule()],
            'restingHeartRate' => ['required', 'numeric']
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

    public function messages()
    {
        return [
            '*' => Responses::GENERAL_ERROR_FR
        ];
    }
}
