<?php

use App\Dictionary;

return [
    'name' => 'Food',
    'types' => [Dictionary::NATIVE_INGREDIENT, Dictionary::NATIVE_RECIPE],
    'quantity_units' => ['teaspoon', 'tablespoon', 'unit', 'pinch', 'slice', 'cup', 'plate', 'gram', 'milliliter',
        'x-small', 'small', 'medium', 'large', 'x-large', 'clove', 'bundle', 'leaf'],
    'weight_units' => ['kg', 'g', 'mg', 'µg'],
    'category' => ['dairy', 'fruits', 'grains', 'protein', 'confections', 'vegetables', 'oils',
        'drinks', 'complements', 'spices'],
    'cooking_method' => ['Grilling', 'Steaming', 'Searing', 'Boiling', 'Sautéing', 'Poaching', 'Broiling',
        'Baking', 'Roasting', 'Blanching', 'Stewing', 'Frying', 'Deep-frying', 'Braising', 'Shallow-frying', 'Barbecue',
        'Simmering'],
    'time_unit' => ['h', 'min'],
    'max_photo_size' => 2,
    'photo_mimes_allowed' => ['png', 'jpg', 'jpeg']

];
