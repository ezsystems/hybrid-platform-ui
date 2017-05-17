<?php
namespace EzSystems\HybridPlatformUi\NavigationHub\Link;

use EzSystems\HybridPlatformUi\NavigationHub\Link;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Route extends Link
{
    /**
     * @var \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    protected $urlGenerator;

    /**
     * @var string
     */
    protected $routeName;

    /**
     * @var array
     */
    protected $routeParams;

    public $zone;

    public $name;

    /**
     * An optional route prefix used to match routes.
     * @var string
     */
    protected $routePrefix;

    public function __construct(UrlGeneratorInterface $urlGenerator, $routeName, $name, $zone, array $routeParams = [])
    {
        $this->urlGenerator = $urlGenerator;
        $this->zone = $zone;
        $this->name = $name;
        $this->routeName = $routeName;
        $this->routeParams = $routeParams;
    }

    public function getUrl()
    {
        return $this->urlGenerator->generate($this->routeName, $this->routeParams);
    }

    public function match(Request $request)
    {
        $routeName = $request->attributes->get('_route');
        $routeParams = $request->attributes->get('_routeParams', []);

        return (
            ($this->matchRoute($routeName) && $this->matchRouteParams($routeParams))
            || $this->matchRoutePrefix($routeName)
        );
    }

    protected function matchRoute($routeName)
    {
        return $routeName === $this->routeName;
    }

    protected function matchRouteParams($params)
    {
        if (count($params) !== count($this->routeParams)) {
            return false;
        }
        foreach ($params as $name => $value) {
            if (!isset($this->routeParams[$name]) || $this->routeParams[$name] !== $value) {
                return false;
            }
        }
        return true;
    }

    /**
     * Matches $routeName against the configured route prefix, if any.
     *
     * @param string $routeName
     *
     * @return bool
     */
    protected function matchRoutePrefix($routeName)
    {
        return $this->routePrefix !== null && strpos($routeName, $this->routePrefix) === 0;
    }

    /**
     * @param mixed $routePrefix
     */
    public function setRoutePrefix($routePrefix)
    {
        $this->routePrefix = $routePrefix;
    }
}
