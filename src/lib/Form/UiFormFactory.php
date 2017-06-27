<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Form;

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

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * Create form to be used for draft actions on versions tab.
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createVersionsDraftActionForm()
    {
        return $this->formFactory->create(DraftActions::class);
    }

    /**
     * Create form to be used for archived actions on versions tab.
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createVersionsArchivedActionForm()
    {
        return $this->formFactory->create(ArchivedActions::class);
    }
}
