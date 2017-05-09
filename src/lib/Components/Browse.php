<?php

namespace EzSystems\HybridPlatformUi\Components;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class Browse implements Component
{
    protected $urlGenerator;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    // passing the router is needed because at the moment the UDW only supports
    // a REST Location id as the starting Location id, so this id is build there
    public function __construct(Request $request, UrlGeneratorInterface $urlGenerator)
    {
        $this->request = $request;
        $this->urlGenerator = $urlGenerator;
    }

    protected function getLocationId()
    {
        if ($this->request->attributes->get('location')) {
            return $this->request->attributes->get('location')->id;
        }
        return "false";
    }

    protected function getLocationRestId()
    {
        if ($this->request->attributes->get('location')) {
            return $this->urlGenerator->generate(
                'ezpublish_rest_loadLocation',
                ['locationPath' => trim($this->request->attributes->get('location')->pathString, '/')]
            );
        }
        return "false";
    }

    public function __toString()
    {
        $selected = $this->getLocationRestId();
        $id = $this->getLocationId();
        // could be rendered with a twig template
        return '<ez-browse selected-location-id="' . $selected . '" location-id="' . $id . '">Browse</ez-browse>';
    }

    public function jsonSerialize()
    {
        return [
            'selector' => 'ez-browse',
            'update' => [
                'attributes' => [
                    'selected-location-id' => $this->getLocationRestId(),
                    'location-id' => $this->getLocationId(),
                ],
            ],
        ];
    }
}
