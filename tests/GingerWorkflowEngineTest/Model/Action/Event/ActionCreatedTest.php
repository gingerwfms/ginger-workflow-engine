<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 30.01.14 - 22:27
 */

namespace GingerWorkflowEngineTest\Model\Action\Event;

use GingerWorkflowEngine\Model\Action\ActionId;
use GingerWorkflowEngine\Model\Action\Event\ActionCreated;
use GingerWorkflowEngine\Model\Action\Type;
use GingerWorkflowEngine\Model\WorkflowRun\WorkflowRunId;
use GingerWorkflowEngineTest\TestCase;
use Rhumsaa\Uuid\Uuid;

/**
 * Class ActionCreatedTest
 *
 * @package GingerWorkflowEngineTest\Model\Action\Event
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class ActionCreatedTest extends TestCase
{
    /**
     * @var ActionCreated
     */
    private $actionCreated;

    protected function setUp()
    {
        $this->actionCreated = new ActionCreated(
            array(
                'actionId'      => Uuid::uuid4()->toString(),
                'type'          => Type::COMMAND,
                'name'          => 'ATestCommand',
                'arguments'     => json_encode(array('foo' => 'bar')),
                'workflowRunId' => Uuid::uuid4()->toString(),
            )
        );
    }

    public function testActionId()
    {
        $this->assertTrue($this->actionCreated->actionId() instanceof ActionId);
    }

    public function testActionType()
    {
        $this->assertEquals(Type::COMMAND, $this->actionCreated->actionType()->toString());
    }

    public function testActionName()
    {
        $this->assertEquals('ATestCommand', $this->actionCreated->actionName()->toString());
    }

    public function testActionArguments()
    {
        $this->assertEquals(array('foo' => 'bar'), $this->actionCreated->actionArguments()->toArray());
    }

    public function testWorkflowRunId()
    {
        $this->assertTrue($this->actionCreated->workflowRunId() instanceof WorkflowRunId);
    }
} 