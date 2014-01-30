<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 26.01.14 - 17:55
 */

namespace GingerWorkflowEngine\Model\WorkflowRun;

/**
 * Interface WorkflowRunRepository
 *
 * @package GingerWorkflowEngine\Model\WorkflowRun
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
interface WorkflowRunRepository
{
    /**
     * @param WorkflowRun $aWorkflowRun
     * @return void
     */
    public function store(WorkflowRun $aWorkflowRun);

    /**
     * @param WorkflowRunId $aWorkflowRunId
     * @return WorkflowRun|null
     */
    public function getFromWorkflowRunId(WorkflowRunId $aWorkflowRunId);
} 