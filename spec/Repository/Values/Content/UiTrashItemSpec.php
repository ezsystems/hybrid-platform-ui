<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace spec\EzSystems\HybridPlatformUi\Repository\Values\Content;

use eZ\Publish\Core\Repository\Values\Content\Location;
use eZ\Publish\Core\Repository\Values\Content\TrashItem;
use eZ\Publish\Core\Repository\Values\ContentType\ContentType;
use EzSystems\HybridPlatformUi\Repository\Values\Content\UiTrashItem;
use PhpSpec\ObjectBehavior;

class UiTrashItemSpec extends ObjectBehavior
{
    private $trashItem;

    private $expectedContentType;

    private $expectedPath;

    function let()
    {
        $this->trashItem = new TrashItem([
            'parentLocationId' => 333,
        ]);
        $this->expectedContentType = new ContentType();
        $this->expectedPath = [
            new Location(['id' => 222]),
        ];

        $this->beConstructedWith(
            $this->trashItem,
            [
                'locationPath' => $this->expectedPath,
                'contentType' => $this->expectedContentType,
            ]
        );
    }

    private function constructParentDeleted()
    {
        $this->beConstructedWith(
            $this->trashItem,
            [
                'locationPath' => $this->expectedPath,
                'contentType' => $this->expectedContentType,
            ]
        );
    }

    function it_is_initializable()
    {
        $this->constructParentDeleted();
        $this->shouldHaveType(UiTrashItem::class);
    }

    function it_has_a_path()
    {
        $this->constructParentDeleted();
        $this->locationPath->shouldReturn($this->expectedPath);
    }

    function it_has_a_contenttype()
    {
        $this->constructParentDeleted();
        $this->contentType->shouldReturn($this->expectedContentType);
    }

    function it_tells_if_the_parent_is_deleted()
    {
        $this->constructParentDeleted();
        $this->isParentDeleted()->shouldReturn(true);
    }

    function it_tells_if_the_parent_is_not_deleted()
    {
        $locationPath = array_merge($this->expectedPath, [new Location(['id' => 333])]);

        $this->beConstructedWith(
            $this->trashItem,
            [
                'locationPath' => $locationPath,
                'contentType' => $this->expectedContentType,
            ]
        );

        $this->isParentDeleted()->shouldReturn(false);
    }
}
