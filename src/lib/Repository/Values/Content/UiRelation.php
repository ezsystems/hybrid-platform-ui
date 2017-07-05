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
     * Field definition name for the relation.
     * This will either come from destinationContentInfo OR sourceContentInfo depending upon if reverse relation or normal relation.
     *
     * @var string
     */
    protected $relationFieldDefinitionName;

    /**
     * The content type name for the relation.
     * This will either come from destinationContentInfo OR sourceContentInfo depending upon if reverse relation or normal relation.
     *
     * @var string
     */
    protected $relationContentTypeName;

    /**
     * Main location for the relation.
     * This will either come from destinationContentInfo OR sourceContentInfo depending upon if reverse relation or normal relation.
     *
     * @var Location
     */
    protected $relationLocation;

    /**
     * The name for the relation.
     * This will either come from destinationContentInfo OR sourceContentInfo depending upon if reverse relation or normal relation.
     *
     * @var Location
     */
    protected $relationName;

    public function __construct(APIRelation $relation, array $properties = [])
    {
        parent::__construct(get_object_vars($relation) + $properties);
    }
}
