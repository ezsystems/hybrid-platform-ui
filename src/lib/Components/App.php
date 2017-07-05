<?php

namespace EzSystems\HybridPlatformUi\Components;

use Symfony\Component\Templating\EngineInterface;

class App implements Component
{
    const TAG_NAME = 'ez-platform-ui-app';

    protected $mainContent;

    protected $navigationHub;

    protected $toolbars;

    protected $templating;

    protected $title;

    protected $notifications = [];

    public function __construct(
        EngineInterface $templating,
        MainContent $content,
        NavigationHub $navigationHub,
        array $toolbars
    ) {
        $this->templating = $templating;
        $this->mainContent = $content;
        $this->navigationHub = $navigationHub;
        $this->toolbars = $toolbars;
    }

    public function setConfig(array $config)
    {
        if (isset($config['title'])) {
            $this->title = $config['title'];
        }
        if (isset($config['toolbars'])) {
            $this->setToolbarsVisibility($config['toolbars']);
        }
        if (isset($config['notifications'])) {
            $this->notifications = $config['notifications'];
        }

        if (isset($config['mainContent']) && $config['mainContent'] instanceof Component) {
            $this->mainContent = $config['mainContent'];
        } elseif (isset($config['mainContent']['result'])) {
            $this->mainContent->setResult($config['mainContent']['result']);
        } elseif (isset($config['mainContent']) && is_array($config['mainContent'])) {
            $this->mainContent->setTemplate($config['mainContent']['template']);
            $this->mainContent->setParameters($config['mainContent']['parameters']);
        }
    }

    protected function setToolbarsVisibility($config)
    {
        foreach ($this->toolbars as $toolbar) {
            $toolbar->setVisible((bool)$config[$toolbar->getId()]);
        }
    }

    public function renderToString()
    {
        return $this->templating->render(
            'EzSystemsHybridPlatformUiBundle:components:app.html.twig',
            [
                'tagName' => self::TAG_NAME,
                'navigationHub' => $this->navigationHub,
                'toolbars' => $this->toolbars,
                'mainContent' => $this->mainContent,
                'appTagName' => self::TAG_NAME,
            ]
        );
    }

    public function jsonSerialize()
    {
        return [
            'selector' => self::TAG_NAME,
            'update' => [
                'properties' => [
                    'pageTitle' => $this->title,
                    'notifications' => $this->notifications,
                ],
                'children' => array_merge(
                    $this->toolbars,
                    [$this->navigationHub, $this->mainContent]
                ),
            ],
        ];
    }
}
