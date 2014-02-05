<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 01.02.14 - 21:19
 */

namespace GingerWorkflowEngine\Model\QueryResult;

/**
 * Interface ItemsLoader
 *
 * @package GingerWorkflowEngine\Model\QueryResult
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
interface ItemsLoader 
{
    /**
     * Returns the name that can be used to receive the ItemsLoader from the ItemsLoaderManager
     *
     * @return string
     */
    public function name();

    /**
     * @param int             $offset
     * @param int             $itemsCountPerPage
     * @param MetaInformation $aMetaInformation
     * @return Item[]
     */
    public function load($offset, $itemsCountPerPage, MetaInformation $aMetaInformation);
} 