<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 30.01.14 - 20:05
 */

namespace GingerWorkflowEngine\Model\Action;

use Assert\Assertion;
use Codeliner\Domain\Shared\EntityInterface;
use GingerWorkflowEngine\Model\Action\Event\ActionCreated;
use GingerWorkflowEngine\Model\WorkflowRun\WorkflowRunId;
use Malocher\EventStore\EventSourcing\EventSourcedObject;
use Rhumsaa\Uuid\Uuid;

/**
 * Class Action
 *
 * Action is the Aggregate Root of the Action-ActionId-Type-Arguments-WorkflowRunId-Aggregate.
 * The Type of the Action can either be Command or Query.
 * In both cases the Action has an ActionId, a specific name and contains a set of Arguments
 * besides a link to the associated WorkflowRun via a WorkflowRunId.
 * The Action is delivered to a Role that executes it and returns a Result (in case of a Query)
 * or publish Events (in case of Command).The Result or the Events are captured and assigned
 * to the associated WorkflowRun.
 *
 * @package GingerWorkflowEngine\Model\Action
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class Action extends EventSourcedObject implements EntityInterface
{
    /**
     * @var ActionId
     */
    private $actionId;

    /**
     * @var Type
     */
    private $type;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Arguments
     */
    private $arguments;

    /**
     * @var WorkflowRunId
     */
    private $workflowRunId;

    /**
     * @param ActionId      $anActionId
     * @param Type          $aType
     * @param string        $name
     * @param Arguments     $anArguments
     * @param WorkflowRunId $aWorkflowRunId
     */
    public function __construct(
        ActionId $anActionId,
        Type $aType,
        $name,
        Arguments $anArguments,
        WorkflowRunId $aWorkflowRunId)
    {
        Assertion::string($name, "Action.name must be string");
        Assertion::minLength($name, 5, "Action.name must at least be 5 chars long");

        $anActionCreated = new ActionCreated(
            array(
                'actionId'      => $anActionId->toString(),
                'type'          => $aType->toString(),
                'name'          => $name,
                'arguments'     => $anArguments->toString(),
                'workflowRunId' => $aWorkflowRunId->toString()
            )
        );

        $this->update($anActionCreated);
    }

    /**
     * @return ActionId
     */
    public function actionId()
    {
        return $this->actionId;
    }

    /**
     * @return bool
     */
    public function isQuery()
    {
        return $this->type->sameValueAs(new Type(Type::QUERY));
    }

    /**
     * @return bool
     */
    public function isCommand()
    {
        return $this->type->sameValueAs(new Type(Type::COMMAND));
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return Arguments
     */
    public function arguments()
    {
        return $this->arguments;
    }

    /**
     * @return WorkflowRunId
     */
    public function workflowRunId()
    {
        return $this->workflowRunId;
    }

    /**
     * @param EntityInterface $other
     * @return boolean
     */
    public function sameIdentityAs(EntityInterface $other)
    {
        if (!$other instanceof Action) {
            return false;
        }

        return $this->actionId()->sameValueAs($other->actionId());
    }

    /**
     * @return void
     */
    protected function registerHandlers()
    {
        $this->handlers['ActionCreated'] = 'onActionCreated';
    }

    /**
     * @param ActionCreated $event
     */
    protected function onActionCreated(ActionCreated $event)
    {
        $this->actionId      = $event->actionId();
        $this->type          = $event->actionType();
        $this->name          = $event->actionName();
        $this->arguments     = $event->actionArguments();
        $this->workflowRunId = $event->workflowRunId();
    }

    /**
     * Required by EventStore
     *
     * @return string
     */
    protected function getId()
    {
        return $this->actionId()->toString();
    }

    /**
     * Required by EventStore
     *
     * @param string $id
     */
    protected function setId($id)
    {
        $this->actionId = new ActionId(Uuid::fromString($id));
    }
}