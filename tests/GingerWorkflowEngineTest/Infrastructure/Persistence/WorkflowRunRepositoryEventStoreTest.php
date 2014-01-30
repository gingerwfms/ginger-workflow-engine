<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 26.01.14 - 22:02
 */

namespace GingerWorkflowEngineTest\Infrastructure\Persistence;
use GingerWorkflowEngine\Infrastructure\Persistence\WorkflowRunRepositoryEventStore;
use GingerWorkflowEngine\Model\WorkflowRun\WorkflowRun;
use GingerWorkflowEngine\Model\WorkflowRun\WorkflowRunId;
use GingerWorkflowEngineTest\TestCase;
use Rhumsaa\Uuid\Uuid;

/**
 * Class WorkflowRunRepositoryEventStoreTest
 *
 * @package GingerWorkflowEngineTest\Infrastructure\Persistence
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class WorkflowRunRepositoryEventStoreTest extends TestCase
{
    /**
     * @var WorkflowRunRepositoryEventStore
     */
    protected $workflowRunRepository;

    protected function setUp()
    {
        $this->createSchema(array('GingerWorkflowEngine\Model\WorkflowRun\WorkflowRun'));

        $this->workflowRunRepository = $this->getTestEventStore()->getRepository('GingerWorkflowEngine\Model\WorkflowRun\WorkflowRun');
    }

    public function testStoreAndGetFromWorkflowRunId()
    {
        $aWorkflowRun = new WorkflowRun(new WorkflowRunId(Uuid::uuid4()));

        $this->workflowRunRepository->store($aWorkflowRun);

        $this->getTestEventStore()->clear();

        $persistedWorkflowRun = $this->workflowRunRepository->getFromWorkflowRunId($aWorkflowRun->workflowRunId());

        $this->assertNotSame($persistedWorkflowRun, $aWorkflowRun);
        $this->assertTrue($aWorkflowRun->sameIdentityAs($persistedWorkflowRun));
    }
} 