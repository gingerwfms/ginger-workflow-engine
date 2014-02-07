<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 26.01.14 - 21:38
 */

namespace GingerWorkflowEngine\Model\WorkflowRun\Event;

use Codeliner\Domain\Shared\DomainEventInterface;
use GingerCore\Model\Workflow\WorkflowId;
use GingerWorkflowEngine\Model\WorkflowRun\WorkflowRunId;
use Malocher\EventStore\EventSourcing\ObjectChangedEvent;
use Rhumsaa\Uuid\Uuid;

/**
 * Class WorkflowRunCreated
 *
 * @package GingerWorkflowEngine\Model\WorkflowRun\Event
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class WorkflowRunCreated extends ObjectChangedEvent implements DomainEventInterface
{
    /**
     * @return WorkflowRunId
     */
    public function workflowRunId()
    {
        return new WorkflowRunId(Uuid::fromString($this->payload['workflowRunId']));
    }

    /**
     * @return WorkflowId
     */
    public function workflowId()
    {
        return new WorkflowId($this->payload['workflowId']);
    }
    /**
     * @return \DateTime
     */
    public function occurredOn()
    {
        $dt = new \DateTime();
        $dt->setTimestamp($this->getTimestamp());
        return $dt;
    }
}