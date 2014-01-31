<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 30.01.14 - 21:48
 */

namespace GingerWorkflowEngineTest\Model\Action;
use GingerWorkflowEngine\Model\Action\ActionId;
use GingerWorkflowEngineTest\TestCase;
use Rhumsaa\Uuid\Uuid;

/**
 * Class ActionIdTest
 *
 * @package GingerWorkflowEngineTest\Model\Action
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class ActionIdTest extends TestCase
{
    public function testToString()
    {
        $anUuid = Uuid::uuid4();

        $anActionId = new ActionId($anUuid);

        $this->assertEquals($anUuid->toString(), $anActionId->toString());
    }

    public function testSameValueAs()
    {
        $anUuid = Uuid::uuid4();

        $anActionId    = new ActionId($anUuid);
        $sameActionId  = new ActionId($anUuid);
        $otherActionId = new ActionId(Uuid::uuid4());

        $this->assertTrue($anActionId->sameValueAs($sameActionId));
        $this->assertFalse($anActionId->sameValueAs($otherActionId));
    }
} 