# Hybrid UI toolbars

The Hybrid UI toolbars are meant to be customized and extended by any UI module.

## Creating a toolbar
New toolbars are created by defining a service tagged with the `ezplatform.ui.toolbar` tag,
and giving it an alias. The definition below defines the `discovery` toolbar.

```yml
services:
    ezsystems.hybrid_platform_ui.component.discoverybar:
        class: EzSystems\HybridPlatformUi\Components\Toolbar
        arguments:
            - ~
            - []
        tags:
            - {name: ezplatform.ui.toolbar, alias: "discovery"}
```

Any class implementing the `Component` interface can be used as a toolbar.

## Adding items to a toolbar
Items are added to a Toolbar by defining a `Component` as a services tagged with the `ezplatform.ui.toolbar_item` tag.
The configuration block below adds the `search` toolbar item to the `discovery` toolbar:
 
```yml
services:
    ezsystems.hybrid_platform_ui.component.search:
        class: EzSystems\HybridPlatformUi\Components\Search
        tags:
            - {name: ezplatform.ui.toolbar_item, toolbar: "discovery"}
```

## Configuring a toolbar's visibility 
The toolbars are hidden by default. Each page may enable the ones it uses.
The configuration block below will show the `discovery` toolbar when on the `ez_urlalias` route, and the `section_admin`
toolbar on any route that begins with `admin_section`.

```yml
ez_hybrid_platform_ui:
    toolbars:
        discovery:
            visiblity:
                - routes: ["ez_urlalias"]
        section_admin:
            visibility:
                - routes: ["admin_section*"]
```
