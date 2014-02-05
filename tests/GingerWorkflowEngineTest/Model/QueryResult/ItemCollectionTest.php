<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 01.02.14 - 20:43
 */

namespace GingerWorkflowEngineTest\Model\QueryResult;

use GingerWorkflowEngine\Model\Action\ActionId;
use GingerWorkflowEngine\Model\Action\Arguments;
use GingerWorkflowEngine\Model\Action\Name;
use GingerWorkflowEngine\Model\QueryResult\Item;
use GingerWorkflowEngine\Model\QueryResult\ItemCollection;
use GingerWorkflowEngine\Model\QueryResult\MetaInformation;
use GingerWorkflowEngine\Model\WorkflowRun\WorkflowRunId;
use GingerWorkflowEngineTest\TestCase;
use Rhumsaa\Uuid\Uuid;

/**
 * Class ItemCollectionTest
 *
 * @package GingerWorkflowEngine\Model\QueryResult
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class ItemCollectionTest extends TestCase
{
    public function testHas()
    {
        $anItem = new Item('some data');

        $anotherItem = new Item(234);

        $anItemCollection = new ItemCollection(array($anItem, $anotherItem));

        $this->assertTrue($anItemCollection->has($anItem));

        $this->assertFalse($anItemCollection->has(new Item(true)));
    }

    public function testToAndFromJSON()
    {
        $anItem = new Item('some data');

        $anotherItem = new Item(234);

        $anItemCollection = new ItemCollection(array($anItem, $anotherItem));

        $aJSONString = $anItemCollection->toJSON();

        $sameItemCollection = ItemCollection::fromJSON($aJSONString);

        $this->assertTrue($anItemCollection->sameValueAs($sameItemCollection));
    }

    public function testToArray()
    {
        $anItem = new Item('some data');

        $anotherItem = new Item(234);

        $anItemCollection = new ItemCollection(array($anItem, $anotherItem));

        $this->assertEquals(array($anItem, $anotherItem), $anItemCollection->toArray());
    }

    public function testItems()
    {
        $anItem = new Item('some data');

        $anotherItem = new Item(234);

        $trueItem = new Item(true);

        $anItemCollection = new ItemCollection(array($anItem, $anotherItem, $trueItem));

        $aMetaInformation = new MetaInformation(
            new WorkflowRunId(Uuid::uuid4()),
            new ActionId(Uuid::uuid4()),
            new Name('TestQuery'),
            new Arguments(array()),
            3
        );

        $this->assertEquals(array($anItem, $anotherItem), $anItemCollection->items(0, 2, $aMetaInformation));
        $this->assertEquals(array($anotherItem, $trueItem), $anItemCollection->items(1, 2, $aMetaInformation));
    }

    public function testIsSingleItemResult()
    {
        $anItem = new Item('some data');

        $anotherItem = new Item(234);

        $anItemCollection = new ItemCollection(array($anItem, $anotherItem));

        $this->assertFalse($anItemCollection->isSingleItemResult());
    }

    public function testIsFullItemCollection()
    {
        $anItem = new Item('some data');

        $anotherItem = new Item(234);

        $anItemCollection = new ItemCollection(array($anItem, $anotherItem));

        $this->assertTrue($anItemCollection->isFullItemCollection());
    }

    public function testIsPagedItemCollection()
    {
        $anItem = new Item('some data');

        $anotherItem = new Item(234);

        $anItemCollection = new ItemCollection(array($anItem, $anotherItem));

        $this->assertFalse($anItemCollection->isPagedItemCollection());
    }

    public function testGetIterator()
    {
        $anItem = new Item('some data');

        $anotherItem = new Item(234);

        $anItemCollection = new ItemCollection(array($anItem, $anotherItem));

        foreach($anItemCollection as $i => $anItemOfCollection) {
            if ($i === 0) {
                $this->assertTrue($anItem->sameValueAs($anItemOfCollection));
            }

            if($i ===  1) {
                $this->assertTrue($anotherItem->sameValueAs($anItemOfCollection));
            }
        }
    }

    public function testOffsetExists()
    {
        $anItem = new Item('some data');

        $anotherItem = new Item(234);

        $anItemCollection = new ItemCollection(array($anItem, $anotherItem));

        $this->assertTrue(isset($anItemCollection[1]));
        $this->assertFalse(isset($anItemCollection[2]));
    }

    public function testOffsetGet()
    {
        $anItem = new Item('some data');

        $anotherItem = new Item(234);

        $anItemCollection = new ItemCollection(array($anItem, $anotherItem));

        $sameItem = $anItemCollection[0];

        $this->assertTrue($anItem->sameValueAs($sameItem));
    }

    public function testOffsetSet()
    {
        $anItem = new Item('some data');

        $anotherItem = new Item(234);

        $anItemCollection = new ItemCollection(array($anItem, $anotherItem));

        $anItemCollection[] = new Item('new Item');

        $this->assertEquals('new Item', $anItemCollection[2]->data());
    }

    public function testOffsetUnset()
    {
        $anItem = new Item('some data');

        $anotherItem = new Item(234);

        $anItemCollection = new ItemCollection(array($anItem, $anotherItem));

        unset($anItemCollection[1]);

        $this->assertFalse(isset($anItemCollection[1]));
    }

    public function testCount()
    {
        $anItem = new Item('some data');

        $anotherItem = new Item(234);

        $anItemCollection = new ItemCollection(array($anItem, $anotherItem));

        $this->assertEquals(2, count($anItemCollection));
    }
} 