<?php

namespace BrauneDigital\TranslationBaseBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class RouterInjectionPass
 *
 * @package BrauneDigital\TranslationBaseBundle\DependencyInjection\Compiler
 */
class RouterInjectionPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     * Inject the service router as the normal router
     */
    public function process(ContainerBuilder $container) {
        if($container->has('braune_digital.translation_base.routing.service_router')) {
            $container->removeAlias('router');
            $container->addAliases(array('router' => 'braune_digital.translation_base.routing.service_router'));
        }
    }
}