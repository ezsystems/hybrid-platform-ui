<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUiBundle\Form\Locations;

use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\API\Repository\Values\Content\LocationUpdateStruct;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

class Ordering extends AbstractType
{
    /**
     * @var Translator
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'sortField',
                ChoiceType::class,
                [
                    'choices' => $this->getSortFields($options['current_sort_field']),
                    'required' => true,
                ]
            )
            ->add(
                'sortOrder',
                ChoiceType::class,
                [
                    'choices' => $this->getSortOrders(),
                    'required' => true,
                ]
            );
    }

    /**
     * @return array
     */
    protected function getSortOrders()
    {
        return [
            /** @Desc("Ascending") */
            $this->translator->trans('locationview.details.ascending', [], 'locationview') => Location::SORT_ORDER_ASC,
            /** @Desc("Descending") */
            $this->translator->trans('locationview.details.descending', [], 'locationview') => Location::SORT_ORDER_DESC,
        ];
    }

    /**
     * @param int|null $currentSortField
     * @return array
     */
    protected function getSortFields($currentSortField)
    {
        $sortFields = [
            /** @Desc("Content name") */
            $this->translator->trans('sort.name', [], 'locationview') => Location::SORT_FIELD_NAME,
            /** @Desc("Priority") */
            $this->translator->trans('sort.priority', [], 'locationview') => Location::SORT_FIELD_PRIORITY,
            /** @Desc("Modification date") */
            $this->translator->trans('sort.modified', [], 'locationview') => Location::SORT_FIELD_MODIFIED,
            /** @Desc("Publication date") */
            $this->translator->trans('sort.published', [], 'locationview') => Location::SORT_FIELD_PUBLISHED,
        ];

        $unsupportedFields = [
            /** @Desc("Location path") */
            $this->translator->trans('sort.path', [], 'locationview') => Location::SORT_FIELD_PATH,
            /** @Desc("Content type identifier") */
            $this->translator->trans('sort.content.type.identifier', [], 'locationview') => Location::SORT_FIELD_CLASS_IDENTIFIER,
            /** @Desc("Section") */
            $this->translator->trans('sort.section', [], 'locationview') => Location::SORT_FIELD_SECTION,
            /** @Desc("Location depth") */
            $this->translator->trans('sort.depth', [], 'locationview') => Location::SORT_FIELD_DEPTH,
            /** @Desc("Content type name") */
            $this->translator->trans('sort.content.type.name', [], 'locationview') => Location::SORT_FIELD_CLASS_NAME,
        ];

        if ($currentSortField) {
            $unsupportedKey = array_search($currentSortField, $unsupportedFields);
            if ($unsupportedKey) {
                $sortFields[$unsupportedKey] = $unsupportedFields[$unsupportedKey];
            }
        }

        return $sortFields;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'current_sort_field' => null,
                'data_class' => LocationUpdateStruct::class,
            ]);
    }
}
