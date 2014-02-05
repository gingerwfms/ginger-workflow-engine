<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 01.02.14 - 22:41
 */

namespace GingerWorkflowEngineTest\Model\QueryResult;

use GingerWorkflowEngine\Model\QueryResult\QueryResultId;
use GingerWorkflowEngineTest\TestCase;
use Rhumsaa\Uuid\Uuid;

/**
 * Class QueryResultIdTest
 *
 * @package GingerWorkflowEngineTest\Model\QueryResult
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class QueryResultIdTest extends TestCase
{
    public function testToString()
    {
        $anUuid = Uuid::uuid4();

        $aQueryResultId = new QueryResultId($anUuid);

        $this->assertEquals($anUuid->toString(), $aQueryResultId->toString());
    }

    public function testSameValueAs()
    {
        $anUuid = Uuid::uuid4();

        $aQueryResultId     = new QueryResultId($anUuid);
        $sameQueryResultId  = new QueryResultId($anUuid);
        $otherQueryResultId = new QueryResultId(Uuid::uuid4());

        $this->assertTrue($aQueryResultId->sameValueAs($sameQueryResultId));
        $this->assertFalse($aQueryResultId->sameValueAs($otherQueryResultId));
    }
} 