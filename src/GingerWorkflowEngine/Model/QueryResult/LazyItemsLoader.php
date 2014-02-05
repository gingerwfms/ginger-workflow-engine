<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 01.02.14 - 21:15
 */

namespace GingerWorkflowEngine\Model\QueryResult;

use Assert\Assertion;
use Codeliner\Domain\Shared\ValueObjectInterface;

/**
 * Class LazyItemsLoader
 *
 * @package GingerWorkflowEngine\Model\QueryResult
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class LazyItemsLoader implements Result
{
    /**
     * @var ItemsLoader
     */
    private $itemsLoader;

    /**
     * @var ItemsLoaderManager
     */
    private static $itemsLoaderManager;


    public static function setItemsLoaderManager(ItemsLoaderManager $anItemsLoaderManager)
    {
        static::$itemsLoaderManager = $anItemsLoaderManager;
    }

    /**
     * @param string $jsonString
     * @return LazyItemsLoader
     * @throws \RuntimeException
     */
    public static function fromJSON($jsonString)
    {
        if (is_null(static::$itemsLoaderManager)) {
            throw new \RuntimeException('ItemsLoaderManager is missing');
        }

        Assertion::isJsonString($jsonString);

        $anItemsLoaderName = json_decode($jsonString);

        Assertion::string($anItemsLoaderName);

        $anItemsLoader = static::$itemsLoaderManager->get($anItemsLoaderName);

        return new LazyItemsLoader($anItemsLoader);
    }

    public function __construct(ItemsLoader $anItemsLoader)
    {
        $this->itemsLoader = $anItemsLoader;
    }

    /**
     * @return string A valid JSON string
     */
    public function toJSON()
    {
        return json_encode($this->itemsLoader->name());
    }

    /**
     * @param int             $offset
     * @param int             $itemsPerPage
     * @param MetaInformation $aMetaInformation
     * @return array|Item[]
     */
    public function items($offset, $itemsPerPage, MetaInformation $aMetaInformation)
    {
        return $this->itemsLoader->load($offset, $itemsPerPage, $aMetaInformation);
    }

    /**
     * @param ValueObjectInterface $other
     * @return boolean
     */
    public function sameValueAs(ValueObjectInterface $other)
    {
        if (!$other instanceof LazyItemsLoader) {
            return false;
        }

        return $this->toJSON() === $other->toJSON();
    }

    /**
     * @return bool
     */
    public function isSingleItemResult()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isFullItemCollection()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isPagedItemCollection()
    {
        return true;
    }
}