<?php

namespace Modules\Food\Rules;

use App\Responses;
use Illuminate\Contracts\Validation\Rule;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\Facades\Image;

class RecipePhotoRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    private string $message;

    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
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

        if ((strlen(base64_decode($value)) / (1024 ** 2)) > config('food.max_photo_size')) {
            $this->message = Responses::SIZE_OVERFLOW;
            return false;
        }

        $mime = explode('/', $img->mime)[1];
        if (!in_array($mime, config('food.photo_mimes_allowed'))) {
            $this->message = Responses::MIME_NOT_ALLOWED;
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
