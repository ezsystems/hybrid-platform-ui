<?php

namespace EzSystems\HybridPlatformUi\Components;

interface Component extends \JsonSerializable
{
    public function __toString();

    function renderToString();
}
