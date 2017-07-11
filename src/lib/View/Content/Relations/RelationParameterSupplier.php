<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\View\Content\Relations;

use eZ\Publish\Core\MVC\Symfony\View\ContentView;
use EzSystems\HybridPlatformUi\Repository\UiRelationService;
use EzSystems\HybridPlatformUi\View\Content\ParameterSupplier;

/**
 * Adds parameters needed to display the relations.
 */
class RelationParameterSupplier implements ParameterSupplier
{
    /**
     * @var UiRelationService
     */
    protected $relationService;

    public function __construct(UiRelationService $relationService)
    {
        $this->relationService = $relationService;
    }

    /**
     * {@inheritdoc}
     */
    public function supply(ContentView $contentView)
    {
        $contentView->addParameters([
            'relations' => $this->relationService->loadRelations($contentView->getContent()->getVersionInfo()),
        ]);
    }
}
