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
        if($container->hasParameter('braune_digital.translation_base.use_routing') && $container->getParameter('braune_digital.translation_base.use_routing')) {
            $container->setAlias('router', 'braune_digital.translation_base.routing.service_router');
        }
    }
}