<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine package.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace GingerWorkflowEngine\Model\WorkflowRun;

use Codeliner\Domain\Shared\EntityInterface;
use GingerCore\Model\Workflow\WorkflowId;
use GingerWorkflowEngine\Model\Action\Action;
use GingerWorkflowEngine\Model\Action\ActionId;
use GingerWorkflowEngine\Model\Action\Arguments;
use GingerWorkflowEngine\Model\Action\Name;
use GingerWorkflowEngine\Model\Action\Type;
use GingerWorkflowEngine\Model\WorkflowRun\Event\WorkflowRunCreated;
use GingerWorkflowEngine\Model\WorkflowRun\Event\WorkflowRunStarted;
use GingerWorkflowEngine\Model\WorkflowRun\Event\WorkflowRunStopped;
use GingerWorkflowEngine\Model\WorkflowRun\Exception\ActionCreationFailedException;
use GingerWorkflowEngine\Model\WorkflowRun\Exception\WorkflowRunAlreadyStartedException;
use GingerWorkflowEngine\Model\WorkflowRun\Exception\WorkflowRunAlreadyStoppedException;
use GingerWorkflowEngine\Model\WorkflowRun\Exception\WorkflowRunNotStartedException;
use GingerWorkflowEngine\Model\WorkflowRun\Exception\WorkflowRunNotStoppedException;
use Malocher\EventStore\EventSourcing\EventSourcedObject;
use Rhumsaa\Uuid\Uuid;

/**
 * Root of the WorkflowRun-Aggregate
 *
 * A WorkflowRun manages a set of associated Actions.
 * It collects basic information like the time when it starts execution and when it ends.
 * It is responsible for creating Actions and prepare them for running.
 * After an Action was processed the Result or the published Events caused by an Action are
 * collected and passed to the WorkflowRun, so that they can be requested by Roles that perform
 * following Actions.
 * 
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class WorkflowRun extends EventSourcedObject implements EntityInterface
{
    /**
     * @var WorkflowRunId
     */
    private $workflowRunId;

    /**
     * @var WorkflowId
     */
    private $workflowId;

    /**
     * @var \DateTime
     */
    private $startedOn;

    /**
     * @var \DateTime
     */
    private $stoppedOn;

    /**
     * @param WorkflowRunId $workflowRunId
     */
    public function __construct(WorkflowRunId $aWorkflowRunId, WorkflowId $aWorkflowId)
    {
        //construct is not invoked, when WorkflowRun is reloaded, so we know that this is a new WorkflowRun
        $this->update(
            new WorkflowRunCreated(
                array(
                    'workflowRunId' => $aWorkflowRunId->toString(),
                    'workflowId'    => $aWorkflowId->toString(),
                )
            )
        );
    }

    /**
     * @return WorkflowRunId
     */
    public function workflowRunId()
    {
        return $this->workflowRunId;
    }

    /**
     * @return WorkflowId
     */
    public function workflowId()
    {
        return $this->workflowId;
    }

    /**
     * @throws Exception\WorkflowRunAlreadyStartedException
     */
    public function start()
    {
        if ($this->isStarted()) {
            throw new WorkflowRunAlreadyStartedException(
                sprintf(
                    'WorkflowRun: %s is already started',
                    $this->workflowRunId()->toString()
                )
            );
        }

        $this->update(new WorkflowRunStarted(array('workflowRunId' => $this->workflowRunId()->toString())));
    }

    /**
     * @return bool
     */
    public function isStarted()
    {
        return !is_null($this->startedOn);
    }

    /**
     * @return \DateTime
     * @throws Exception\WorkflowRunNotStartedException
     */
    public function startedOn()
    {
        if (is_null($this->startedOn)) {
            throw new WorkflowRunNotStartedException(
                sprintf(
                    'WorkflowRun: -%s- not started',
                    $this->workflowRunId()->toString()
                )
            );
        }

        return $this->startedOn;
    }

    /**
     * @return bool
     */
    public function isRunning()
    {
        return $this->isStarted() && !$this->isStopped();
    }

    /**
     * @throws Exception\WorkflowRunNotStartedException
     * @throws Exception\WorkflowRunAlreadyStoppedException
     */
    public function stop()
    {
        if (!$this->isStarted()) {
            throw new WorkflowRunNotStartedException(
                sprintf(
                    'WorkflowRun: -%s- not started',
                    $this->workflowRunId()->toString()
                )
            );
        }

        if ($this->isStopped()) {
            throw new WorkflowRunAlreadyStoppedException(
                sprintf(
                    'WorkflowRun: -%s- already stopped',
                    $this->workflowRunId()->toString()
                )
            );
        }

        $this->update(new WorkflowRunStopped(array('workflowRunId' => $this->workflowRunId()->toString())));
    }

    /**
     * @return bool
     */
    public function isStopped()
    {
        return !is_null($this->stoppedOn);
    }

    /**
     * @return \DateTime
     * @throws Exception\WorkflowRunNotStoppedException
     */
    public function stoppedOn()
    {
        if (!$this->isStopped()) {
            throw new WorkflowRunNotStoppedException(
                sprintf(
                    'WorkflowRun: -%s- not stopped',
                    $this->workflowRunId()->toString()
                )
            );
        }

        return $this->stoppedOn;
    }

    /**
     * @param Name      $aName
     * @param Type      $aType
     * @param Arguments $anArguments
     * @return Action
     * @throws Exception\ActionCreationFailedException
     */
    public function createAction(Name $aName, Type $aType, Arguments $anArguments)
    {
        if (!$this->isRunning()) {
            throw new ActionCreationFailedException(
                sprintf(
                    'Action creation failed. WorkflowRun %s is not running',
                    $this->workflowRunId()->toString()
                )
            );
        }

        return new Action(
            new ActionId(Uuid::uuid4()),
            $aType,
            $aName,
            $anArguments,
            $this->workflowRunId()

        );
    }

    /**
     * @param EntityInterface $other
     * @return boolean
     */
    public function sameIdentityAs(EntityInterface $other)
    {
        if (!$other instanceof WorkflowRun) {
            return false;
        }

        return $this->workflowRunId->sameValueAs($other->workflowRunId());
    }

    /**
     * Register internal event handler methods
     */
    protected function registerHandlers()
    {
        $this->handlers['WorkflowRunCreated'] = 'onWorkflowRunCreated';
        $this->handlers['WorkflowRunStarted'] = 'onWorkflowRunStarted';
        $this->handlers['WorkflowRunStopped'] = 'onWorkflowRunStopped';
    }

    /**
     * @param WorkflowRunCreated $event
     */
    protected function onWorkflowRunCreated(WorkflowRunCreated $event)
    {
        $this->workflowRunId = $event->workflowRunId();
        $this->workflowId    = $event->workflowId();
    }

    /**
     * @param WorkflowRunStarted $event
     */
    protected function onWorkflowRunStarted(WorkflowRunStarted $event)
    {
        $this->startedOn = $event->occurredOn();
    }

    protected function onWorkflowRunStopped(WorkflowRunStopped $event)
    {
        $this->stoppedOn = $event->occurredOn();
    }

    /**
     * Required by EventStore
     *
     * @param string $id
     */
    protected function setId($id)
    {
        $this->workflowRunId = new WorkflowRunId(Uuid::fromString($id));
    }

    /**
     * Required by EventStore
     *
     * @return string
     */
    protected function getId()
    {
        return $this->workflowRunId->toString();
    }
}
