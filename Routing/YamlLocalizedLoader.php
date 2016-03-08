<?php

namespace BrauneDigital\TranslationBaseBundle\Routing;

use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RouteCollection;

class YamlLocalizedLoader extends YamlFileLoader {

    protected $locales;

    /**
     * YamlLocalizedLoader constructor.
     *
     * @param FileLocatorInterface $locator
     * @param array                $locales
     */
    public function __construct(FileLocatorInterface $locator, array $locales = array('en')) {
        parent::__construct($locator);
        $this->locales = $locales;
    }

    /**
     * Parses a route and adds it to the RouteCollection.
     *
     * @param RouteCollection $collection A RouteCollection instance
     * @param string          $name       Route name
     * @param array           $config     Route definition
     * @param string          $path       Full path of the YAML file being processed
     */
    protected function parseRoute(RouteCollection $collection, $name, array $config, $path)
    {
        parent::parseRoute($collection, $name, $this->modifyConfig($config), $path);
    }

    /**
     * Parses an import and adds the routes in the resource to the RouteCollection.
     *
     * @param RouteCollection $collection A RouteCollection instance
     * @param array           $config     Route definition
     * @param string          $path       Full path of the YAML file being processed
     * @param string          $file       Loaded file name
     */
    protected function parseImport(RouteCollection $collection, array $config, $path, $file)
    {
        parent::parseImport($collection, $this->modifyConfig($config), $path, $file);
    }

    /**
     *
     */
    protected function modifyConfig($config) {

        $requirements = isset($config['requirements']) ? $config['requirements'] : array();

        if(!array_key_exists('_locale', $requirements)) {
            $requirements['_locale'] = implode('|', $this->locales);
        }

        $config['requirements'] = $requirements;

        $options = isset($config['options']) ? $config['options'] : array();

        if(!array_key_exists('compiler_class', $requirements)) {
            $options['compiler_class'] = "BrauneDigital\\TranslationBaseBundle\\Routing\\LocalizedRouteCompiler";
        }

        $config['options'] = $options;

        return $config;
    }

    /**
     * @param mixed $resource
     * @param null  $type
     *
     * @return bool
     */
    public function supports($resource, $type = null)
    {
        return parent::supports($resource, null) && $type === 'localized';
    }
}