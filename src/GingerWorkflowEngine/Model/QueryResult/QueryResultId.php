<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 01.02.14 - 22:39
 */

namespace GingerWorkflowEngine\Model\QueryResult;

use Codeliner\Domain\Shared\ValueObjectInterface;
use Rhumsaa\Uuid\Uuid;

/**
 * Class QueryResultId
 *
 * @package GingerWorkflowEngine\Model\QueryResult
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class QueryResultId implements ValueObjectInterface
{
    /**
     * @var Uuid
     */
    private $uuid;

    /**
     * @param Uuid $uuid
     */
    public function __construct(Uuid $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @string
     */
    public function toString()
    {
        return $this->uuid->toString();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }


    /**
     * @param ValueObjectInterface $other
     * @return boolean
     */
    public function sameValueAs(ValueObjectInterface $other)
    {
        if (!$other instanceof QueryResultId) {
            return false;
        }

        return $this->toString() === $other->toString();
    }
} 