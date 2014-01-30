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
use GingerWorkflowEngine\Model\WorkflowRun\Event\WorkflowRunCreated;
use GingerWorkflowEngine\Model\WorkflowRun\Event\WorkflowRunStarted;
use GingerWorkflowEngine\Model\WorkflowRun\Event\WorkflowRunStopped;
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
    public function __construct(WorkflowRunId $workflowRunId)
    {
        //construct is not invoked, when WorkflowRun is reloaded, so we know that this is a new WorkflowRun
        $this->update(new WorkflowRunCreated(array('workflowRunId' => $workflowRunId->toString())));
    }

    /**
     * @return WorkflowRunId
     */
    public function workflowRunId()
    {
        return $this->workflowRunId;
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
