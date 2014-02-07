<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 26.01.14 - 21:48
 */

namespace GingerWorkflowEngineTest\Model\WorkflowRun;

use GingerWorkflowEngine\Model\Action\Arguments;
use GingerWorkflowEngine\Model\Action\Name;
use GingerWorkflowEngine\Model\Action\Type;
use GingerCore\Model\Workflow\WorkflowId;
use GingerWorkflowEngine\Model\WorkflowRun\WorkflowRun;
use GingerWorkflowEngine\Model\WorkflowRun\WorkflowRunId;
use GingerWorkflowEngineTest\TestCase;
use Rhumsaa\Uuid\Uuid;

/**
 * Class WorkflowRunTest
 *
 * @package GingerWorkflowEngineTest\Model\WorkflowRun
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class WorkflowRunTest extends TestCase
{
    public function testConstruct()
    {
        $uuid = Uuid::uuid4();
        $workflowRun = new WorkflowRun(new WorkflowRunId($uuid), new WorkflowId('1234'));

        $this->assertEquals($uuid, $workflowRun->workflowRunId()->toString());
        $this->assertEquals('1234', $workflowRun->workflowId()->toString());
    }

    public function testStartAndIsStartedAndThrowsExceptionIfAlreadyStarted()
    {
        $aWorkflowRun = new WorkflowRun(new WorkflowRunId(Uuid::uuid4()), new WorkflowId('1234'));

        $aWorkflowRun->start();

        $this->assertTrue($aWorkflowRun->isStarted());

        $this->setExpectedException('GingerWorkflowEngine\Model\WorkflowRun\Exception\WorkflowRunAlreadyStartedException');

        $aWorkflowRun->start();
    }

    public function testStartedOn()
    {
        $aWorkflowRun = new WorkflowRun(new WorkflowRunId(Uuid::uuid4()), new WorkflowId('1234'));

        $aWorkflowRun->start();

        $this->assertEquals(date('d.m.Y'), $aWorkflowRun->startedOn()->format('d.m.Y'));
    }

    /**
     * @expectedException \GingerWorkflowEngine\Model\WorkflowRun\Exception\WorkflowRunNotStartedException
     */
    public function testStartedOnThrowsExceptionIfNotStarted()
    {
        $aWorkflowRun = new WorkflowRun(new WorkflowRunId(Uuid::uuid4()), new WorkflowId('1234'));

        $aWorkflowRun->startedOn();
    }

    public function testStopAndIsStopped()
    {
        $aWorkflowRun = new WorkflowRun(new WorkflowRunId(Uuid::uuid4()), new WorkflowId('1234'));

        $aWorkflowRun->start();

        $aWorkflowRun->stop();

        $this->assertTrue($aWorkflowRun->isStopped());
    }

    /**
     * @expectedException \GingerWorkflowEngine\Model\WorkflowRun\Exception\WorkflowRunNotStartedException
     */
    public function testStopThrowsExceptionIfNotStarted()
    {
        $aWorkflowRun = new WorkflowRun(new WorkflowRunId(Uuid::uuid4()), new WorkflowId('1234'));

        $aWorkflowRun->stop();
    }

    /**
     * @expectedException \GingerWorkflowEngine\Model\WorkflowRun\Exception\WorkflowRunAlreadyStoppedException
     */
    public function testStopThrowsExceptionIfIsStopped()
    {
        $aWorkflowRun = new WorkflowRun(new WorkflowRunId(Uuid::uuid4()), new WorkflowId('1234'));

        $aWorkflowRun->start();

        $aWorkflowRun->stop();

        $aWorkflowRun->stop();
    }

    public function testStoppedOn()
    {
        $aWorkflowRun = new WorkflowRun(new WorkflowRunId(Uuid::uuid4()), new WorkflowId('1234'));

        $aWorkflowRun->start();

        $aWorkflowRun->stop();

        $this->assertEquals(date('d.m.Y'), $aWorkflowRun->stoppedOn()->format('d.m.Y'));
    }

    /**
     * @expectedException \GingerWorkflowEngine\Model\WorkflowRun\Exception\WorkflowRunNotStoppedException
     */
    public function stoppedOnThrowsExceptionIfNotStopped()
    {
        $aWorkflowRun = new WorkflowRun(new WorkflowRunId(Uuid::uuid4()), new WorkflowId('1234'));

        $aWorkflowRun->start();

        $aWorkflowRun->stoppedOn();
    }

    public function testIsRunning()
    {
        $aWorkflowRun = new WorkflowRun(new WorkflowRunId(Uuid::uuid4()), new WorkflowId('1234'));

        $this->assertFalse($aWorkflowRun->isRunning());

        $aWorkflowRun->start();

        $this->assertTrue($aWorkflowRun->isRunning());

        $aWorkflowRun->stop();

        $this->assertFalse($aWorkflowRun->isRunning());
    }

    public function testCreateAction()
    {
        $aWorkflowRun = new WorkflowRun(new WorkflowRunId(Uuid::uuid4()), new WorkflowId('1234'));

        $aWorkflowRun->start();

        $anAction = $aWorkflowRun->createAction(new Name('Testcommand'), new Type(Type::COMMAND), new Arguments(array()));

        $this->assertTrue($aWorkflowRun->workflowRunId()->sameValueAs($anAction->workflowRunId()));
        $this->assertEquals('Testcommand', $anAction->name()->toString());
        $this->assertTrue($anAction->isCommand());
    }

    /**
     * @expectedException \GingerWorkflowEngine\Model\WorkflowRun\Exception\ActionCreationFailedException
     */
    public function testCreateActionThrowsExceptionIfNotStarted()
    {
        $aWorkflowRun = new WorkflowRun(new WorkflowRunId(Uuid::uuid4()), new WorkflowId('1234'));
        $aWorkflowRun->createAction(new Name('Testcommand'), new Type(Type::COMMAND), new Arguments(array()));

    }

    /**
     * @expectedException \GingerWorkflowEngine\Model\WorkflowRun\Exception\ActionCreationFailedException
     */
    public function testCreateActionThrowsExceptionIfAlreadyStopped()
    {
        $aWorkflowRun = new WorkflowRun(new WorkflowRunId(Uuid::uuid4()), new WorkflowId('1234'));

        $aWorkflowRun->start();

        $aWorkflowRun->stop();

        $aWorkflowRun->createAction(new Name('Testcommand'), new Type(Type::COMMAND), new Arguments(array()));
    }
} 