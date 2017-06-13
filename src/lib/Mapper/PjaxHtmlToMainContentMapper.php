<?php

namespace EzSystems\HybridPlatformUi\Mapper;

use EzSystems\HybridPlatformUi\Components\App;
use EzSystems\HybridPlatformUi\Components\MainContent;

/**
 * Maps a PJAX Response to the MainContent. And App.
 */
class PjaxHtmlToMainContentMapper extends HtmlToMainContentMapper
{
    /**
     * @var \EzSystems\HybridPlatformUi\Components\App
     */
    private $app;

    /**
     * @var \EzSystems\HybridPlatformUi\Components\MainContent
     */
    private $mainContent;

    public function __construct(App $app, MainContent $mainContent)
    {
        $this->app = $app;
        $this->mainContent = $mainContent;
    }

    protected function getBodyXpath()
    {
        return '//div[@data-name="html"]';
    }

    protected function getTitleXpath()
    {
        return '//div[@data-name="title"]';
    }

    protected function setBody($body)
    {
        $this->mainContent->setResult($body);
    }

    protected function setTitle($title)
    {
        $this->app->setConfig(['title' => $title]);
    }
}
