<?php

namespace Modules\Payment\Rules;

use App\Responses;
use Illuminate\Contracts\Validation\Rule;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\Facades\Image;

class PaymentProofRule implements Rule
{
    private string $message;
    private mixed $data_type;
    private mixed $img;
    private string $mime;


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
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if ($this->notImage($value) && $this->notPdf($value)) {
            $this->message = Responses::FILE_NOT_VALID;
            return false;
        }

        if ($this->sizeOverflow($value)) {
            $this->message = Responses::SIZE_OVERFLOW;
            return false;
        }

        if ($this->data_type == 'img')
            if ($this->unauthorizedImageMime()) {
                $this->message = Responses::UNHANDLED_IMAGE_EXTENSION;
                return false;
            }

        request()->request->add(['mime' => $this->mime]);
        return true;
    }

    private function notImage($value): bool
    {
        $decoded_img = base64_decode($value);
        try {
            $this->img = Image::make($decoded_img);
        } catch (NotReadableException) {
            return true;
        }
        $this->data_type = 'img';
        return false;
    }

    private function notPdf($value): bool
    {
        $file_data = base64_decode($value);
        $f = finfo_open();
        $mime_type = finfo_buffer($f, $file_data, FILEINFO_MIME_TYPE);
        if ($mime_type === 'application/pdf') {
            $this->data_type = 'pdf';
            $this->mime = 'pdf';
        }
        return $mime_type != 'application/pdf';
    }

    private function sizeOverflow($value): bool
    {
        return (strlen(base64_decode($value)) / (1024 ** 2)) > config('payment.max_size');
    }

    private function unauthorizedImageMime(): bool
    {
        $this->mime = explode('/', $this->img->mime)[1];
        if (!in_array($this->mime, config('payment.mimes_allowed')))
            return true;
        return false;
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
