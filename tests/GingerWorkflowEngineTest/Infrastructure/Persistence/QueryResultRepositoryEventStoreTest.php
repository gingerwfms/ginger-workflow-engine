<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 05.02.14 - 21:34
 */

namespace GingerWorkflowEngineTest\Infrastructure\Persistence;

use GingerWorkflowEngine\Infrastructure\Persistence\QueryResultRepositoryEventStore;
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
 * Class QueryResultRepositoryEventStoreTest
 *
 * @package GingerWorkflowEngineTest\Infrastructure\Persistence
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class QueryResultRepositoryEventStoreTest extends TestCase
{
    /**
     * @var QueryResultRepositoryEventStore
     */
    private $queryResultRepository;

    protected function setUp()
    {
        $this->createSchema(array('GingerWorkflowEngine\Model\QueryResult\QueryResult'));

        $this->queryResultRepository = $this->getTestEventStore()->getRepository('GingerWorkflowEngine\Model\QueryResult\QueryResult');
    }

    public function testStoreAndGetQueryResultOfId()
    {
        $queryResultId = new QueryResultId(Uuid::uuid4());

        $metaInformation = new MetaInformation(
            new WorkflowRunId(Uuid::uuid4()),
            new ActionId(Uuid::uuid4()),
            new Name('TestQuery'),
            new Arguments(array('foo' => 'bar')),
            1
        );

        $result = new Item('A result item');

        $queryResult = new QueryResult(
            $queryResultId,
            $metaInformation,
            $result
        );

        $this->queryResultRepository->store($queryResult);

        $this->getTestEventStore()->clear();

        $sameQueryResult = $this->queryResultRepository->getQueryResultOfId($queryResultId);

        //Both QueryResults should have the same QueryResultId, but should have other references
        $this->assertNotSame($sameQueryResult, $queryResult);

        $this->assertTrue($sameQueryResult->sameIdentityAs($queryResult));
    }
} 