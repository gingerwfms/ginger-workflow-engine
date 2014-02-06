<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 26.01.14 - 21:44
 */

namespace GingerWorkflowEngineTest\Model\WorkflowRun\Event;
use GingerWorkflowEngine\Model\WorkflowRun\Event\WorkflowRunCreated;
use GingerWorkflowEngineTest\TestCase;
use Rhumsaa\Uuid\Uuid;

/**
 * Class WorkflowRunCreatedTest
 *
 * @package GingerWorkflowEngineTest\Model\WorkflowRun\Event
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class WorkflowRunCreatedTest extends TestCase
{
    public function testWorkflowRunId()
    {
        $uuid = Uuid::uuid4();
        $event = new WorkflowRunCreated(
            array(
                'workflowRunId' => $uuid->toString(),
                'workflowId'    => '1234'
            )
        );

        $workflowRunId = $event->workflowRunId();

        $this->assertEquals($uuid->toString(), $workflowRunId->toString());
    }

    public function testWorkflowId()
    {
        $uuid = Uuid::uuid4();
        $event = new WorkflowRunCreated(
            array(
                'workflowRunId' => $uuid->toString(),
                'workflowId'    => '1234'
            )
        );

        $this->assertEquals('1234', $event->workflowId()->toString());
    }
} 