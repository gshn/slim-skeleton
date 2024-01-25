<?php

namespace App\Test\Traits;

use App\Factory\QueryFactory;
use DI\ContainerBuilder;
use PDO;
use Slim\App;

trait AppTestTrait
{
    use ContainerTestTrait;
    use HttpTestTrait;
    use HttpJsonTestTrait;

    protected App $app;
    protected QueryFactory $queryFactory;

    /**
     * Before each test.
     */
    protected function setUp(): void
    {
        $this->setUpApp();
    }

    protected function setUpApp(): void
    {
        $container = (new ContainerBuilder())
            ->addDefinitions(__DIR__ . '/../../config/container.php')
            ->build();

        $this->app = $container->get(App::class);

        $this->setUpContainer($container);

        /** @phpstan-ignore-next-line */
        if (method_exists($this, 'setUpDatabase')) {
            $this->setUpDatabase(__DIR__ . '/../../resources/schema/schema.sql');
        }

        $this->queryFactory = new QueryFactory($container->get(PDO::class));
    }
}
