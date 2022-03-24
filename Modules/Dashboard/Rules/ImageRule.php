<?php

namespace Modules\Dashboard\Rules;

use App\Responses;
use Illuminate\Contracts\Validation\Rule;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\Facades\Image;

class ImageRule implements Rule
{

    private string $message;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $decoded_img = base64_decode($value);
        try {
            $img = Image::make($decoded_img);
        } catch (NotReadableException) {
            $this->message = Responses::INVALID_IMAGE;
            return false;
        }

        $mime = explode('/', $img->mime)[1];
        if (!in_array($mime, config('payment.mimes_allowed'))) {
            $this->message = Responses::UNHANDLED_IMAGE_EXTENSION;
            return false;
        }

        if(strlen(base64_decode($value)) / (1024 ** 2) > config('payment.max_size')) {
            $this->message = Responses::SIZE_OVERFLOW;
            return false;
        }
        request()->request->add(['mime' => $mime]);
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->message;
    }
}
