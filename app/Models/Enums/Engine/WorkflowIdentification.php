<?php

namespace App\Models\Enums\Engine;

use MyCLabs\Enum\Enum;

class WorkflowIdentification extends Enum
{
    const ML_AD_SINGLE_ACTION_MENU = 'ml-advertisements-single-action-menu';
    const ML_AD_MULTIPLE_ACTION_MENU = 'ml-advertisements-multiple-action-menu';
    const ML_AD_EXTRA_FORMS = 'ml-advertisements-extra-forms';
    const ML_ORDER_SINGLE_ACTION_MENU = 'ml-orders-single-action-menu';
    const ML_ORDER_MULTIPLE_ACTION_MENU = 'ml-orders-multiple-action-menu';
}