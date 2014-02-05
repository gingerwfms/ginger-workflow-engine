<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 01.02.14 - 22:09
 */

namespace GingerWorkflowEngineTest\Mock;

use GingerWorkflowEngine\Model\QueryResult\Item;
use GingerWorkflowEngine\Model\QueryResult\ItemsLoader;
use GingerWorkflowEngine\Model\QueryResult\MetaInformation;

class ItemsLoaderMock implements ItemsLoader
{
    private $items;

    public function __construct()
    {
        $this->items = array(
            new Item('first item'),
            new Item('second item'),
            new Item('third item'),
            new Item('fourth item'),
        );
    }

    /**
     * Returns the name that can be used to receive the ItemsLoader from the ItemsLoaderManager
     *
     * @return string
     */
    public function name()
    {
        return __CLASS__;
    }

    /**
     * @param int $offset
     * @param int $itemsCountPerPage
     * @param MetaInformation $aMetaInformation
     * @return Item[]
     */
    public function load($offset, $itemsCountPerPage, MetaInformation $aMetaInformation)
    {
        return array_slice($this->items, $offset, $itemsCountPerPage);
    }
}