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
     * @var string
     */
    protected $routeParams;

    public function __construct(UrlGeneratorInterface $urlGenerator, $routeName, $routeParams, $name, $zoneIdentifier)
    {
        $this->urlGenerator = $urlGenerator;
        $this->zone = $zoneIdentifier;
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
            $this->matchRoute($routeName)
            && $this->matchRouteParams($routeParams)
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
}
