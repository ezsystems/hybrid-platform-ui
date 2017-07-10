<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUiBundle\Form\Versions;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ArchivedActions extends Data
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('delete', SubmitType::class, [
                'validation_groups' => false,
            ])
            ->add('new_draft', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'constraints' => new Callback(
                ['callback' => [$this, 'validate']]
            ),
        ]);
    }

    public function validate($data, ExecutionContextInterface $context)
    {
        if (isset($data['versionIds']) && count($data['versionIds']) !== 1) {
            $context->buildViolation('Only one version can be selected')
                ->atPath('versionIds')
                ->addViolation();
        }
    }
}
