<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Form;

use eZ\Publish\API\Repository\Values\Content\Language;
use eZ\Publish\API\Repository\Values\Content\Location;
use EzSystems\HybridPlatformUi\Mapper\Form\Location\OrderingMapper;
use EzSystems\HybridPlatformUi\Mapper\Form\LocationMapper;
use EzSystems\HybridPlatformUi\Mapper\Form\TranslationMapper;
use EzSystems\HybridPlatformUi\Mapper\Form\VersionMapper;
use EzSystems\HybridPlatformUiBundle\Form\Locations\LocationSwap;
use EzSystems\HybridPlatformUiBundle\Form\Locations\Ordering;
use EzSystems\HybridPlatformUiBundle\Form\Locations\Actions as LocationActions;
use EzSystems\HybridPlatformUiBundle\Form\Translations\Actions as TranslationActions;
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

    /**
     * @var OrderingMapper
     */
    private $orderingMapper;

    /**
     * @var TranslationMapper
     */
    private $translationMapper;

    public function __construct(
        FormFactoryInterface $formFactory,
        VersionMapper $versionMapper,
        LocationMapper $locationMapper,
        OrderingMapper $orderingMapper,
        TranslationMapper $translationMapper
    ) {
        $this->formFactory = $formFactory;
        $this->versionMapper = $versionMapper;
        $this->locationMapper = $locationMapper;
        $this->orderingMapper = $orderingMapper;
        $this->translationMapper = $translationMapper;
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
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createLocationsActionForm(array $locations = [])
    {
        $data = $this->locationMapper->mapToForm($locations);

        return $this->formFactory->create(LocationActions::class, $data);
    }

    /**
     * Create a form to be used for swapping contents location.
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createLocationsContentSwapForm()
    {
        return $this->formFactory->create(LocationSwap::class);
    }

    /**
     * Create form to be used for ordering of locations.
     *
     * @param Location $location
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createLocationOrderingForm(Location $location)
    {
        return $this->formFactory->create(
            Ordering::class,
            $this->orderingMapper->mapToForm($location),
            ['current_sort_field' => $location->sortField]
        );
    }

    /**
     * Create form to be used for actions on translations tab.
     *
     * @param Language[] $translations
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createTranslationsActionForm(array $translations = [])
    {
        $data = $this->translationMapper->mapToForm($translations);

        return $this->formFactory->create(TranslationActions::class, $data);
    }
}
