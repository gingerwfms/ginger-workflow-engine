<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 31.01.14 - 17:31
 */

namespace GingerWorkflowEngine\Infrastructure\Persistence;

use GingerWorkflowEngine\Model\Action\Action;
use GingerWorkflowEngine\Model\Action\ActionId;
use GingerWorkflowEngine\Model\Action\ActionRepository;
use Malocher\EventStore\Repository\EventSourcingRepository;

/**
 * Class ActionRepositoryEventStore
 *
 * @package GingerWorkflowEngine\Infrastructure\Persistence
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class ActionRepositoryEventStore extends EventSourcingRepository implements ActionRepository
{

    /**
     * @param Action $anAction
     * @return void
     */
    public function store(Action $anAction)
    {
        $this->save($anAction);
    }

    /**
     * @param ActionId $anActionId
     * @return Action|null
     */
    public function getActionOfId(ActionId $anActionId)
    {
        return $this->find($anActionId->toString());
    }
}