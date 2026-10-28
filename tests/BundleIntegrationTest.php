<?php

namespace Test\Michel\PaperORMBundle;

use Michel\PaperORM\Entity\EntityInterface;
use Michel\PaperORM\EntityManager;
use Michel\PaperORM\EntityManagerInterface;
use Michel\PaperORM\PaperConfiguration;
use Michel\PaperORMBundle\DataCollector\PaperORMCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Test\Michel\PaperORMBundle\Fixtures\TestKernel;

final class BundleIntegrationTest extends KernelTestCase
{

    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }

    protected function setUp(): void
    {
        self::bootKernel();
    }

    public function testEntityManagerIsWired(): void
    {

        $container = self::getContainer();
        $em = $container->get(EntityManagerInterface::class);
        $this->assertInstanceOf(EntityManagerInterface::class, $em);
    }

    public function testConfigIsWired()
    {

        $container = self::getContainer();
        $this->assertSame('sqlite:///:memory:', $container->getParameter('paper_orm.dsn'));
        $this->assertTrue($container->getParameter('paper_orm.debug'));
        $this->assertSame('paper.logger', $container->getParameter('paper_orm.logger'));
        $this->assertStringEndsWith('/tests/Fixtures/src/Entity', $container->getParameter('paper_orm.entity_dir'));
        $this->assertStringEndsWith('/tests/Fixtures/migrations', $container->getParameter('paper_orm.migrations_dir'));
        $this->assertSame('mig_versions', $container->getParameter('paper_orm.migrations_table'));
        $this->assertFalse($container->getParameter('paper_orm.proxy_autoload'));

    }

    public function testEntityManagerIsResetBetweenRequests(): void
    {
        $container = self::getContainer();
        $em = $container->get(EntityManagerInterface::class);
        $em->persist(new class implements EntityInterface {
            public function getPrimaryKeyValue()
            {
                return null;
            }
        });
        $unitOfWork = (new \ReflectionProperty($em, 'unitOfWork'))->getValue($em);
        $this->assertCount(1, $unitOfWork->getEntityInsertions());

        // What a worker runs between two requests.
        $container->get('services_resetter')->reset();
        $this->assertCount(0, $unitOfWork->getEntityInsertions());
    }

    public function testCollectorDoesNotConnect(): void
    {
        $em = EntityManager::createFromConfig(PaperConfiguration::fromDsn('sqlite://:memory:', true));
        $collector = new PaperORMCollector($em);
        $collector->collect(new Request(), new Response());

        $this->assertFalse($em->getConnection()->isConnected());
        $this->assertSame(0, $collector->getQueryCount());
    }
}
