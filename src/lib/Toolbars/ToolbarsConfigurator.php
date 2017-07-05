<?php

namespace EzSystems\HybridPlatformUi\Toolbars;

use Symfony\Component\HttpFoundation\Request;

interface ToolbarsConfigurator
{
    public function fromRequest(Request $rest);
}
