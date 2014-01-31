<?php
/*
 * This file is part of the codeliner/ginger-workflow-engine.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 31.01.14 - 17:34
 */

namespace GingerWorkflowEngineTest\Infrastructure\Persistence;

use GingerWorkflowEngine\Infrastructure\Persistence\ActionRepositoryEventStore;
use GingerWorkflowEngine\Model\Action\Action;
use GingerWorkflowEngine\Model\Action\ActionId;
use GingerWorkflowEngine\Model\Action\Arguments;
use GingerWorkflowEngine\Model\Action\Name;
use GingerWorkflowEngine\Model\Action\Type;
use GingerWorkflowEngine\Model\WorkflowRun\WorkflowRunId;
use GingerWorkflowEngineTest\TestCase;
use Rhumsaa\Uuid\Uuid;

class ActionRepositoryEventStoreTest extends TestCase
{
    /**
     * @var ActionRepositoryEventStore
     */
    private $actionRepository;

    protected function setUp()
    {
        $this->createSchema(array('GingerWorkflowEngine\Model\Action\Action'));

        $this->actionRepository = $this->getTestEventStore()->getRepository('GingerWorkflowEngine\Model\Action\Action');
    }

    public function testStoreAndGetFromActionId()
    {
        $uuid = Uuid::uuid4();

        $anActionId = new ActionId($uuid);

        $anArguments = new Arguments(array('foo' => 'bar'));

        $workflowRunId = new WorkflowRunId(Uuid::uuid4());

        $anAction = new Action(
            $anActionId,
            new Type(Type::COMMAND),
            new Name('Testcommand'),
            $anArguments,
            $workflowRunId
        );

        $this->actionRepository->store($anAction);

        $this->getTestEventStore()->clear();

        $loadedAction = $this->actionRepository->getFromActionId($anActionId);

        $this->assertNotNull($loadedAction);

        $this->assertNotSame($anAction, $loadedAction);

        $this->assertTrue($anAction->sameIdentityAs($loadedAction));
    }
} 