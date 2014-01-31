<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 30.01.14 - 21:44
 */

namespace GingerWorkflowEngine\Model\Action;

use Codeliner\Domain\Shared\ValueObjectInterface;
use Rhumsaa\Uuid\Uuid;

/**
 * Class ActionId
 *
 * @package GingerWorkflowEngine\Model\Action
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class ActionId implements ValueObjectInterface
{
    /**
     * @var Uuid
     */
    private $uuid;

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
        if (!$other instanceof ActionId) {
            return false;
        }

        return $this->toString() === $other->toString();
    }
}