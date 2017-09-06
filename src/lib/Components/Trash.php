<?php

namespace EzSystems\HybridPlatformUi\Components;

use Symfony\Component\Templating\EngineInterface;

class Trash implements Component
{
    /**
     * @var \Twig_Environment
     */
    protected $templating;

    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    public function renderToString()
    {
        return $this->templating->render('EzSystemsHybridPlatformUiBundle:components:trash.html.twig');
    }

    public function jsonSerialize()
    {
        return false;
    }
}
