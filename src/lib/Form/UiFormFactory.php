<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Form;

use EzSystems\HybridPlatformUi\Mapper\Form\VersionMapper;
use EzSystems\HybridPlatformUiBundle\Form\Versions\ArchivedActions;
use EzSystems\HybridPlatformUiBundle\Form\Versions\DraftActions;
use EzSystems\HybridPlatformUiBundle\Form\Versions\LocationSwap;
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

    public function __construct(FormFactoryInterface $formFactory, VersionMapper $versionMapper)
    {
        $this->formFactory = $formFactory;
        $this->versionMapper = $versionMapper;
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
     * Create a form to be used for swapping contents location.
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createLocationsContentSwapForm()
    {
        return $this->formFactory->create(LocationSwap::class);
    }
}
