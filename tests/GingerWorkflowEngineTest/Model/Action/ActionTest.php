<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 31.01.14 - 17:10
 */

namespace GingerWorkflowEngineTest\Model\Action;
use GingerWorkflowEngine\Model\Action\Action;
use GingerWorkflowEngine\Model\Action\ActionId;
use GingerWorkflowEngine\Model\Action\Arguments;
use GingerWorkflowEngine\Model\Action\Type;
use GingerWorkflowEngine\Model\WorkflowRun\WorkflowRunId;
use GingerWorkflowEngineTest\TestCase;
use Rhumsaa\Uuid\Uuid;

/**
 * Class ActionTest
 *
 * @package GingerWorkflowEngineTest\Model\Action
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class ActionTest extends TestCase
{
    /**
     * @var Action
     */
    private $command;

    /**
     * @var Action
     */
    private $query;

    /**
     * @var Uuid
     */
    private $uuid;

    /**
     * @var WorkflowRunId
     */
    private $workflowRunId;

    protected function setUp()
    {
        $this->uuid = Uuid::uuid4();

        $anActionId = new ActionId($this->uuid);

        $anArguments = new Arguments(array('foo' => 'bar'));

        $this->workflowRunId = new WorkflowRunId(Uuid::uuid4());

        $this->command = new Action(
            $anActionId,
            new Type(Type::COMMAND),
            'Testcommand',
            $anArguments,
            $this->workflowRunId
        );

        $this->query = new Action(
            new ActionId(Uuid::uuid4()),
            new Type(Type::QUERY),
            'Testquery',
            $anArguments,
            $this->workflowRunId
        );
    }

    public function testActionId()
    {
        $this->assertEquals($this->uuid->toString(), $this->command->actionId()->toString());
    }

    public function testIsQuery()
    {
        $this->assertFalse($this->command->isQuery());
        $this->assertTrue($this->query->isQuery());

    }

    public function testIsCommand()
    {
        $this->assertTrue($this->command->isCommand());
        $this->assertFalse($this->query->isCommand());
    }

    public function testName()
    {
        $this->assertEquals('Testcommand', $this->command->name());
    }

    public function testArguments()
    {
        $this->assertEquals(array('foo' => 'bar'), $this->command->arguments()->toArray());
    }

    public function testWorkflowRunId()
    {
        $this->assertTrue($this->workflowRunId->sameValueAs($this->command->workflowRunId()));
    }

    public function testSameIdentityAs()
    {
        $sameAction = new Action(
            new ActionId($this->uuid),
            new Type(Type::COMMAND),
            'Testcommand',
            new Arguments(array('foo' => 'bar')),
            $this->workflowRunId
        );

        $this->assertTrue($this->command->sameIdentityAs($sameAction));
        $this->assertFalse($this->command->sameIdentityAs($this->query));
    }
} 