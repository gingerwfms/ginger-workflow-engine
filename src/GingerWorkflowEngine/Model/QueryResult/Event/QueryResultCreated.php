<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 01.02.14 - 23:07
 */

namespace GingerWorkflowEngine\Model\QueryResult\Event;

use Codeliner\Domain\Shared\DomainEventInterface;
use GingerWorkflowEngine\Model\QueryResult\MetaInformation;
use GingerWorkflowEngine\Model\QueryResult\QueryResultId;
use GingerWorkflowEngine\Model\QueryResult\Result;
use Malocher\EventStore\EventSourcing\ObjectChangedEvent;
use Rhumsaa\Uuid\Uuid;

/**
 * Class QueryResultCreated
 *
 * @package GingerWorkflowEngine\Model\QueryResult\Event
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class QueryResultCreated extends ObjectChangedEvent implements DomainEventInterface
{
    /**
     * @return QueryResultId
     */
    public function queryResultId()
    {
        return new QueryResultId(Uuid::fromString($this->payload['queryResultId']));
    }

    /**
     * @return MetaInformation
     */
    public function metaInformation()
    {
        return MetaInformation::fromArray($this->payload['metaInformation']);
    }

    /**
     * @return Result
     */
    public function result()
    {
        $resultClass = $this->payload['result']['resultClass'];
        return $resultClass::fromJSON($this->payload['result']['data']);
    }

    /**
     * @return \DateTime
     */
    public function occurredOn()
    {
        $dt = new \DateTime();
        $dt->setTimestamp($this->getTimestamp());
        return $dt;
    }
}