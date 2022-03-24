<?php

namespace Modules\Notification\Http\Requests;

use App\Responses;
use App\Utility;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use JetBrains\PhpStorm\Pure;

class NotificationListRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[Pure] public function rules(): array
    {
        return Utility::remove_array_shape_tag([
            'page' => ['required', 'integer'],
            'length' => ['required', 'integer'],
        ]);
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
