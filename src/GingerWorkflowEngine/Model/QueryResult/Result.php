<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 01.02.14 - 17:45
 */

namespace GingerWorkflowEngine\Model\QueryResult;

use Codeliner\Domain\Shared\ValueObjectInterface;

/**
 * Interface Result
 *
 * @package GingerWorkflowEngine\Model\QueryResult
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
interface Result extends ValueObjectInterface
{
    //Marker interface for the three Result types: Item, ItemCollection, LazyItemsLoader

    /**
     * @param string $jsonString
     * @return Result
     */
    public static function fromJSON($jsonString);

    /**
     * @return string A valid JSON string
     */
    public function toJSON();

    /**
     * @return bool
     */
    public function isSingleItemResult();

    /**
     * @return bool
     */
    public function isFullItemCollection();

    /**
     * @return bool
     */
    public function isPagedItemCollection();

    /**
     * @param int             $offset
     * @param int             $itemsPerPage
     * @param MetaInformation $aMetaInformation
     * @return Item[]
     */
    public function items($offset, $itemsPerPage, MetaInformation $aMetaInformation);
} 