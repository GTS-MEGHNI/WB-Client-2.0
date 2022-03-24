<?php

namespace Modules\Dashboard\Http\Requests;

use App\Responses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use JetBrains\PhpStorm\ArrayShape;
use Modules\Dashboard\Rules\ImageRule;
use Modules\Dashboard\Rules\ProgressRule;

class UpdateProgressRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'id' => ['bail', 'required', 'integer', 'exists:metrics.progresses', new ProgressRule()],
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
            'tracingPhotos.front.label' => ['required', 'string'],
            'tracingPhotos.front.data' => ['required', 'string', new ImageRule()],
            'tracingPhotos.back' => ['required', 'array'],
            'tracingPhotos.back.label' => ['required', 'string'],
            'tracingPhotos.back.data' => ['required', 'string', new ImageRule()],
            'tracingPhotos.left' => ['required', 'array'],
            'tracingPhotos.left.label' => ['required', 'string'],
            'tracingPhotos.left.data' => ['required', 'string', new ImageRule()],
            'tracingPhotos.right' => ['required', 'array'],
            'tracingPhotos.right.label' => ['required', 'string'],
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

    #[ArrayShape(['*' => "string"])]
    public function messages(): array
    {
        return [
            '*' => Responses::GENERAL_ERROR_FR
        ];
    }


}
