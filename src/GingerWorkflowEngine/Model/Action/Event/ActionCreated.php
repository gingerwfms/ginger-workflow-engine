<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 30.01.14 - 22:19
 */

namespace GingerWorkflowEngine\Model\Action\Event;
use Codeliner\Domain\Shared\DomainEventInterface;
use GingerWorkflowEngine\Model\Action\ActionId;
use GingerWorkflowEngine\Model\Action\Arguments;
use GingerWorkflowEngine\Model\Action\Name;
use GingerWorkflowEngine\Model\Action\Type;
use GingerWorkflowEngine\Model\WorkflowRun\WorkflowRunId;
use Malocher\EventStore\EventSourcing\ObjectChangedEvent;
use Rhumsaa\Uuid\Uuid;

/**
 * Class ActionCreated
 *
 * @package GingerWorkflowEngine\Model\Action\Event
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class ActionCreated extends ObjectChangedEvent implements DomainEventInterface
{
    /**
     * @return ActionId
     */
    public function actionId()
    {
        return new ActionId(Uuid::fromString($this->payload['actionId']));
    }

    /**
     * @return Type
     */
    public function actionType()
    {
        return new Type($this->payload['type']);
    }

    /**
     * @return Name
     */
    public function actionName()
    {
        return new Name($this->payload['name']);
    }

    /**
     * @return Arguments
     */
    public function actionArguments()
    {
        $args = new Arguments(array());

        return $args->fromString($this->payload['arguments']);
    }

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