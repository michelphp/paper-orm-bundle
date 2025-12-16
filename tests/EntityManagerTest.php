<?php

namespace Test\Michel\PaperORMBundle;

use Michel\PaperORM\EntityManager;
use Michel\PaperORM\PaperConfiguration;
use PHPUnit\Framework\TestCase;

class EntityManagerTest extends TestCase
{

    public function testFromDsnCreatesConnection(): void
    {
        $em = EntityManager::createFromConfig(PaperConfiguration::fromDsn('sqlite://:memory:'));
        $this->assertInstanceOf(EntityManager::class, $em);
    }

}
