<?php

namespace EzSystems\HybridPlatformUi\Components;

use Symfony\Component\HttpFoundation\Request;

class Browse implements Component
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    public function __construct(Request $request = null)
    {
        $this->request = $request;
    }

    protected function getLocationId()
    {
        if ($this->request->attributes->get('location')) {
            return $this->request->attributes->get('location')->id;
        }

        return null;
    }

    public function renderToString()
    {
        $selected = $this->getLocationId();
        // should be rendered with a twig template
        return '<ez-browse
            class="ez-button"
            ' . ($selected ? 'selected-location-id="' . $selected . '"' : '') . '>
                Browse
            </ez-browse>';
    }

    public function jsonSerialize()
    {
        return [
            'selector' => 'ez-browse',
            'update' => [
                'properties' => [
                    'selectedLocationId' => $this->getLocationId(),
                ],
            ],
        ];
    }
}
