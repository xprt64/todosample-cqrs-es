<?php

namespace MongoDB\Tests\Operation;

use MongoDB\Operation\Count;
use MongoDB\Operation\CreateIndexes;
use MongoDB\Operation\InsertMany;
use MongoDB\Tests\CommandObserver;
use stdClass;

class CountFunctionalTest extends FunctionalTestCase
{
    public function testDefaultReadConcernIsOmitted()
    {
        (new CommandObserver)->observe(
            function() {
                $operation = new Count(
                    $this->getDatabaseName(),
                    $this->getCollectionName(),
                    [],
                    ['readConcern' => $this->createDefaultReadConcern()]
                );

                $operation->execute($this->getPrimaryServer());
            },
            function(stdClass $command) {
                $this->assertObjectNotHasAttribute('readConcern', $command);
            }
        );
    }

    public function testHintOption()
    {
        if (version_compare($this->getServerVersion(), '2.6.0', '<')) {
            $this->markTestSkipped('count command does not support "hint" option');
        }

        $insertMany = new InsertMany($this->getDatabaseName(), $this->getCollectionName(), [
            ['x' => 1],
            ['x' => 2],
            ['y' => 3],
        ]);
        $insertMany->execute($this->getPrimaryServer());

        $createIndexes = new CreateIndexes($this->getDatabaseName(), $this->getCollectionName(), [
            ['key' => ['x' => 1], 'sparse' => true, 'name' => 'sparse_x'],
            ['key' => ['y' => 1]],
        ]);
        $createIndexes->execute($this->getPrimaryServer());

        $hintsUsingSparseIndex = [
            ['x' => 1],
            'sparse_x',
        ];

        /* Per SERVER-22041, the count command in server versions before 3.3.2
         * may ignore the hint option if its query predicate is empty. */
        $filter = ['_id' => ['$exists' => true]];

        foreach ($hintsUsingSparseIndex as $hint) {
            $operation = new Count($this->getDatabaseName(), $this->getCollectionName(), $filter, ['hint' => $hint]);
            $this->assertEquals(2, $operation->execute($this->getPrimaryServer()));
        }

        $hintsNotUsingSparseIndex = [
            ['_id' => 1],
            ['y' => 1],
            'y_1',
        ];

        foreach ($hintsNotUsingSparseIndex as $hint) {
            $operation = new Count($this->getDatabaseName(), $this->getCollectionName(), $filter, ['hint' => $hint]);
            $this->assertEquals(3, $operation->execute($this->getPrimaryServer()));
        }
    }
}
