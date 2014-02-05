<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 01.02.14 - 17:35
 */

namespace GingerWorkflowEngineTest\Model\QueryResult;

use GingerWorkflowEngine\Model\Action\ActionId;
use GingerWorkflowEngine\Model\Action\Arguments;
use GingerWorkflowEngine\Model\Action\Name;
use GingerWorkflowEngine\Model\QueryResult\MetaInformation;
use GingerWorkflowEngine\Model\WorkflowRun\WorkflowRunId;
use GingerWorkflowEngineTest\TestCase;
use Rhumsaa\Uuid\Uuid;

/**
 * Class MetaInformationTest
 *
 * @package GingerWorkflowEngineTest\Model\QueryResult
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class MetaInformationTest extends TestCase
{
    /**
     * @var MetaInformation
     */
    private $metaInformation;

    protected function setUp()
    {
        $this->metaInformation = new MetaInformation(
            new WorkflowRunId(Uuid::uuid4()),
            new ActionId(Uuid::uuid4()),
            new Name('TestQuery'),
            new Arguments(array('foo' => 'bar')),
            5
        );
    }

    public function testWorkflowRunId()
    {
        $this->assertInstanceOf('GingerWorkflowEngine\Model\WorkflowRun\WorkflowRunId', $this->metaInformation->workflowRunId());
    }

    public function testActionId()
    {
        $this->assertInstanceOf('GingerWorkflowEngine\Model\Action\ActionId', $this->metaInformation->actionId());
    }

    public function testActionName()
    {
        $this->assertEquals('TestQuery', $this->metaInformation->actionName()->toString());
    }

    public function testActionArguments()
    {
        $this->assertEquals(array('foo' => 'bar'), $this->metaInformation->actionArguments()->toArray());
    }

    public function testResultSetCount()
    {
        $this->assertEquals(5, $this->metaInformation->resultSetCount());
    }

    public function testSameValueAs()
    {
        $sameMetaInformation = new MetaInformation(
            $this->metaInformation->workflowRunId(),
            $this->metaInformation->actionId(),
            new Name('TestQuery'),
            new Arguments(array('foo' => 'bar')),
            5
        );

        $otherMetaInformation = new MetaInformation(
            new WorkflowRunId(Uuid::uuid4()),
            new ActionId(Uuid::uuid4()),
            new Name('AnotherOuery'),
            new Arguments(array('foo' => 'bar')),
            10
        );

        $this->assertTrue($this->metaInformation->sameValueAs($sameMetaInformation));
        $this->assertFalse($this->metaInformation->sameValueAs($otherMetaInformation));
    }

    public function testToAndFromArray()
    {
        $aMetaInformationArray = $this->metaInformation->toArray();

        $newMetaInformation = MetaInformation::fromArray($aMetaInformationArray);

        $this->assertTrue($this->metaInformation->sameValueAs($newMetaInformation));
    }
}