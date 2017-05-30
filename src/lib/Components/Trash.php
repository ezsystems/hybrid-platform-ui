<?php

namespace EzSystems\HybridPlatformUi\Components;

class Trash implements Component
{
    public function __toString()
    {
        return '<button class="ez-button" disabled>
            Trash
        </button>';
    }

    public function jsonSerialize()
    {
        return false;
    }
}
