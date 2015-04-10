<?php

namespace Behatch\Notifier;

use Behat\Testwork\ServiceContainer\ExtensionManager;
use Behat\Testwork\Hook\ServiceContainer\HookExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class Extension extends HookExtension
{
    public function getConfigKey()
    {
        return 'behatch_notifiers';
    }

    public function initialize(ExtensionManager $extensionManager)
    {
    }

    public function process(ContainerBuilder $container)
    {
    }

    public function load(ContainerBuilder $container, array $config)
    {
    }

    public function configure(ArrayNodeDefinition $builder)
    {
    }

    public function getCompilerPasses()
    {
        return [];
    }
}
