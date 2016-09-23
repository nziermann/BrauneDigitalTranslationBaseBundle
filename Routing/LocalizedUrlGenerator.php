<?php

namespace BrauneDigital\TranslationBaseBundle\Routing;

use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RouteCollection;

class LocalizedUrlGenerator extends UrlGenerator {

    protected $locales;
    protected $defaultLocale;
    protected $localeKey = '_locale';

    public function __construct($logger, $locales = array('en'), $defaultLocale = 'en') {
        $this->logger = $logger;
        $this->locales = $locales;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * @throws MissingMandatoryParametersException When some parameters are missing that are mandatory for the route
     * @throws InvalidParameterException           When a parameter value for a placeholder is not correct because
     *                                             it does not match the requirement
     */
    protected function doGenerate($variables, $defaults, $requirements, $tokens, $parameters, $name, $referenceType, $hostTokens, array $requiredSchemes = array())
    {
        if (is_bool($referenceType) || is_string($referenceType)) {
            @trigger_error('The hardcoded value you are using for the $referenceType argument of the '.__CLASS__.'::generate method is deprecated since version 2.8 and will not be supported anymore in 3.0. Use the constants defined in the UrlGeneratorInterface instead.', E_USER_DEPRECATED);

            if (true === $referenceType) {
                $referenceType = self::ABSOLUTE_URL;
            } elseif (false === $referenceType) {
                $referenceType = self::ABSOLUTE_PATH;
            } elseif ('relative' === $referenceType) {
                $referenceType = self::RELATIVE_PATH;
            } elseif ('network' === $referenceType) {
                $referenceType = self::NETWORK_PATH;
            }
        }

        $variables = array_flip($variables);
        $mergedParams = array_replace($defaults, $this->context->getParameters(), $parameters);

        // all params must be given
        if ($diff = array_diff_key($variables, $mergedParams)) {
            throw new MissingMandatoryParametersException(sprintf('Some mandatory parameters are missing ("%s") to generate a URL for route "%s".', implode('", "', array_keys($diff)), $name));
        }

        $url = '';
        $optional = true;
        foreach ($tokens as $token) {
            if ('variable' === $token[0]) {
                if (!$optional || !array_key_exists($token[3], $defaults) || null !== $mergedParams[$token[3]] && (string) $mergedParams[$token[3]] !== (string) $defaults[$token[3]]) {
                    // check requirement
                    if (null !== $this->strictRequirements && !preg_match('#^'.$token[2].'$#', $mergedParams[$token[3]])) {
                        $message = sprintf('Parameter "%s" for route "%s" must match "%s" ("%s" given) to generate a corresponding URL.', $token[3], $name, $token[2], $mergedParams[$token[3]]);
                        if ($this->strictRequirements) {
                            throw new InvalidParameterException($message);
                        }

                        if ($this->logger) {
                            $this->logger->error($message);
                        }

                        return;
                    }

                    if($token[3] !== $this->localeKey || (string) $mergedParams[$token[3]] != $this->defaultLocale) {
                        $url = $token[1].$mergedParams[$token[3]].$url;
                        $optional = false;
                    }
                }
            } else {
                // static text
                $url = $token[1].$url;
                $optional = false;
            }
        }

        if ('' === $url) {
            $url = '/';
        }

        // the contexts base URL is already encoded (see Symfony\Component\HttpFoundation\Request)
        $url = strtr(rawurlencode($url), $this->decodedChars);

        // the path segments "." and ".." are interpreted as relative reference when resolving a URI; see http://tools.ietf.org/html/rfc3986#section-3.3
        // so we need to encode them as they are not used for this purpose here
        // otherwise we would generate a URI that, when followed by a user agent (e.g. browser), does not match this route
        $url = strtr($url, array('/../' => '/%2E%2E/', '/./' => '/%2E/'));
        if ('/..' === substr($url, -3)) {
            $url = substr($url, 0, -2).'%2E%2E';
        } elseif ('/.' === substr($url, -2)) {
            $url = substr($url, 0, -1).'%2E';
        }

        $schemeAuthority = '';
        if ($host = $this->context->getHost()) {
            $scheme = $this->context->getScheme();

            if ($requiredSchemes) {
                $schemeMatched = false;
                foreach ($requiredSchemes as $requiredScheme) {
                    if ($scheme === $requiredScheme) {
                        $schemeMatched = true;

                        break;
                    }
                }

                if (!$schemeMatched) {
                    $referenceType = self::ABSOLUTE_URL;
                    $scheme = current($requiredSchemes);
                }
            } elseif (isset($requirements['_scheme']) && ($req = strtolower($requirements['_scheme'])) && $scheme !== $req) {
                // We do this for BC; to be removed if _scheme is not supported anymore
                $referenceType = self::ABSOLUTE_URL;
                $scheme = $req;
            }

            if ($hostTokens) {
                $routeHost = '';
                foreach ($hostTokens as $token) {
                    if ('variable' === $token[0]) {
                        if (null !== $this->strictRequirements && !preg_match('#^'.$token[2].'$#i', $mergedParams[$token[3]])) {
                            $message = sprintf('Parameter "%s" for route "%s" must match "%s" ("%s" given) to generate a corresponding URL.', $token[3], $name, $token[2], $mergedParams[$token[3]]);

                            if ($this->strictRequirements) {
                                throw new InvalidParameterException($message);
                            }

                            if ($this->logger) {
                                $this->logger->error($message);
                            }

                            return;
                        }

                        $routeHost = $token[1].$mergedParams[$token[3]].$routeHost;
                    } else {
                        $routeHost = $token[1].$routeHost;
                    }
                }

                if ($routeHost !== $host) {
                    $host = $routeHost;
                    if (self::ABSOLUTE_URL !== $referenceType) {
                        $referenceType = self::NETWORK_PATH;
                    }
                }
            }

            if (self::ABSOLUTE_URL === $referenceType || self::NETWORK_PATH === $referenceType) {
                $port = '';
                if ('http' === $scheme && 80 != $this->context->getHttpPort()) {
                    $port = ':'.$this->context->getHttpPort();
                } elseif ('https' === $scheme && 443 != $this->context->getHttpsPort()) {
                    $port = ':'.$this->context->getHttpsPort();
                }

                $schemeAuthority = self::NETWORK_PATH === $referenceType ? '//' : "$scheme://";
                $schemeAuthority .= $host.$port;
            }
        }

        if (self::RELATIVE_PATH === $referenceType) {
            $url = self::getRelativePath($this->context->getPathInfo(), $url);
        } else {
            $url = $schemeAuthority.$this->context->getBaseUrl().$url;
        }

        // add a query string if needed
        $extra = array_diff_key($parameters, $variables, $defaults);
        if ($extra && $query = http_build_query($extra, '', '&')) {
            // "/" and "?" can be left decoded for better user experience, see
            // http://tools.ietf.org/html/rfc3986#section-3.4
            $url .= '?'.strtr($query, array('%2F' => '/'));
        }

        return $url;
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