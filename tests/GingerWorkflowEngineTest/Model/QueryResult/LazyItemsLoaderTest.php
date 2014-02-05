<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 01.02.14 - 22:08
 */

namespace GingerWorkflowEngineTest\Model\QueryResult;

use GingerWorkflowEngine\Model\Action\ActionId;
use GingerWorkflowEngine\Model\Action\Arguments;
use GingerWorkflowEngine\Model\Action\Name;
use GingerWorkflowEngine\Model\QueryResult\ItemsLoaderManager;
use GingerWorkflowEngine\Model\QueryResult\LazyItemsLoader;
use GingerWorkflowEngine\Model\QueryResult\MetaInformation;
use GingerWorkflowEngine\Model\WorkflowRun\WorkflowRunId;
use GingerWorkflowEngineTest\Mock\ItemsLoaderMock;
use GingerWorkflowEngineTest\TestCase;
use Rhumsaa\Uuid\Uuid;

/**
 * Class LazyItemsLoaderTest
 *
 * @package GingerWorkflowEngineTest\Model\QueryResult
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class LazyItemsLoaderTest extends TestCase
{
    /**
     * @var LazyItemsLoader
     */
    private $lazyItemsLoader;

    protected function setUp()
    {
        $anItemsLoaderManager = new ItemsLoaderManager();

        $anItemsLoader = new ItemsLoaderMock();

        $anItemsLoaderManager->setInvokableClass($anItemsLoader->name(), get_class($anItemsLoader));

        LazyItemsLoader::setItemsLoaderManager($anItemsLoaderManager);

        $this->lazyItemsLoader = new LazyItemsLoader($anItemsLoader);
    }

    public function testToAndFromJsonAndSameValueAs()
    {
        $jsonString = $this->lazyItemsLoader->toJSON();

        $this->assertJson($jsonString);

        $aLazyItemsLoader = LazyItemsLoader::fromJSON($jsonString);

        $this->assertTrue($this->lazyItemsLoader->sameValueAs($aLazyItemsLoader));
    }

    public function testItems()
    {
        $aMetaInformation = new MetaInformation(
            new WorkflowRunId(Uuid::uuid4()),
            new ActionId(Uuid::uuid4()),
            new Name('TestQuery'),
            new Arguments(array()),
            4
        );

        $anItemsList = $this->lazyItemsLoader->items(1, 2, $aMetaInformation);

        $anItemsStringList = array();

        foreach($anItemsList as $anItem) {
            $anItemsStringList[] = $anItem->data();
        }

        $this->assertEquals(array('second item', 'third item'), $anItemsStringList);
    }

    public function testIsSingleItemResult()
    {
        $this->assertFalse($this->lazyItemsLoader->isSingleItemResult());
    }

    public function testIsFullItemCollection()
    {
        $this->assertFalse($this->lazyItemsLoader->isFullItemCollection());
    }

    public function testIsPagedItemCollection()
    {
        $this->assertTrue($this->lazyItemsLoader->isPagedItemCollection());
    }
} 