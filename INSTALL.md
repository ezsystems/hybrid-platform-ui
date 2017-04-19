# Installation

## Current development version
Custom repositories and requirements must be set at root level during the current development phase.
Edit the file, and add the following blocks:

```json
    "require": {
        "composer/installers": "dev-ezplatform_assets as 1.3.x-dev",
        "dpobel/ez-field-edit": "dev-master as 1.0.x-dev",
        "dpobel/ez-map": "dev-master as 1.0.x-dev",
        "dpobel/hybrid-platformui-assets": "~0.3"
    }
```

```json
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/dpobel/ez-field-edit.git"
        },
        {
            "type": "vcs",
            "url": "https://github.com/dpobel/hybrid-platformui-assets.git"
        },
        {
            "type": "vcs",
            "url": "https://github.com/dpobel/installers.git"
        },
        {
            "type": "vcs",
            "url": "https://github.com/dpobel/ez-map.git"
        }
    ]
```
## Requirements
From eZ Platform 1.9.0 or higher, run:

    composer require ezsystems/hybrid-platform-ui:^0.1@dev

## Bundles
Edit `src/AppKernel.php`, and add the `EzSystemsHybridUiBundle` and `FOSJsRoutingBundle` to the list:

    $bundles = array(
        // ...
        new EzSystems\HybridPlatformUiBundle\EzSystemsHybridPlatformUiBundle(),
        new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
        new AppBundle\AppBundle(),
    );

## Routes
Register the required routes. Edit `app/config/routing.yml`, and add the following lines:

    fos_js_routing:
        resource: "@FOSJsRoutingBundle/Resources/config/routing/routing.xml"

    ez_platform_hybrid_ui:
        resource: "@EzSystemsHybridPlatformUiBundle/Resources/config/routing.yml"

## Assets
Update the assets by running the following command:

    # Symfony 2
    php app/console assets:install --symlink web
    
    # Symfony 3
    php bin/console assets:install --symlink web


