<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Decorator;

use eZ\Publish\API\Repository\Values\ValueObject;

/**
 * Facilitate decoration of value objects by proxying all magic methods to them.
 */
abstract class ValueObjectDecorator
{
    /**
     * @return ValueObject
     */
    abstract public function getValueObject();

    public function __set($property, $value)
    {
        return $this->getValueObject()->__set($property, $value);
    }

    public function __get($property)
    {
        return $this->getValueObject()->__get($property);
    }

    public function __isset($property)
    {
        return $this->getValueObject()->__isset($property);
    }

    public function __unset($property)
    {
        return $this->getValueObject()->__unset($property);
    }
}
