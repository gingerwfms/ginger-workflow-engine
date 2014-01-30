<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 26.01.14 - 21:29
 */

namespace GingerWorkflowEngineTest\Model\WorkflowRun;

use GingerWorkflowEngine\Model\WorkflowRun\WorkflowRunId;
use GingerWorkflowEngineTest\TestCase;
use Rhumsaa\Uuid\Uuid;

/**
 * Class WorkflowRunIdTest
 *
 * @package GingerWorkflowEngineTest\Model\WorkflowRun
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class WorkflowRunIdTest extends TestCase
{
    public function testSameValueAs()
    {
        $aUUID = Uuid::uuid4();
        $anotherUUID = Uuid::uuid4();
        $aWorkflowRunId = new WorkflowRunId($aUUID);
        $equalWorkflowRunId = new WorkflowRunId($aUUID);
        $anotherWorkflowRunId = new WorkflowRunId($anotherUUID);

        $this->assertTrue($aWorkflowRunId->sameValueAs($equalWorkflowRunId));
        $this->assertFalse($aWorkflowRunId->sameValueAs($anotherWorkflowRunId));
    }
} 