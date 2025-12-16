<?php

namespace Michel\PaperORMBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('paper_orm');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
            ->scalarNode('dsn')
            ->info('Database DSN for PaperORM (ex: mysql://user:pass@127.0.0.1:3306/db)')
            ->isRequired()
            ->end()

            ->booleanNode('debug')
            ->info('Enable debug mode (more verbose logging, strict checks)')
            ->defaultFalse()
            ->end()

            ->scalarNode('logger')
            ->info('Logger service ID (set null to disable logging)')
            ->defaultNull()
            ->end()

            ->scalarNode('entity_dir')
            ->info('Directory containing your entities')
            ->defaultValue('%kernel.project_dir%/src/Entity')
            ->end()

            ->scalarNode('migrations_dir')
            ->info('Directory where PaperORM will generate/read migrations')
            ->defaultValue('%kernel.project_dir%/migrations')
            ->end()

            ->scalarNode('migrations_table')
            ->info('Database table name used to track executed migrations')
            ->defaultValue('mig_versions')
            ->end()

            ->booleanNode('proxy_autoload')
            ->info('Enable proxy autoload')
            ->defaultFalse()
            ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
