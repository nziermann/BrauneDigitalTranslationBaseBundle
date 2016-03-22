<?php

namespace BrauneDigital\TranslationBaseBundle\Routing;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;

class ServiceRouter extends Router
{
    protected $matcher;
    protected $matcherId;

    protected $generator;
    protected $generatorId;

    protected $container;

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
    public function __construct(ContainerInterface $container, $resource, RequestContext $context, $generatorId, $matcherId) {
        parent::__construct($container, $resource, array(), $context);
        $this->container = $container;
        $this->generatorId = $generatorId;
        $this->matcherId = $matcherId;
    }

    /**
     * Gets the UrlGenerator instance associated with this Router.
     *
     * @return UrlGeneratorInterface A UrlGeneratorInterface instance
     */
    public function getGenerator()    {

        if($this->generator == null) {
            $this->generator = $this->container->get($this->generatorId);
            $this->generator->setRoutes($this->getRouteCollection());
            $this->generator->setContext($this->getContext());
        }

        return $this->generator;
    }

    /**
     * Gets the UrlMatcher instance associated with this Router.
     *
     * @return UrlMatcherInterface A UrlMatcherInterface instance
     */
    public function getMatcher() {

        if($this->matcher == null) {
            $this->matcher = $this->container->get($this->matcherId);
            $this->matcher->setRoutes($this->getRouteCollection());
            $this->matcher->setContext($this->getContext());
        }

        return $this->matcher;
    }
}