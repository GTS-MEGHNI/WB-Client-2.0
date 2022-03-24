<?php

namespace Modules\Authentication\Http\Requests;

use App\GlobalRegex;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Authentication\Rules\SignPasscodeRule;

class QuickAuthValidateAccountRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['bail', 'required', 'email:rfc,dns', 'unique:App\Models\User,email', 'exists:register_logs,email'],
            'passcode' => ['required', 'string', 'regex:' . GlobalRegex::PASSCODE_PATTERN,
                new SignPasscodeRule($this->get('email'))],
            'newsletter' => ['required', 'bool']
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
