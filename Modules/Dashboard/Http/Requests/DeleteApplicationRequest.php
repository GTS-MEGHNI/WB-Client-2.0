<?php

namespace Modules\Dashboard\Http\Requests;

use App\GlobalRegex;
use App\Responses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use JetBrains\PhpStorm\ArrayShape;
use Modules\Dashboard\Rules\DeleteRule;

class DeleteApplicationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape(['id' => "array"])]
    public function rules(): array
    {
        return [
            'id' => ['bail', 'required', 'string', 'regex:' . GlobalRegex::ORDER_ID_PATTERN,
                'exists:orders.orders,order_id', new DeleteRule()]
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(Responses::DebugResponseError($validator->errors(),
                Responses::CANNOT_DELETE))
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
