<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 31.01.14 - 19:55
 */

namespace GingerWorkflowEngine\Model\QueryResult;

use Assert\Assertion;
use Codeliner\Comparison\EqualsBuilder;
use Codeliner\Domain\Shared\ValueObjectInterface;
use GingerWorkflowEngine\Model\Action\ActionId;
use GingerWorkflowEngine\Model\Action\Arguments;
use GingerWorkflowEngine\Model\Action\Name;
use GingerWorkflowEngine\Model\WorkflowRun\WorkflowRunId;
use Rhumsaa\Uuid\Uuid;

/**
 * Class Metainformation
 *
 * @package GingerWorkflowEngine\Model\QueryResult
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class MetaInformation implements ValueObjectInterface
{
    /**
     * @var WorkflowRunId
     */
    private $workflowRunId;

    /**
     * @var ActionId
     */
    private $actionId;

    /**
     * @var Name
     */
    private $actionName;

    /**
     * @var Arguments
     */
    private $actionArguments;

    /**
     * @var int
     */
    private $resultSetCount;

    /**
     * @param array $aMetaInformationArray
     * @return MetaInformation
     */
    public static function fromArray(array $aMetaInformationArray)
    {
        Assertion::keyExists($aMetaInformationArray, 'workflowRunId');
        Assertion::keyExists($aMetaInformationArray, 'actionId');
        Assertion::keyExists($aMetaInformationArray, 'actionName');
        Assertion::keyExists($aMetaInformationArray, 'actionArguments');
        Assertion::keyExists($aMetaInformationArray, 'resultSetCount');

        $aWorkflowRunId    = new WorkflowRunId(Uuid::fromString($aMetaInformationArray['workflowRunId']));
        $anActionId        = new ActionId(Uuid::fromString($aMetaInformationArray['actionId']));
        $anActionName      = new Name($aMetaInformationArray['actionName']);
        $anActionArguments = new Arguments($aMetaInformationArray['actionArguments']);
        $aResultSetCount   = $aMetaInformationArray['resultSetCount'];

        return new MetaInformation(
            $aWorkflowRunId,
            $anActionId,
            $anActionName,
            $anActionArguments,
            $aResultSetCount
        );
    }

    /**
     * @param WorkflowRunId $aWorkflowRunId
     * @param ActionId      $anActionId
     * @param Name          $anActionName
     * @param Arguments     $anArguments
     * @param int           $aResultSetCount
     */
    public function __construct(
        WorkflowRunId $aWorkflowRunId,
        ActionId $anActionId,
        Name $anActionName,
        Arguments $anArguments,
        $aResultSetCount)
    {
        Assertion::integer($aResultSetCount, "ResultSetCount must be an integer");

        $this->workflowRunId   = $aWorkflowRunId;
        $this->actionId        = $anActionId;
        $this->actionName      = $anActionName;
        $this->actionArguments = $anArguments;
        $this->resultSetCount  = $aResultSetCount;
    }

    /**
     * @return WorkflowRunId
     */
    public function workflowRunId()
    {
        return $this->workflowRunId;
    }

    /**
     * @return ActionId
     */
    public function actionId()
    {
        return $this->actionId;
    }

    /**
     * @return Name
     */
    public function actionName()
    {
        return $this->actionName;
    }

    /**
     * @return Arguments
     */
    public function actionArguments()
    {
        return $this->actionArguments;
    }

    /**
     * @return int
     */
    public function resultSetCount()
    {
        return $this->resultSetCount;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'workflowRunId'   => $this->workflowRunId()->toString(),
            'actionId'        => $this->actionId()->toString(),
            'actionName'      => $this->actionName()->toString(),
            'actionArguments' => $this->actionArguments()->toArray(),
            'resultSetCount'  => $this->resultSetCount()
        );
    }

    /**
     * @param ValueObjectInterface $other
     * @return boolean
     */
    public function sameValueAs(ValueObjectInterface $other)
    {
        if (!$other instanceof MetaInformation) {
            return false;
        }

        return EqualsBuilder::create()
            ->append($this->workflowRunId()->toString(), $other->workflowRunId()->toString())
            ->append($this->actionId()->toString(), $other->actionId()->toString())
            ->append($this->actionName()->toString(), $other->actionName()->toString())
            ->append($this->actionArguments()->toArray(), $other->actionArguments()->toArray())
            ->append($this->resultSetCount(), $other->resultSetCount())
            ->equals();
    }
}