<?php

namespace BrauneDigital\TranslationBaseBundle\Routing;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;

class ServiceRouter extends Router
{
    /**
     * @var array
     */
    protected $locales;

    /**
     * @var string
     */
    protected $defaultLocale;

    protected $matcher;

    protected $generator;

    /**
     * ServiceRouter constructor.
     *
     * @param ContainerInterface    $container
     * @param mixed                 $resource
     * @param array                 $options
     * @param RequestContext        $context
     * @param UrlGeneratorInterface $generator
     * @param UrlMatcherInterface   $matcher
     */
    public function __construct(ContainerInterface $container, $resource, RequestContext $context, LocalizedUrlGenerator $generator, LocalizedUrlMatcher $matcher) {
        parent::__construct($container, $resource, array(), $context);
        $this->generator = $generator;
        $this->matcher = $matcher;

        $this->generator->setRoutes($this->getRouteCollection());
        $this->generator->setContext($this->getContext());
        $this->matcher->setRoutes($this->getRouteCollection());
        $this->matcher->setContext($this->getContext());
    }

    /**
     * Gets the UrlGenerator instance associated with this Router.
     *
     * @return UrlGeneratorInterface A UrlGeneratorInterface instance
     */
    public function getGenerator()    {
        return $this->generator;
    }

    /**
     * Gets the UrlMatcher instance associated with this Router.
     *
     * @return UrlMatcherInterface A UrlMatcherInterface instance
     */
    public function getMatcher() {
        return $this->matcher;
    }

    //No Warmup needed
    public function warmUp($cacheDir) {

    }
}