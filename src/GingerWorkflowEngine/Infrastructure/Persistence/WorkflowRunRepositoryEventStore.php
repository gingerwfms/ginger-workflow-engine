<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 26.01.14 - 21:59
 */

namespace GingerWorkflowEngine\Infrastructure\Persistence;

use GingerWorkflowEngine\Model\WorkflowRun\WorkflowRun;
use GingerWorkflowEngine\Model\WorkflowRun\WorkflowRunId;
use GingerWorkflowEngine\Model\WorkflowRun\WorkflowRunRepository;
use Malocher\EventStore\Repository\EventSourcingRepository;

/**
 * Class WorkflowRunRepositoryEventStore
 *
 * @package GingerWorkflowEngine\Infrastructure\Persistence
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class WorkflowRunRepositoryEventStore extends EventSourcingRepository implements WorkflowRunRepository
{
    /**
     * @param WorkflowRun $aWorkflowRun
     * @return void
     */
    public function store(WorkflowRun $aWorkflowRun)
    {
        $this->save($aWorkflowRun);
    }

    /**
     * @param WorkflowRunId $aWorkflowRunId
     * @return WorkflowRun|null
     */
    public function getFromWorkflowRunId(WorkflowRunId $aWorkflowRunId)
    {
        return $this->find($aWorkflowRunId->toString());
    }
}