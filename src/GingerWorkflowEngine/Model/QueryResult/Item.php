<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 01.02.14 - 17:47
 */

namespace GingerWorkflowEngine\Model\QueryResult;

use Assert\Assertion;
use Codeliner\Domain\Shared\ValueObjectInterface;
use Zend\Stdlib\ArrayUtils;
use Zend\Text\Exception\InvalidArgumentException;

/**
 * Class Item
 *
 * @package GingerWorkflowEngine\Model\QueryResult
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class Item implements Result
{
    /**
     * @var mixed
     */
    private $data;

    /**
     * @param string $jsonString
     * @return Item
     * @throws \Assert\AssertionFailedException
     */
    public static function fromJSON($jsonString)
    {
        Assertion::isJsonString($jsonString);

        return new Item(json_decode($jsonString, true));
    }

    /**
     * @param mixed $data
     * @throws \InvalidArgumentException
     */
    public function __construct($data)
    {
        Assertion::notNull($data, 'Data must not be null');

        if (is_object($data)) {
            throw new \InvalidArgumentException('Complex data must only be structured as array. An object is not allowed');
        }

        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return bool
     */
    public function isScalar()
    {
        return is_scalar($this->data());
    }

    /**
     * @return bool
     */
    public function isBoolean()
    {
        return is_bool($this->data());
    }

    /**
     * @return bool
     */
    public function isInteger()
    {
        return is_int($this->data());
    }

    /**
     * @return bool
     */
    public function isFloat()
    {
        return is_float($this->data());
    }

    /**
     * @return bool
     */
    public function isString()
    {
        return is_string($this->data());
    }

    /**
     * @return bool
     */
    public function isArray()
    {
        return is_array($this->data());
    }

    /**
     * @return bool
     */
    public function isList()
    {
        return ArrayUtils::isList($this->data());
    }

    /**
     * @return bool
     */
    public function isHashTable()
    {
        return ArrayUtils::isHashTable($this->data());
    }

    /**
     * @return string
     */
    public function toJSON()
    {
        return json_encode($this->data());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toJSON();
    }

    /**
     * @param ValueObjectInterface $other
     * @return boolean
     */
    public function sameValueAs(ValueObjectInterface $other)
    {
        if (!$other instanceof Item) {
            return false;
        }

        return $this->data() === $other->data();
    }

    /**
     * @param int             $offset
     * @param int             $itemsPerPage
     * @param MetaInformation $aMetaInformation
     * @return array|Item[]
     */
    public function items($offset, $itemsPerPage, MetaInformation $aMetaInformation)
    {
        if ($offset === 0) {
            return array($this);
        } else {
            return array();
        }
    }

    /**
     * @return bool
     */
    public function isSingleItemResult()
    {
        return true;
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
}