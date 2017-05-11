<?php

namespace EzSystems\HybridPlatformUi\Components;

class Toolbar implements Component
{
    protected $children;

    protected $id;

    protected $visible;

    public function __construct($id, array $children)
    {
        $this->id = $id;
        $this->children = $children;
    }

    public function __toString()
    {
        $html = '<ez-toolbar id="' . $this->id . '"' . ($this->visible ? ' visible' : '') . '>';
        foreach ($this->children as $component) {
            $html .= (string)$component;
        }
        $html .= '</ez-toolbar>';

        return $html;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setVisible($visible)
    {
        $this->visible = $visible;
    }

    public function jsonSerialize()
    {
        return [
            'selector' => '#' . $this->id,
            'update' => [
                'properties' => [
                    'visible' => $this->visible,
                ],
                'children' => $this->children,
            ],
        ];
    }
}
