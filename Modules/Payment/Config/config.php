<?php

use App\Dictionary;

return [
    'name' => 'Payment',
    'base_methods' => [Dictionary::CCP, Dictionary::BARIDIMOB],
    'mimes_allowed' => ['png', 'jpg', 'jpeg'],
    'max_size' => 6 //mb
];
