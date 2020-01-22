<?php

namespace ResponseMocker\RouteLoader;

use ResponseMocker\Controller\GetController;
use ResponseMocker\Controller\MockController;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

final class MockedRouteLoader extends Loader
{
    /**
     * @var Finder
     */
    private $finder;
    /**
     * @var string
     */
    private $resourceLocation;
    /**
     * @var string
     */
    private $routePrefix;

    public function __construct(string $resourceLocation, string $routePrefix)
    {
        $this->finder = Finder::create();
        $this->resourceLocation = $resourceLocation;
        $this->routePrefix = $routePrefix;
    }

    /**
     * @inheritDoc
     */
    public function load($resource, string $type = null)
    {

        $routes = new RouteCollection();

        if (empty($this->resourceLocation)) {
            return $routes;
        }


        $files = $this->finder->in($this->resourceLocation)->files();

        /* @var SplFileInfo $file */
        foreach ($files as $file) {
            $path = sprintf('%s/%s', $this->routePrefix, $file->getRelativePath());
            $method = $file->getBasename('.json');

            $defaults = [
                '_controller' => sprintf('%s::%sAction', MockController::class, $method)
            ];

            $requirements = [];

            $route = new Route($path, $defaults, $requirements, [], null, [], [$method]);

            // add the new route to the route collection
            $routeName = $file->getRelativePath();
            $routes->add($routeName, $route);
        }

        /*
        $directories = $this->finder->in($this->resourceLocation)->directories();

        foreach ($directories as $directory) {

            $path = sprintf('%s/%s', $this->routePrefix, $directory);

            $defaults = [
                '_controller' => MockController::class . '::getAction',
                'method' => 'GET'
            ];

            $requirements = [];

            $route = new Route($path, $defaults, $requirements);

            // add the new route to the route collection
            $routeName = $directory;
            $routes->add($routeName, $route);

        }*/

        $this->isLoaded = true;

        return $routes;
    }

    /**
     * @inheritDoc
     */
    public function supports($resource, string $type = null)
    {
        return 'mock' === $type;
    }
}