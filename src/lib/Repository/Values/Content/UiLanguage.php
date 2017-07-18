<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Repository\Values\Content;

use eZ\Publish\API\Repository\Values\Content\Language;

/**
 * Extends original value object in order to provide additional fields.
 * Takes a standard language instance and retrieves properties from it in addition to the provided properties.
 *
 * @property-read bool $main
 * @property-read bool $userCanRemove
 */
class UiLanguage extends Language
{
    /**
     * Is main language.
     *
     * @var bool
     */
    protected $main;

    /**
     * User can remove.
     *
     * @var bool
     */
    protected $userCanRemove;

    public function __construct(Language $language, array $properties = [])
    {
        parent::__construct(get_object_vars($language) + $properties);
    }

    /**
     * Can delete translation.
     *
     * @return bool
     */
    public function canDelete()
    {
        return !$this->main && $this->userCanRemove;
    }
}
