<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 01.02.14 - 22:32
 */

namespace GingerWorkflowEngine\Model\QueryResult;

use Codeliner\Domain\Shared\EntityInterface;
use GingerWorkflowEngine\Model\QueryResult\Event\QueryResultCreated;
use Malocher\EventStore\EventSourcing\EventSourcedObject;
use Rhumsaa\Uuid\Uuid;

/**
 * Class QueryResult
 *
 * QueryResult is the Aggregate Root of the QueryResult-MetaInformation-Result-Aggregate.
 * It represents a result of an Action of type Query that was processed by a Role.
 *
 * @package GingerWorkflowEngine\Model\QueryResult
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class QueryResult extends EventSourcedObject implements EntityInterface
{
    /**
     * @var QueryResultId
     */
    private $queryResultId;

    /**
     * @var MetaInformation
     */
    private $metaInformation;

    /**
     * @var Result
     */
    private $result;

    /**
     * @param QueryResultId   $aQueryResultId
     * @param MetaInformation $aMetaInformation
     * @param Result          $aResult
     */
    public function __construct(
        QueryResultId $aQueryResultId,
        MetaInformation $aMetaInformation,
        Result $aResult)
    {
        $this->update(new QueryResultCreated(array(
            'queryResultId' => $aQueryResultId->toString(),
            'metaInformation' => $aMetaInformation->toArray(),
            'result' => array(
                'resultClass' => get_class($aResult),
                'data'        => $aResult->toJSON()
            )
        )));
    }

    /**
     * @return QueryResultId
     */
    public function queryResultId()
    {
        return $this->queryResultId;
    }

    /**
     * @return MetaInformation
     */
    public function metaInformation()
    {
        return $this->metaInformation;
    }

    /**
     * @return Result
     */
    public function result()
    {
        return $this->result;
    }

    /**
     * @param EntityInterface $other
     * @return boolean
     */
    public function sameIdentityAs(EntityInterface $other)
    {
        if (!$other instanceof QueryResult) {
            return false;
        }

        return $this->queryResultId()->sameValueAs($other->queryResultId());
    }

    /**
     * Hookpoint to register event handlers
     *
     * @throws \Malocher\EventStore\EventSourcing\EventSourcingException
     */
    protected function registerHandlers()
    {
        $this->handlers['QueryResultCreated'] = 'onQueryResultCreated';
    }

    /**
     * @param QueryResultCreated $event
     */
    protected function onQueryResultCreated(QueryResultCreated $event)
    {
        $this->queryResultId   = $event->queryResultId();
        $this->metaInformation = $event->metaInformation();
        $this->result          = $event->result();
    }

    /**
     * Required by EventStore
     *
     * @return string
     */
    protected function getId()
    {
        return $this->queryResultId()->toString();
    }

    /**
     * Required by EventStore
     *
     * @param string $id
     */
    protected function setId($id)
    {
        $this->queryResultId = new QueryResultId(Uuid::fromString($id));
    }
}