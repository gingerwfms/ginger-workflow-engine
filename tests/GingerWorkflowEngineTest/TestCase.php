<?php
/*
 * This file is part of the codeliner/ginger-plugin-installer package.
 * (c) Alexander Miertsch <kontakt@codeliner.ws>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace GingerWorkflowEngineTest;

use Malocher\EventStore\Configuration\Configuration;
use Malocher\EventStore\EventStore;

/**
 * TestCase
 * 
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EventStore
     */
    protected $eventStore;

    /**
     *
     * @return EventStore
     */
    protected function getTestEventStore()
    {
        if (null === $this->eventStore) {
            $config = new Configuration(array(
                'adapter' => array(
                    'MalocherEventStoreModule\Adapter\Zf2EventStoreAdapter' => array(
                        'connection' => array(
                            'driver' => 'Pdo_Sqlite',
                            'database' => ':memory:'
                        )
                    )
                ),
                'repository_map' => array(
                    'GingerWorkflowEngine\Model\WorkflowRun\WorkflowRun' => 'GingerWorkflowEngine\Infrastructure\Persistence\WorkflowRunRepositoryEventStore',
                    'GingerWorkflowEngine\Model\Action\Action'           => 'GingerWorkflowEngine\Infrastructure\Persistence\ActionRepositoryEventStore',
                    'GingerWorkflowEngine\Model\QueryResult\QueryResult' => 'GingerWorkflowEngine\Infrastructure\Persistence\QueryResultRepositoryEventStore',
                )
            ));

            $this->eventStore = new EventStore($config);
        }


        return $this->eventStore;
    }

    protected function createSchema(array $streams)
    {
        $adapter = $this->getTestEventStore()->getAdapter();

        $adapter->dropSchema($streams);
        $adapter->createSchema($streams);
    }
}
