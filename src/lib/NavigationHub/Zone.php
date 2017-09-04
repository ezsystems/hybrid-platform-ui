<?php

namespace EzSystems\HybridPlatformUi\NavigationHub;

class Zone
{
    /**
     * @todo remove the name, and use i18n for the human readable string.
     */
    public $name;

    public $identifier;

    /**
     * The icon identifier in the icon sprite.
     *
     * @var string
     */
    public $icon;

    public function __construct($name, $identifier, $icon)
    {
        $this->name = $name;
        $this->identifier = $identifier;
        $this->icon = $icon;
    }
}
