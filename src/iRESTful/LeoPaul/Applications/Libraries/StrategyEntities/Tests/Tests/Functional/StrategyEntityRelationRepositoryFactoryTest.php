<?php
namespace iRESTful\LeoPaul\Applications\Libraries\StrategyEntities\Tests\Tests\Functional;
use iRESTful\LeoPaul\Applications\Libraries\StrategyEntities\Infrastructure\Factories\StrategyEntityRelationRepositoryFactory;

final class StrategyRelationRepositoryFactoryTest extends \PHPUnit_Framework_TestCase {
    private $factory;
    public function setUp() {

        $containerRepositoryMapper = [
            'my_container' => $this->createMock('iRESTful\LeoPaul\Objects\Entities\Entities\Domain\Repositories\EntityRepository')
        ];

        $this->factory = new StrategyEntityRelationRepositoryFactory($containerRepositoryMapper);

    }

    public function tearDown() {

    }

    public function testCreate_Success() {

        $repository = $this->factory->create();

        $this->assertTrue($repository instanceof \iRESTful\LeoPaul\Applications\Libraries\StrategyEntities\Infrastructure\Repositories\StrategyEntityRelationRepository);

    }

}
