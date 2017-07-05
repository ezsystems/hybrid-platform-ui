<?php

namespace EzSystems\HybridPlatformUi\Components;

interface Component extends \JsonSerializable
{
    function renderToString();
}
