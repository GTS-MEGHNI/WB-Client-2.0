<?php

namespace Modules\Authentication\Http\Requests;

use App\Responses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\Authentication\Rules\FacebookAccountRule;
use Modules\Authentication\Rules\GoogleAccountRule;

class RecoverSubmitMailRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @noinspection PhpArrayShapeAttributeCanBeAddedInspection
     */
    public function rules(): array
    {
        return [
            'email' => array('required', 'email:rfc,dns', 'exists:App\Models\User,email',
                /*new FacebookAccountRule(), new GoogleAccountRule()*/)
        ];
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

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(Responses::DebugResponseError($validator->messages(),
                $validator->messages()->first())));
    }

    /** @noinspection PhpArrayShapeAttributeCanBeAddedInspection */
    public function messages(): array
    {
        return [
            'email.exists' => Responses::EMAIL_NOT_FOUND,
            'email.*' => Responses::INVALID_EMAIL
        ];
    }
}
