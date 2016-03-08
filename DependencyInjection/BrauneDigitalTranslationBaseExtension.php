<?php

namespace BrauneDigital\TranslationBaseBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class BrauneDigitalTranslationBaseExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $config = $this->processConfiguration($configuration, $configs);

        $bundles = $container->getParameter('kernel.bundles');

        if($config['routing']) {
            $loader->load('routing_services.yml');

            //inject as main router:
            $container->removeAlias('router');
            $container->addAliases(array('router' => 'braune_digital.translation_base.routing.service_router'));
        }
        
        if($config['admin'] && isset($bundles['SonataAdminBundle'])) {
            $loader->load('admin.yml');
        }
    }
}
