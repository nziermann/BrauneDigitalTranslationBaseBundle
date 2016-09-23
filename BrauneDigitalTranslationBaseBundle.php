<?php

namespace BrauneDigital\TranslationBaseBundle;

use BrauneDigital\TranslationBaseBundle\DependencyInjection\Compiler\RouterInjectionPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BrauneDigitalTranslationBaseBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new RouterInjectionPass());
    }
}
