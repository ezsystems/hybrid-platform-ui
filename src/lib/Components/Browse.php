<?php

namespace EzSystems\HybridPlatformUi\Components;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class Browse implements Component
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function getLocationId()
    {
        if ($this->request->attributes->get('location')) {
            return $this->request->attributes->get('location')->id;
        }
        return "false";
    }

    public function __toString()
    {
        $selected = $this->getLocationId();
        // should be rendered with a twig template
        return '<ez-browse
            class="ez-button"
            selected-location-id="' . $selected . '">
                Browse
            </ez-browse>';
    }

    public function jsonSerialize()
    {
        return [
            'selector' => 'ez-browse',
            'update' => [
                'attributes' => [
                    'selected-location-id' => $this->getLocationId(),
                ],
            ],
        ];
    }
}
