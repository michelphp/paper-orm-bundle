<?php

namespace Michel\PaperORMBundle\DependencyInjection;

use Michel\PaperORM\PaperConfiguration;
use Michel\PaperORMBundle\DataCollector\PaperORMCollector;
use Symfony\Bundle\FrameworkBundle\DataCollector\AbstractDataCollector;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Reference;

final class PaperORMExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);


        $container->setParameter('paper_orm.dsn', $config['dsn']);
        $container->setParameter('paper_orm.debug', $config['debug']);
        $container->setParameter('paper_orm.logger', $config['logger']);
        $container->setParameter('paper_orm.entity_dir', $config['entity_dir']);
        $container->setParameter('paper_orm.migrations_dir', $config['migrations_dir']);
        $container->setParameter('paper_orm.migrations_table', $config['migrations_table']);
        $container->setParameter('paper_orm.proxy_autoload', $config['proxy_autoload'] ?? false);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.yaml');

        if ($config['logger'] !== null) {
            $container->getDefinition(PaperConfiguration::class)
                ->addMethodCall('withLogger', [new Reference($config['logger'])]);
        }

        // The collector extends a FrameworkBundle class, which the bundle does not require.
        if (!class_exists(AbstractDataCollector::class)) {
            $container->removeDefinition(PaperORMCollector::class);
        }
    }
    public function prepend(ContainerBuilder $container): void
    {
        $container->prependExtensionConfig('twig', [
            'paths' => [
                dirname(__DIR__, 2).'/templates' => 'PaperORMBundle'
            ]
        ]);
    }

}

