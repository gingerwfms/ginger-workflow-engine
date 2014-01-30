<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 29.01.14 - 21:08
 */

namespace GingerWorkflowEngine\Model\WorkflowRun\Exception;

class WorkflowRunAlreadyStoppedException extends \RuntimeException implements WorkflowRunException
{
} 