<?php

namespace EzSystems\HybridPlatformUi\NavigationHub;

use Symfony\Component\HttpFoundation\Request;

abstract class Link
{
    public $zone;

    public $name;

    abstract public function match(Request $request);

    /**
     * Returns the URL for the link.
     *
     * @return string|null The URL, or null if it could not be generated.
     */
    abstract public function getUrl();

    public function renderToString()
    {
        $disabled = '';
        $url = $this->getUrl();

        if ($url === null) {
            $url = '#';
            $disabled = ' data-disabled';
        }

        return sprintf(
            '<a href="%s"%s>%s</a>',
            $url, $disabled, htmlentities($this->name, ENT_HTML5)
        );
    }
}
