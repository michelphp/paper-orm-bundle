<?php

namespace Test\Michel\PaperORMBundle\Fixtures;

use Michel\PaperORMBundle\PaperORMBundle;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
final class TestKernel extends Kernel
{
    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new PaperORMBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $confDir = __DIR__ . '/config';

        // Charge la config du framework en mode test
        $loader->load($confDir . '/packages/framework.yaml');
        $loader->load($confDir . '/packages/paper_orm.yaml');
        $loader->load($confDir . '/services_test.yaml');
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
    }

    public function getProjectDir(): string
    {
        return __DIR__;
    }
}
