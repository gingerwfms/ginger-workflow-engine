<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 31.01.14 - 17:28
 */

namespace GingerWorkflowEngine\Model\Action;

/**
 * Interface ActionRepository
 *
 * @package GingerWorkflowEngine\Model\Action
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
interface ActionRepository 
{
    /**
     * @param Action $anAction
     * @return void
     */
    public function store(Action $anAction);

    /**
     * @param ActionId $anActionId
     * @return Action|null
     */
    public function getFromActionId(ActionId $anActionId);
} 