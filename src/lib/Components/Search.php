<?php

namespace EzSystems\HybridPlatformUi\Components;

use Symfony\Component\Templating\EngineInterface;

class Search implements Component
{
    /**
     * @var \Symfony\Component\Templating\EngineInterface
     */
    protected $templating;

    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    public function renderToString()
    {
        return $this->templating->render(
            'EzSystemsHybridPlatformUiBundle:components:search.html.twig'
        );
    }

    public function jsonSerialize()
    {
        return false;
    }
}
