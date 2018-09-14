<?php

namespace App\Listeners\Genesis;

use App\Services\Genesis\Workflows\MultipleActionMenu;
use App\Services\Genesis\Workflows\SingleActionMenu;
use Betalabs\LaravelHelper\Models\Tenant;

class CreateWorkflows extends Base
{

    /** @var \App\Services\Genesis\Workflows\SingleActionMenu */
    private $singleActionMenu;

    /** @var \App\Services\Genesis\Workflows\MultipleActionMenu */
    private $multipleActionMenu;

    /**
     * CreateWorkflows constructor.
     *
     * @param \App\Services\Genesis\Workflows\SingleActionMenu $singleActionMenu
     * @param \App\Services\Genesis\Workflows\MultipleActionMenu $multipleActionMenu
     */
    public function __construct(
        SingleActionMenu $singleActionMenu,
        MultipleActionMenu $multipleActionMenu
    ) {
        $this->singleActionMenu = $singleActionMenu;
        $this->multipleActionMenu = $multipleActionMenu;
    }


    /**
     * Handle the event.
     *
     * @param \Betalabs\LaravelHelper\Models\Tenant $tenant
     */
    public function handle(Tenant $tenant)
    {
        // Do not forget to authenticate
        $this->authenticate($tenant);

        // Services to create workflows
        $this->singleActionMenu->create();
        $this->multipleActionMenu->create();
    }

}