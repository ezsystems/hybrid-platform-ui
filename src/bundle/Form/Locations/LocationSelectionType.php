<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUiBundle\Form\Locations;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationSelectionType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'type' => 'single',
            'starting_location_id' => 2,
            'include_content_types' => [],
            'exclude_content_types' => [],
            'widget' => 'text',
        ]);
        $resolver->setAllowedValues('type', ['single', 'multiple']);
        $resolver->setAllowedValues('widget', ['text', 'udw']);
        $resolver->setAllowedTypes('starting_location_id', 'int');
    }

    public function getParent()
    {
        return NumberType::class;
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['widget'] = $options['widget'];
        $view->vars['starting_location_id'] = $options['starting_location_id'];
    }
}
