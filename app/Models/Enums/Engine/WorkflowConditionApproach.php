<?php

namespace App\Models\Enums\Engine;

use MyCLabs\Enum\Enum;

class WorkflowConditionApproach extends Enum
{
    const AND = 'and';
    const OR = 'or';
}