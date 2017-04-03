<?php

namespace EzSystems\HybridPlatformUi\Components;

use Symfony\Component\HttpFoundation\Request;
use EzSystems\HybridPlatformUi\Components\Component;
use EzSystems\HybridPlatformUi\Components\NavigationHub;
use EzSystems\HybridPlatformUi\Components\MainContent;

/**
 * Could this be BOTH a component and a Subscriber ?
 * It would be harder to test...
 * But does it have much that needs to be tested ?
 * Does it really help ? It would meant that the app subscribes to the MVC layer and
 * takes care of rendering the result. It may make some sense.
 */
class App implements Component
{
    const TAG_NAME = 'ez-platformui-app';

    protected $mainContent;

    protected $navigationHub;

    protected $toolbars;

    protected $templating;

    protected $title;

    public function __construct(
        $templating,
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

        if (isset($config['mainContent']) && $config['mainContent'] instanceof Component) {
            $this->mainContent = $config['mainContent'];
        } elseif (isset($config['mainContent']['result'])) {
            $this->mainContent->setResult($config['mainContent']['result']);
        } elseif (is_array($config['mainContent'])) {
            $this->mainContent->setTemplate($config['mainContent']['template']);
            $this->mainContent->setParameters($config['mainContent']['parameters']);
        }
    }

    public function getTitle()
    {
        return $this->title;
    }

    protected function setToolbarsVisibility($config)
    {
        foreach ($this->toolbars as $toolbar) {
            $toolbar->setVisible((bool)$config[$toolbar->getId()]);
        }
    }

    public function __toString()
    {
        return $this->templating->render(
            'eZPlatformUIBundle:Components:app.html.twig',
            [
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
                'attributes' => [
                    'title' => $this->title,
                    'url' => $_SERVER['REQUEST_URI'],
                ],
                'children' => array_merge(
                    $this->toolbars,
                    [$this->navigationHub, $this->mainContent]
                ),
            ]
        ];
    }
}
