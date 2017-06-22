<?php

namespace EzSystems\HybridPlatformUi\Pjax;

use EzSystems\HybridPlatformUi\Components\App;
use EzSystems\HybridPlatformUi\Components\MainContent;
use EzSystems\HybridPlatformUi\Mapper\MainContentMapper;
use Symfony\Component\HttpFoundation\Response;

/**
 * Maps a PJAX Response to the MainContent. And App.
 */
interface PjaxResponseMainContentMapper extends MainContentMapper
{
}
