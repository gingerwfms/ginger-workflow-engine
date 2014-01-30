<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 29.01.14 - 21:22
 */

namespace GingerWorkflowEngine\Model\WorkflowRun\Event;

use Codeliner\Domain\Shared\DomainEventInterface;
use Malocher\EventStore\EventSourcing\ObjectChangedEvent;

/**
 * Class WorkflowRunStopped
 *
 * @package GingerWorkflowEngine\Model\WorkflowRun\Event
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class WorkflowRunStopped extends ObjectChangedEvent implements DomainEventInterface
{
    /**
     * @return WorkflowRunId
     */
    public function workflowRunId()
    {
        return new WorkflowRunId(Uuid::fromString($this->payload['workflowRunId']));
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