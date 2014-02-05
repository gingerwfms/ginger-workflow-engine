<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 05.02.14 - 21:21
 */

namespace GingerWorkflowEngineTest\Model\QueryResult;

use GingerWorkflowEngine\Model\Action\ActionId;
use GingerWorkflowEngine\Model\Action\Arguments;
use GingerWorkflowEngine\Model\Action\Name;
use GingerWorkflowEngine\Model\QueryResult\Item;
use GingerWorkflowEngine\Model\QueryResult\MetaInformation;
use GingerWorkflowEngine\Model\QueryResult\QueryResult;
use GingerWorkflowEngine\Model\QueryResult\QueryResultId;
use GingerWorkflowEngine\Model\WorkflowRun\WorkflowRunId;
use GingerWorkflowEngineTest\TestCase;
use Rhumsaa\Uuid\Uuid;

/**
 * Class QueryResultTest
 *
 * @package GingerWorkflowEngineTest\Model\QueryResult
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class QueryResultTest extends TestCase
{
    private $queryResult;

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

        $this->queryResult = new QueryResult(
            $this->queryResultId,
            $this->metaInformation,
            $this->result
        );
    }

    public function testQueryResultId()
    {
        $this->assertTrue($this->queryResultId->sameValueAs($this->queryResult->queryResultId()));
    }

    public function testMetaInformation()
    {
        $this->assertTrue($this->metaInformation->sameValueAs($this->queryResult->metaInformation()));
    }

    public function testResult()
    {
        $this->assertTrue($this->result->sameValueAs($this->queryResult->result()));
    }
} 