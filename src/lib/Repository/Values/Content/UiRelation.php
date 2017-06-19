<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Repository\Values\Content;

use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\Core\Repository\Values\Content\Relation;
use eZ\Publish\API\Repository\Values\Content\Relation as APIRelation;

/**
 * Extends original value object in order to provide additional fields.
 */
class UiRelation extends Relation
{
    /**
     * Field definition.
     *
     * @var string
     */
    protected $fieldDefinition;

    /**
     * The content type for the destination.
     *
     * @var string
     */
    protected $destinationContentType;

    /**
     * The location for the destination.
     *
     * @var Location
     */
    protected $destinationLocation;

    public function __construct(APIRelation $relation, array $properties = [])
    {
        parent::__construct(get_object_vars($relation) + $properties);
    }
}
