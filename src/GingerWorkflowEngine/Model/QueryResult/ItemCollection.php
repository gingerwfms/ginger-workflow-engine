<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 01.02.14 - 20:09
 */

namespace GingerWorkflowEngine\Model\QueryResult;

use Assert\Assertion;
use Codeliner\Domain\Shared\ValueObjectInterface;
use Traversable;

/**
 * Class ItemCollection
 *
 * @package GingerWorkflowEngine\Model\QueryResult
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class ItemCollection implements Result, \Countable, \ArrayAccess, \IteratorAggregate
{
    /**
     * @var Item[]
     */
    private $itemCollection;

    /**
     * @param string $jsonString
     * @return Result
     */
    public static function fromJSON($jsonString)
    {
        Assertion::isJsonString($jsonString);

        $itemJsonStrings = json_decode($jsonString, true);

        $items = array();

        foreach($itemJsonStrings as $anItemJsonString) {
            $items[] = Item::fromJSON($anItemJsonString);
        }

        return new ItemCollection($items);

        $p = new Paginator();
    }

    /**
     * @param array $anItemCollection
     */
    public function __construct(array $anItemCollection)
    {
        Assertion::allIsInstanceOf($anItemCollection, 'GingerWorkflowEngine\Model\QueryResult\Item', 'All elements in the ItemCollection must be of type Item');

        $this->itemCollection = $anItemCollection;
    }

    public function has(Item $anItem)
    {
        foreach($this->itemCollection as $myItem) {
            if ($myItem->sameValueAs($anItem)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Item[]
     */
    public function toArray()
    {
        return $this->itemCollection;
    }

    /**
     * @return string
     */
    public function toJSON()
    {
        $jsonStrings = array();

        foreach($this->itemCollection as $anItem) {
            $jsonStrings[] = $anItem->toJSON();
        }

        return json_encode($jsonStrings);
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
        return true;
    }

    /**
     * @return bool
     */
    public function isPagedItemCollection()
    {
        return false;
    }

    /**
     * @param ValueObjectInterface $other
     * @return boolean
     */
    public function sameValueAs(ValueObjectInterface $other)
    {
        if (!$other instanceof ItemCollection) {
            return false;
        }

        if ($this->count() !== $other->count()) {
            return false;
        }

        foreach($this->itemCollection as $myItem) {
            if (!$other->has($myItem)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param int             $offset
     * @param int             $itemsPerPage
     * @param MetaInformation $aMetaInformation
     * @return array|Item[]
     */
    public function items($offset, $itemsPerPage, MetaInformation $aMetaInformation)
    {
        return array_slice($this->itemCollection, $offset, $itemsPerPage);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->itemCollection);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return isset($this->itemCollection[$offset]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return Item
     */
    public function offsetGet($offset)
    {
        return $this->itemCollection[$offset];
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        Assertion::isInstanceOf($value, 'GingerWorkflowEngine\Model\QueryResult\Item');
        if (is_null($offset)) {
            $this->itemCollection[] = $value;
        } else {
            $this->itemCollection[$offset] = $value;
        }
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->itemCollection[$offset]);
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->itemCollection);
    }
}