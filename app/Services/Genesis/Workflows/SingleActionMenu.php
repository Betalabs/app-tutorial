<?php

namespace App\Services\Genesis\Workflows;

use App\Models\Enums\Engine\WorkflowConditionApproach as Approach;
use App\Models\Enums\Engine\WorkflowConditionOperator as Operator;
use App\Models\Enums\Engine\WorkflowIdentification;
use App\Models\Enums\Engine\WorkflowStepApproach;
use App\Services\Engine\Event\Indexer as EventIndexer;
use App\Services\Engine\Listener\Indexer as ListenerIndexer;
use App\Services\Engine\Workflow\Creator as WorkflowCreator;
use App\Services\Engine\Workflow\Condition\Creator as Condition;
use App\Services\Engine\Workflow\Updater as WorkflowUpdater;
use App\Services\Engine\Workflow\Step\Creator as StepCreator;
use Illuminate\Support\Facades\Auth;

class SingleActionMenu
{
    const TRANS_PATH = 'app/Services/App/Genesis/Engine/Virtualization/Workflow/Order/SingleActionMenu.';
    const LISTENER_CLASS = 'App\Listeners\EngineListeners\AppDispatcher';
    const LISTENER_METHOD = 'get';
    const EVENT_CLASS = 'App\Services\MenuAction\Service';
    const EVENT_METHOD = 'singleExtra';

    /**
     * @var \App\Services\Engine\Event\Indexer
     */
    private $events;
    /**
     * @var \App\Services\Engine\Listener\Indexer
     */
    private $listeners;
    /**
     * @var \App\Services\Engine\Workflow\Updater
     */
    private $workflow;

    /**
     * Creator constructor.
     *
     * @param \App\Services\Engine\Event\Indexer $events
     * @param \App\Services\Engine\Listener\Indexer $listeners
     * @param \App\Services\Engine\Workflow\Updater $workflow
     */
    public function __construct(
        EventIndexer $events,
        ListenerIndexer $listeners,
        WorkflowUpdater $workflow
    ) {
        $this->events = $events;
        $this->listeners = $listeners;
        $this->workflow = $workflow;
    }

    /**
     * Create action-menu Workflows
     */
    public function create()
    {
        $events = $this->events
            ->setQuery([
                'class' => self::EVENT_CLASS,
                'method' => self::EVENT_METHOD,
                '_with' => 'params',
            ])
            ->setLimit(1)
            ->retrieve();

        if ($events->isEmpty()) {
            throw new \RuntimeException('Engine Events not found.');
        }

        $listeners = $this->listeners
            ->setQuery([
                'class' => self::LISTENER_CLASS,
                'method' => self::LISTENER_METHOD,
                '_with' => 'params',
            ])
            ->setLimit(1)
            ->retrieve();

        if ($listeners->isEmpty()) {
            throw new \RuntimeException('Listeners not found.');
        }

        $event = $events->first();
        $name = trans(self::TRANS_PATH . 'workflow.name');
        $identification = WorkflowIdentification::ML_ORDER_SINGLE_ACTION_MENU;
        $workflow = (new WorkflowCreator($name, $event->id))
            ->setIdentification($identification)
            ->create();

        $eventParam = $this->searchEventParam('entity', $event->params);
        (new Condition(
            $workflow->id,
            $eventParam->id,
            'order',
            new Operator(Operator::EQUAL),
            new Approach(Approach:: AND)
        ))->create();

        /** @var \Betalabs\LaravelHelper\Models\EngineRegistry $engineRegistry */
        $engineRegistry = Auth::user()->engineRegistry;
        $listener = $listeners->first();
        $approach = WorkflowStepApproach::SYNCHRONOUS;
        $appRegistryParam = $this->searchEventParam(
            'appRegistryId',
            $listener->params
        );
        $appUriParam = $this->searchEventParam('uri', $listener->params);
        $idEventParam = $this->searchEventParam('id', $event->params);
        $step = (new StepCreator($workflow->id, $listener->id, $approach))
            ->setParams([
                [
                    'engine_listener_param_id' => $appRegistryParam->id,
                    'value' => $engineRegistry->registry_id,
                ],
                [
                    'engine_listener_param_id' => $appUriParam->id,
                    'value' => 'orders/',
                ],
                [
                    'engine_event_param_id' => $idEventParam->id,
                    'engine_listener_param_id' => $appUriParam->id,
                ],
                [
                    'engine_listener_param_id' => $appUriParam->id,
                    'value' => '/action-menu',
                ],
            ])
            ->create();

        $this->workflow
            ->setWorkflowId($workflow->id)
            ->setWorkflowStepId($step->id)
            ->update();
    }

    /**
     * Search Workflow Event Param by name
     *
     * @param string $name
     * @param array $params
     *
     * @return \stdClass|null
     */
    private function searchEventParam(string $name, array $params)
    {
        foreach ($params as $key => $param) {
            if ($param->name == $name) {
                return $params[$key];
            }
        }

        return null;
    }
}