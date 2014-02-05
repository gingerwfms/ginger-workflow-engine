<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 03.02.14 - 19:43
 */

namespace GingerWorkflowEngineTest\Model\QueryResult\Event;

use GingerWorkflowEngine\Model\Action\ActionId;
use GingerWorkflowEngine\Model\Action\Arguments;
use GingerWorkflowEngine\Model\Action\Name;
use GingerWorkflowEngine\Model\QueryResult\Event\QueryResultCreated;
use GingerWorkflowEngine\Model\QueryResult\Item;
use GingerWorkflowEngine\Model\QueryResult\MetaInformation;
use GingerWorkflowEngine\Model\QueryResult\QueryResultId;
use GingerWorkflowEngine\Model\QueryResult\Result;
use GingerWorkflowEngine\Model\WorkflowRun\WorkflowRunId;
use GingerWorkflowEngineTest\TestCase;
use Rhumsaa\Uuid\Uuid;

/**
 * Class QueryResultCreatedTest
 *
 * @package GingerWorkflowEngineTest\Model\QueryResult\Event
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class QueryResultCreatedTest extends TestCase
{
    /**
     * @var QueryResultCreated
     */
    private $queryResultCreated;

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

    protected function setUp()
    {
        $this->queryResultId = new QueryResultId(Uuid::uuid4());

        $this->metaInformation = new MetaInformation(
            new WorkflowRunId(Uuid::uuid4()),
            new ActionId(Uuid::uuid4()),
            new Name('TestQuery'),
            new Arguments(array('foo' => 'bar')),
            1
        );

        $this->result = new Item('A result item');

        $this->queryResultCreated = new QueryResultCreated(array(
            'queryResultId' => $this->queryResultId->toString(),
            'metaInformation' => $this->metaInformation->toArray(),
            'result'          => array(
                'resultClass' => get_class($this->result),
                'data'        => $this->result->toJSON()
            )
        ));
    }

    public function testQueryResultId()
    {
        $this->assertTrue($this->queryResultId->sameValueAs($this->queryResultCreated->queryResultId()));
    }

    public function testMetaInformation()
    {
        $this->assertTrue($this->metaInformation->sameValueAs($this->queryResultCreated->metaInformation()));
    }

    public function testResult()
    {
        $this->assertTrue($this->result->sameValueAs($this->queryResultCreated->result()));
    }
} 