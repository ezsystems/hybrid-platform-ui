<?php

namespace EzSystems\HybridPlatformUi\Components;

use Symfony\Component\Templating\EngineInterface;

class MainContent implements Component
{
    protected $templating;

    protected $template = null;

    protected $parameters = [];

    protected $result = false;

    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    public function setTemplate($template)
    {
        $this->template = $template;
    }

    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }

    public function setResult($result)
    {
        $this->result = $result;
    }

    public function __toString()
    {
        $string = '';
        if ($this->result) {
            $string = $this->result;
        } elseif ($this->template) {
            $string = $this->templating->render(
                $this->template,
                $this->parameters
            );
        }

        return $string;
    }

    public function jsonSerialize()
    {
        return [
            'selector' => 'main',
            'update' => (string)$this,
        ];
    }
}
