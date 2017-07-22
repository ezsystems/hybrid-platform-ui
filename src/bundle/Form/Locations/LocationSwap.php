<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUiBundle\Form\Locations;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class LocationSwap extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            /**
             * Could/should be a LocationSelectionType.
             * It would/could have a UniversalDiscoveryWidget appearance.
             * But in any case, it has options:
             * - single/multiple
             * - selectable types ?
             * - start location ?
             * Or this could be done from the template, with reusable elements.
             */
            ->add('new_location_id', LocationSelectionType::class, ['type' => 'single', 'widget' => 'udw'])
            ->add('swap', SubmitType::class);
    }
}
