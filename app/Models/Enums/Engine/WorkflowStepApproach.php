<?php

namespace App\Models\Enums\Engine;

use MyCLabs\Enum\Enum;

class WorkflowStepApproach extends Enum
{
    const SYNCHRONOUS = 'synchronous';
    const ASYNCHRONOUS = 'asynchronous';
}