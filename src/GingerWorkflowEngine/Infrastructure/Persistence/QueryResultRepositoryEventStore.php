<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 05.02.14 - 21:32
 */

namespace GingerWorkflowEngine\Infrastructure\Persistence;

use GingerWorkflowEngine\Model\QueryResult\QueryResult;
use GingerWorkflowEngine\Model\QueryResult\QueryResultId;
use GingerWorkflowEngine\Model\QueryResult\QueryResultRepository;
use Malocher\EventStore\Repository\EventSourcingRepository;

/**
 * Class QueryResultRepositoryEventStore
 *
 * @package GingerWorkflowEngine\Infrastructure\Persistence
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class QueryResultRepositoryEventStore extends EventSourcingRepository implements QueryResultRepository
{

    /**
     * @param QueryResult $aQueryResult
     * @return void
     */
    public function store(QueryResult $aQueryResult)
    {
        $this->save($aQueryResult);
    }

    /**
     * @param QueryResultId $aQueryResultId
     * @return QueryResult
     */
    public function getQueryResultOfId(QueryResultId $aQueryResultId)
    {
        return $this->find($aQueryResultId->toString());
    }
}