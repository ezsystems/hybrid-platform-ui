<?php

namespace EzSystems\HybridPlatformUi\Dashboard;

interface SectionInterface
{
    /**
     * @return string
     */
    public function render(): string;
}
