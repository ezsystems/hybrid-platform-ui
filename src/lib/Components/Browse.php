<?php

namespace EzSystems\HybridPlatformUi\Components;

use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;

class Browse implements Component
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var \Symfony\Component\Templating\EngineInterface
     */
    protected $templating;

    public function __construct(EngineInterface $templating, Request $request = null)
    {
        $this->templating = $templating;
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
        return $this->templating->render(
            'EzSystemsHybridPlatformUiBundle:components:browse.html.twig',
            ['selectedLocationId' => $this->getLocationId()]
        );
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
