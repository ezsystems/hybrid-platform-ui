<?php

namespace spec\EzSystems\HybridPlatformUi\Repository;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\API\Repository\Values\Content\Language;
use eZ\Publish\Core\Repository\Values\User\User;
use eZ\Publish\Core\Repository\Values\Content\VersionInfo;
use EzSystems\HybridPlatformUi\Repository\Permission\UiPermissionResolver;
use EzSystems\HybridPlatformUi\Repository\UiTranslationService;
use EzSystems\HybridPlatformUi\Repository\UiUserService;
use EzSystems\HybridPlatformUi\Repository\Values\Content\UiVersionInfo;
use PhpSpec\ObjectBehavior;

class UiVersionServiceSpec extends ObjectBehavior
{
    function let(
        ContentService $contentService,
        UiUserService $userService,
        UiTranslationService $translationService,
        UiPermissionResolver $permissionResolver
    ) {
        $this->beConstructedWith($contentService, $userService, $translationService, $permissionResolver);
    }

    function it_should_delete_versions(
        ContentService $contentService,
        ContentInfo $contentInfo,
        VersionInfo $versionInfo
    ) {
        $versionNumber = 10;

        $contentService->loadVersionInfo($contentInfo, $versionNumber)->willReturn($versionInfo)->shouldBeCalled();
        $contentService->deleteVersion($versionInfo)->shouldBeCalled();

        $this->deleteVersion($contentInfo, $versionNumber);
    }

    function it_loads_ui_versions_with_translation_and_author(
        ContentService $contentService,
        UiPermissionResolver $permissionResolver,
        UiUserService $userService,
        UiTranslationService $translationService
    ) {
        $creatorId = 1;
        $contentInfo = new ContentInfo();
        $versionInfo = new VersionInfo(['creatorId' => $creatorId, 'status' => VersionInfo::STATUS_DRAFT]);
        $user = new User();
        $language = new Language();

        $contentService->loadVersions($contentInfo)->willReturn([$versionInfo]);
        $userService->findUserById($creatorId)->willReturn($user);
        $translationService->loadTranslations($versionInfo)->willReturn([$language]);
        $permissionResolver->canEditVersion($versionInfo)->shouldBeCalled()->willReturn(true);

        $uiVersion = new UiVersionInfo($versionInfo, ['author' => $user, 'translations' => [$language], 'canEdit' => true]);

        $this->loadVersions($contentInfo)->shouldBeLike([$uiVersion]);
    }

    function it_should_create_a_draft(
        ContentService $contentService,
        ContentInfo $contentInfo,
        VersionInfo $versionInfo
    ) {
        $versionNumber = 10;

        $contentService->loadVersionInfo($contentInfo, $versionNumber)->willReturn($versionInfo)->shouldBeCalled();
        $contentService->createContentDraft($contentInfo, $versionInfo)->shouldBeCalled();

        $this->createDraft($contentInfo, $versionNumber);
    }
}
