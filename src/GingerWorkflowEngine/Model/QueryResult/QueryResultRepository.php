<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 05.02.14 - 21:28
 */

namespace GingerWorkflowEngine\Model\QueryResult;

/**
 * Interface QueryResultRepository
 *
 * @package GingerWorkflowEngine\Model\QueryResult
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
interface QueryResultRepository
{
    /**
     * @param QueryResult $aQueryResult
     * @return void
     */
    public function store(QueryResult $aQueryResult);

    /**
     * @param QueryResultId $aQueryResultId
     * @return QueryResult
     */
    public function getQueryResultOfId(QueryResultId $aQueryResultId);
} 