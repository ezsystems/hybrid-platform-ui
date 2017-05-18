<?php

namespace EzSystems\HybridPlatformUi\NavigationHub;

class Zone
{
    /**
     * @todo remove the name, and use i18n for the human readable string.
     */
    public $name;

    public $identifier;

    public function __construct($name, $identifier)
    {
        $this->name = $name;
        $this->identifier = $identifier;
    }
}
