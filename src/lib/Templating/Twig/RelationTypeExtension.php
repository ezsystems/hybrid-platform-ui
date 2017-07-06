<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\HybridPlatformUi\Templating\Twig;

use eZ\Publish\API\Repository\Values\Content\Relation;
use JMS\TranslationBundle\Annotation\Desc;
use Symfony\Component\Translation\TranslatorInterface;
use Twig_Extension;
use Twig_SimpleFunction;

/**
 * Twig extension for displaying the relation type based on a relation.
 */
class RelationTypeExtension extends Twig_Extension
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function getName()
    {
        return 'ezpublish.relation_type';
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction(
                'ez_ui_relation_type',
                [$this, 'renderRelationType']
            ),
        ];
    }

    /**
     * Returns the translated relation type.
     *
     * @param Relation $relation
     *
     * @return string
     */
    public function renderRelationType(Relation $relation)
    {
        $domain = 'locationview';

        switch ($relation->type) {
            case Relation::COMMON:
                return /** @Desc("Content level relation") */
                    $this->translator->trans('locationview.relations.type.content_level_relation', [], $domain);
            case Relation::EMBED:
                return /** @Desc("Embed") */
                    $this->translator->trans('locationview.relations.type.embed', [], $domain);
            case Relation::LINK:
                return /** @Desc("Link") */
                    $this->translator->trans('locationview.relations.type.link', [], $domain);
            case Relation::FIELD:
                return /** @Desc("Field") */
                    $this->translator->trans('locationview.relations.type.field', [], $domain);
            default:
                return '';
        }
    }
}
