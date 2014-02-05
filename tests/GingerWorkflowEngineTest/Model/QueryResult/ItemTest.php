<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 01.02.14 - 19:33
 */

namespace GingerWorkflowEngineTest\Model\QueryResult;

use GingerWorkflowEngine\Model\Action\ActionId;
use GingerWorkflowEngine\Model\Action\Arguments;
use GingerWorkflowEngine\Model\Action\Name;
use GingerWorkflowEngine\Model\QueryResult\Item;
use GingerWorkflowEngine\Model\QueryResult\MetaInformation;
use GingerWorkflowEngine\Model\WorkflowRun\WorkflowRunId;
use GingerWorkflowEngineTest\TestCase;
use Rhumsaa\Uuid\Uuid;

/**
 * Class ItemTest
 *
 * @package GingerWorkflowEngineTest\Model\QueryResult
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class ItemTest extends TestCase
{
    public function testIsScalar()
    {
        $boolItem = new Item(true);

        $this->assertTrue($boolItem->isScalar());

        $intItem = new Item(123);

        $this->assertTrue($intItem->isScalar());

        $floatItem = new Item(1.2);

        $this->assertTrue($floatItem->isScalar());

        $stringItem = new Item('a string');

        $this->assertTrue($stringItem->isScalar());

        $arrayItem = new Item(array('an array'));

        $this->assertFalse($arrayItem->isScalar());
    }

    public function testIsBoolean()
    {
        $boolItem = new Item(true);

        $this->assertTrue($boolItem->isBoolean());

        $intItem = new Item(123);

        $this->assertFalse($intItem->isBoolean());

        $floatItem = new Item(1.2);

        $this->assertFalse($floatItem->isBoolean());

        $stringItem = new Item('a string');

        $this->assertFalse($stringItem->isBoolean());

        $arrayItem = new Item(array('an array'));

        $this->assertFalse($arrayItem->isBoolean());
    }

    public function testIsInteger()
    {
        $boolItem = new Item(true);

        $this->assertFalse($boolItem->isInteger());

        $intItem = new Item(123);

        $this->assertTrue($intItem->isInteger());

        $floatItem = new Item(1.2);

        $this->assertFalse($floatItem->isInteger());

        $stringItem = new Item('a string');

        $this->assertFalse($stringItem->isInteger());

        $arrayItem = new Item(array('an array'));

        $this->assertFalse($arrayItem->isInteger());
    }

    public function testIsFloat()
    {
        $boolItem = new Item(true);

        $this->assertFalse($boolItem->isFloat());

        $intItem = new Item(123);

        $this->assertFalse($intItem->isFloat());

        $floatItem = new Item(1.2);

        $this->assertTrue($floatItem->isFloat());

        $stringItem = new Item('a string');

        $this->assertFalse($stringItem->isFloat());

        $arrayItem = new Item(array('an array'));

        $this->assertFalse($arrayItem->isFloat());
    }

    public function testIsString()
    {
        $boolItem = new Item(true);

        $this->assertFalse($boolItem->isString());

        $intItem = new Item(123);

        $this->assertFalse($intItem->isString());

        $floatItem = new Item(1.2);

        $this->assertFalse($floatItem->isString());

        $stringItem = new Item('a string');

        $this->assertTrue($stringItem->isString());

        $arrayItem = new Item(array('an array'));

        $this->assertFalse($arrayItem->isString());
    }

    public function testIsArray()
    {
        $boolItem = new Item(true);

        $this->assertFalse($boolItem->isArray());

        $intItem = new Item(123);

        $this->assertFalse($intItem->isArray());

        $floatItem = new Item(1.2);

        $this->assertFalse($floatItem->isArray());

        $stringItem = new Item('a string');

        $this->assertFalse($stringItem->isArray());

        $arrayItem = new Item(array('an array'));

        $this->assertTrue($arrayItem->isArray());
    }

    public function testIsList()
    {
        $boolItem = new Item(true);

        $this->assertFalse($boolItem->isList());

        $intItem = new Item(123);

        $this->assertFalse($intItem->isList());

        $floatItem = new Item(1.2);

        $this->assertFalse($floatItem->isList());

        $stringItem = new Item('a string');

        $this->assertFalse($stringItem->isList());

        $listItem = new Item(array('this', 'is', 'a', 'list'));

        $this->assertTrue($listItem->isList());

        $hashTableItem = new Item(array('this' => 'is', 'a' => 'HashTable'));

        $this->assertFalse($hashTableItem->isList());
    }

    public function testIsHashTable()
    {
        $boolItem = new Item(true);

        $this->assertFalse($boolItem->isHashTable());

        $intItem = new Item(123);

        $this->assertFalse($intItem->isHashTable());

        $floatItem = new Item(1.2);

        $this->assertFalse($floatItem->isHashTable());

        $stringItem = new Item('a string');

        $this->assertFalse($stringItem->isHashTable());

        $listItem = new Item(array('this', 'is', 'a', 'list'));

        $this->assertFalse($listItem->isHashTable());

        $hashTableItem = new Item(array('this' => 'is', 'a' => 'HashTable'));

        $this->assertTrue($hashTableItem->isHashTable());
    }

    public function testToAndFromJSON()
    {
        $listItem = new Item(array('array', 'to', 'json'));

        $jsonString = $listItem->toJSON();

        $this->assertJson($jsonString);

        $listItemFromJSON = Item::fromJSON($jsonString);

        $this->assertTrue($listItem->sameValueAs($listItemFromJSON));
    }

    public function testSameValueAs()
    {
        $boolItem     = new Item(true);
        $sameBoolItem = new Item(true);
        $integerItem  = new Item(1);

        $this->assertTrue($boolItem->sameValueAs($sameBoolItem));
        $this->assertFalse($boolItem->sameValueAs($integerItem));
    }

    public function testItems()
    {
        $integerItem = new Item(1234);

        $aMetaInformation = new MetaInformation(
            new WorkflowRunId(Uuid::uuid4()),
            new ActionId(Uuid::uuid4()),
            new Name('TestQuery'),
            new Arguments(array()),
            1
        );

        $this->assertEquals(array($integerItem), $integerItem->items(0, 1, $aMetaInformation));
    }

    public function testIsSingleItemResult()
    {
        $integerItem = new Item(1234);

        $this->assertTrue($integerItem->isSingleItemResult());
    }

    public function testIsFullItemCollection()
    {
        $integerItem = new Item(1234);

        $this->assertTrue($integerItem->isFullItemCollection());
    }

    public function testIsPagedItemCollection()
    {
        $integerItem = new Item(1234);

        $this->assertFalse($integerItem->isPagedItemCollection());
    }
} 