<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace EzSystems\HybridPlatformUi\Templating\Twig;

use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\Values\Content\Location;
use Twig_Environment;
use Twig_Extension;
use Twig_SimpleFunction;

class LocationExtension extends Twig_Extension
{
    /**
     * @var LocationService
     */
    private $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    public function getName()
    {
        return 'ezpublish.location';
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction(
                'ez_ui_location_path',
                [$this, 'renderLocationPath'],
                ['needs_environment' => true, 'is_safe' => ['html']]
            )
        ];
    }

    public function renderLocationPath(Twig_Environment $environment, Location $location)
    {
        $path = $location->path;

        /** remove root location from display */
        array_shift($path);
        /** last element will be the provided location, no need to retrieve it again */
        array_pop($path);

        $locations = [];

        foreach ($path as $locationId) {
            $locations[] = $this->locationService->loadLocation($locationId);
        }

        $locations[] = $location;

        return $environment->render(
            'EzSystemsHybridPlatformUiBundle:fields/location:path.html.twig',
            ['locations' => $locations]
        );
    }
}
