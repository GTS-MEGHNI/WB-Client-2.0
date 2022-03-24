<?php

use App\Dictionary;

return [
    'name' => 'Subscription',
    'diets' => [Dictionary::BALANCED_DIET, Dictionary::FAT_LOSS_DIET, Dictionary::WEIGHT_GAIN_DIET,
        Dictionary::WEIGHT_MAINTAIN_DIET],
    'diet_types' => [Dictionary::VARIOUS, Dictionary::VEGAN],
    'plans' => [Dictionary::ONE_MONTH_SUBSCRIPTION, Dictionary::THREE_MONTHS_SUBSCRIPTION,
        Dictionary::SIX_MONTHS_SUBSCRIPTION],
    'genders' => [Dictionary::MALE, Dictionary::FEMALE],
    'budgets' => [Dictionary::ECONOMIC_BUDGET, Dictionary::MEDIUM_BUDGET, Dictionary::HIGH_BUDGET],
    'inactive_states' => [Dictionary::DELETED_ORDER, Dictionary::REJECTED_ORDER, Dictionary::CANCELED_ORDER,
        Dictionary::EXPIRED_ORDER, Dictionary::TERMINATED_ORDER]
];
