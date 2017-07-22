<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUiBundle\Form\Locations;

use EzSystems\HybridPlatformUi\Form\PermissionFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class Actions extends AbstractType
{
    /**
     * @var \EzSystems\HybridPlatformUi\Form\PermissionFilter
     */
    private $permissionFilter;

    public function __construct(PermissionFilter $permissionFilter)
    {
        $this->permissionFilter = $permissionFilter;
    }

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

        $builder
            ->get('removeLocations')
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                [$this->permissionFilter, 'applyPermissions']
            );
    }

    private function applyPermissions()
    {
        return function (FormEvent $event) {
            $form = $event->getForm();
            $data = $form->getParent()->getData();
            if (!isset($data['canRemoveLocations'])) {
                return;
            }

            $canRemoveLocations = $data['canRemoveLocations'];
            foreach ($form as $name => $checkbox)
            {
                $options = $checkbox->getConfig()->getOptions();
                if ($canRemoveLocations[$name] === false) {
                    $options['disabled'] = true;
                }
                $form->add($name, CheckboxType::class, $options);
            }
        };
    }
}
