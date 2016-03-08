<?php

namespace BrauneDigital\TranslationBaseBundle\Routing;

use Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class LocalizedUrlMatcher extends RedirectableUrlMatcher {

    protected $locales;
    protected $defaultLocale;

    public function __construct(RequestContext $context,  $locales = array('en'), $default = 'en') {
        $this->context = $context;
        $this->locales = $locales;
        $this->defaultLocale = $default;
    }

    /**
     * @return RouteCollection
     */
    public function getRoutes() {
        return $this->routes;
    }

    /**
     * @param RouteCollection $routes
     */
    public function setRoutes($routes) {
        $this->routes = $routes;
    }
}