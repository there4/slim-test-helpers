<?php

namespace There4\Slim\Test;

/**
 * This router acts as a middle man requesting flushing the underlying
 * matching route cache on every request.
 *
 * Deprecated in Slim V3
 */
class NoCacheRouter extends \Slim\Router
{
    /**
     * @codeCoverageIgnore
     */
    public function __construct($originalRouter)
    {
        parent::__construct();

        $this->routes      = $originalRouter->routes;
        $this->namedRoutes = $originalRouter->namedRoutes;
        $this->routeGroups = $originalRouter->routeGroups;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getMatchedRoutes($httpMethod, $resourceUri, $reload = false)
    {
        // Force a reload of all matched routes
        return parent::getMatchedRoutes($httpMethod, $resourceUri, true);
    }
}
