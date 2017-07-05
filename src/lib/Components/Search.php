<?php

namespace EzSystems\HybridPlatformUi\Components;

class Search implements Component
{
    function __toString()
    {
        return $this->renderToString();
    }

    public function renderToString()
    {
        return '<button class="ez-button" disabled>Search</button>';
    }

    public function jsonSerialize()
    {
        return false;
    }
}
