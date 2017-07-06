<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Form;

use eZ\Publish\API\Repository\Values\Content\Location;
use EzSystems\HybridPlatformUi\Mapper\Form\LocationMapper;
use EzSystems\HybridPlatformUi\Mapper\Form\VersionMapper;
use EzSystems\HybridPlatformUiBundle\Form\Locations\Actions as LocationActions;
use EzSystems\HybridPlatformUiBundle\Form\Locations\Visibility;
use EzSystems\HybridPlatformUiBundle\Form\Versions\ArchivedActions;
use EzSystems\HybridPlatformUiBundle\Form\Versions\DraftActions;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Factory to enable the creation of forms.
 */
class UiFormFactory
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var VersionMapper
     */
    private $versionMapper;

    /**
     * @var LocationMapper
     */
    private $locationMapper;

    public function __construct(
        FormFactoryInterface $formFactory,
        VersionMapper $versionMapper,
        LocationMapper $locationMapper
    ) {
        $this->formFactory = $formFactory;
        $this->versionMapper = $versionMapper;
        $this->locationMapper = $locationMapper;
    }

    /**
     * Create form to be used for draft actions on versions tab.
     *
     * @param array $versions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createVersionsDraftActionForm(array $versions = [])
    {
        $data = $this->versionMapper->mapToForm($versions);

        return $this->formFactory->create(DraftActions::class, $data);
    }

    /**
     * Create form to be used for archived actions on versions tab.
     *
     * @param array $versions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createVersionsArchivedActionForm(array $versions = [])
    {
        $data = $this->versionMapper->mapToForm($versions);

        return $this->formFactory->create(ArchivedActions::class, $data);
    }

    /**
     * Create form to be used for actions on locations tab.
     *
     * @param Location[] $locations
     */
    public function createLocationsActionForm(array $locations = [])
    {
        $data = $this->locationMapper->mapToForm($locations);

        return $this->formFactory->create(LocationActions::class, $data);
    }

    public function createLocationVisibilityForm()
    {
        return $this->formFactory->create(Visibility::class);
    }
}
