<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\View\Content\Relations;

use eZ\Publish\Core\MVC\Symfony\View\ContentView;
use EzSystems\HybridPlatformUi\Repository\Permission\UiPermissionResolver;
use EzSystems\HybridPlatformUi\Repository\UiRelationService;
use EzSystems\HybridPlatformUi\View\Content\ParameterSupplier;

/**
 * Adds parameters needed to display the reverse relations.
 */
class ReverseRelationParameterSupplier implements ParameterSupplier
{
    /**
     * @var UiRelationService
     */
    private $relationService;

    /**
     * @var UiPermissionResolver
     */
    private $permissionResolver;

    public function __construct(UiRelationService $relationService, UiPermissionResolver $permissionResolver)
    {
        $this->relationService = $relationService;
        $this->permissionResolver = $permissionResolver;
    }

    /**
     * Adds reverse relations to the view if user has the correct permissions.
     *
     * {@inheritdoc}
     */
    public function supply(ContentView $contentView)
    {
        if (!$this->permissionResolver->canAccessReverseRelations()) {
            return;
        }

        $contentView->addParameters([
            'reverseRelations' => $this->relationService->loadReverseRelations($contentView->getContent()->contentInfo),
        ]);
    }
}
