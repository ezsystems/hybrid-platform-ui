<?php

namespace EzSystems\HybridPlatformUi\Components;

class Trash implements Component
{
    public function renderToString()
    {
        return '<button class="ez-button  ez-button-action" disabled>
            Trash
        </button>';
    }

    public function jsonSerialize()
    {
        return false;
    }
}
