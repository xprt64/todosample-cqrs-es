<?php
/**
 * Copyright (c) 2018 Constantin Galbenu <xprt64@gmail.com>
 */

namespace tests\unit\Gica\Serialize\ObjectHydratorWithPrivateParentPropertiesTest {

    use Gica\Serialize\ObjectHydrator\ObjectHydrator;
    use Gica\Serialize\ObjectHydrator\ObjectUnserializer\CompositeObjectUnserializer;
    use ns2\SomeNestedObject;

    class ObjectHydratorWithPrivateParentPropertiesTest extends \PHPUnit_Framework_TestCase
    {

        public function test_hydrateObject()
        {
            $document = [
                'a'      => 'a',
                'b'      => 'b',
                'n'      => ['p' => 32],
                'nArray' => [
                    ['p' => 33],
                    ['p' => 34],
                ],
                't1_a'   => 11,
                't1_b'   => 't1_b',
                't2_a'   => 56,
                't2_b'   => 't2_b',
            ];

            $sut = new ObjectHydrator(new CompositeObjectUnserializer([]));

            /** @var MyObject $reconstructed */
            $reconstructed = $sut->hydrateObject(MyObject::class, $document);

            $this->assertInstanceOf(MyObject::class, $reconstructed);
            $this->assertSame($document['a'], $reconstructed->getA());
            $this->assertSame($document['b'], $reconstructed->getB());
            $this->assertSame($document['t1_a'], $reconstructed->getT1A());
            $this->assertSame($document['t1_b'], $reconstructed->getT1B());
            $this->assertSame($document['t2_a'], $reconstructed->getT2A());
            $this->assertSame($document['t2_b'], $reconstructed->getT2B());
            $this->assertInstanceOf(SomeNestedObject::class, $reconstructed->getN());
            $this->assertSame($document['n']['p'], $reconstructed->getN()->getP());
            $this->assertCount(2, $reconstructed->getNArray());
            $this->assertSame($document['nArray'][0]['p'], $reconstructed->getNArray()[0]->getP());
            $this->assertSame($document['nArray'][1]['p'], $reconstructed->getNArray()[1]->getP());
        }
    }

    class MyParent2
    {
        /**
         * @var int
         */
        private $t2_a;
        private $t2_b;

        public function __construct($t2_a, $t2_b)
        {
            $this->t2_a = $t2_a;
            $this->t2_b = $t2_b;
        }

        public function getT2A()
        {
            return $this->t2_a;
        }

        public function getT2B()
        {
            return $this->t2_b;
        }
    }

    class MyParent1 extends MyParent2
    {
        private $t1_a;
        private $t1_b;

        /**
         * @var SomeNestedObject[]
         */
        private $nArray;

        public function __construct($t1_a, $t1_b, $t2_a, $t2_b)
        {
            parent::__construct($t2_a, $t2_b);
            $this->t1_a = $t1_a;
            $this->t1_b = $t1_b;
        }

        public function getT1A()
        {
            return $this->t1_a;
        }

        public function getT1B()
        {
            return $this->t1_b;
        }

        /**
         * @return SomeNestedObject[]
         */
        public function getNArray(): array
        {
            return $this->nArray;
        }
    }

    class MyObject extends MyParent1
    {
        private $a;
        private $b;

        /** @var SomeNestedObject */
        private $n;

        public function __construct($a, $b, $t1_a, $t1_b, $t2_a, $t2_b)
        {
            parent::__construct($t1_a, $t1_b, $t2_a, $t2_b);
            $this->a = $a;
            $this->b = $b;
        }

        public function getA()
        {
            return $this->a;
        }

        public function getB()
        {
            return $this->b;
        }

        public function getN(): SomeNestedObject
        {
            return $this->n;
        }
    }
}

namespace ns2 {
    class SomeNestedObject
    {
        /**
         * @var int
         */
        private $p;

        public function __construct($p)
        {
            $this->p = $p;
        }

        public function getP(): int
        {
            return $this->p;
        }
    }
}