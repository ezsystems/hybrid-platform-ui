<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUiBundle\Form\Locations;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class Actions extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('delete', SubmitType::class)
            ->add('add', SubmitType::class)
            ->add('parentLocationId', TextType::class, ['required' => false])
            ->add(
                'locationVisibility',
                CollectionType::class,
                [
                    'entry_type' => CheckboxType::class,
                    'required' => false,
                    'allow_add' => true,
                ]
            )
            ->add(
                'removeLocations',
                CollectionType::class,
                [
                    'entry_type' => CheckboxType::class,
                    'required' => false,
                    'allow_add' => true,
                ]
            );
    }
}
