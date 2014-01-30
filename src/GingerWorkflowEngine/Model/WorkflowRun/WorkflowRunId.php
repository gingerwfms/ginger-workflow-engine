<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 25.01.14 - 22:55
 */

namespace GingerWorkflowEngine\Model\WorkflowRun;

use Codeliner\Domain\Shared\ValueObjectInterface;
use Rhumsaa\Uuid\Uuid;

/**
 * Value Object WorkflowRunId is the unique identifier of a WorkflowRun-Aggregate
 *
 * @package GingerWorkflowEngine\Model\WorkflowRun
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class WorkflowRunId implements ValueObjectInterface
{
    /**
     * @var Uuid
     */
    protected $uuid;

    /**
     * @param Uuid $uuid
     */
    public function __construct(Uuid $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return string
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
        if (!$other instanceof WorkflowRunId) {
            return false;
        }

        return $this->toString() === $other->toString();
    }
}