<?php

namespace EzSystems\HybridPlatformUi\Components;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Templating\EngineInterface;

class NavigationHub implements Component
{
    const TAG_NAME = 'ez-navigation-hub';

    const ACTIVE_ZONE_CLASS = 'is-active-zone';

    const MATCHED_LINK_CLASS = 'is-matched-link';

    /**
     * @var \Twig_Environment
     */
    protected $templating;

    /**
     * @var \EzSystems\HybridPlatformUi\NavigationHub\Zone[]
     */
    protected $zones;

    /**
     * @var \EzSystems\HybridPlatformUi\NavigationHub\Link[]
     */
    protected $links;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    public function __construct(EngineInterface $templating, Request $request, array $zones = [], array $links = [])
    {
        $this->request = $request;
        $this->templating = $templating;
        $this->zones = $zones;
        $this->links = $links;
    }

    function __toString()
    {
        return $this->renderToString();
    }

    public function renderToString()
    {
        return $this->templating->render(
            'EzSystemsHybridPlatformUiBundle:components:navigationhub.html.twig',
            [
                'tag' => self::TAG_NAME,
                'attributes' => $this->getAttributes(),
                'zones' => $this->zones,
                'links' => $this->links,
                'activeZone' => $this->getActiveZoneIdentifier(),
                'activeZoneClass' => self::ACTIVE_ZONE_CLASS,
                'matchedLinkClass' => self::MATCHED_LINK_CLASS,
            ]
        );
    }

    protected function getActiveLink()
    {
        foreach ($this->links as $link) {
            if ($link->match($this->request)) {
                return $link;
            }
        }

        return null;
    }

    protected function getActiveZoneIdentifier()
    {
        $link = $this->getActiveLink();
        if ($link) {
            return $link->zone;
        }

        return '';
    }

    protected function getActiveLinkUrl()
    {
        $link = $this->getActiveLink();

        if ($link) {
            return $link->getUrl();
        }

        return '';
    }

    protected function getAttributes()
    {
        return [
            'active-zone-class' => self::ACTIVE_ZONE_CLASS,
            'matched-link-class' => self::MATCHED_LINK_CLASS,
            'active-zone' => $this->getActiveZoneIdentifier(),
            'matched-link-url' => $this->getActiveLinkUrl(),
        ];
    }

    public function jsonSerialize()
    {
        return [
            'selector' => self::TAG_NAME,
            'update' => [
                'attributes' => $this->getAttributes(),
            ],
        ];
    }
}
