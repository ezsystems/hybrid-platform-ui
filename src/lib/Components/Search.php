<?php

namespace EzSystems\HybridPlatformUi\Components;

class Search implements Component
{
    public function __toString()
    {
        return '<button class="ez-button" disabled>Search</button>';
    }

    public function jsonSerialize()
    {
        return false;
    }
}
