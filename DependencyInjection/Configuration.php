<?php

namespace BrauneDigital\TranslationBaseBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('braune_digital_translation_base');

        $this->addAdminSection($rootNode);
        $this->addRoutingSection($rootNode);

        return $treeBuilder;
    }

    /**
     * @param $rootNode
     */
    protected function addAdminSection(ArrayNodeDefinition $rootNode) {
        $rootNode->children()->booleanNode('admin')->defaultTrue();
    }

    /**
     * @param $rootNode
     */
    protected function addRoutingSection(ArrayNodeDefinition $rootNode) {
        $rootNode->children()->booleanNode('routing')->defaultFalse();
    }
}
