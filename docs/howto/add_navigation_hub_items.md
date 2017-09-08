# How-to: add items to the navigation hub

The navigation hub, that shows zones and links in the UI, can easily be added items
by means of tagged services.

## Adding a zone item
Zones contain a set of links. The default zones are Content, Page, Performances and Admin.
A new zone would for instance be added by a 3rd party bundle to integrate some kind of application
in the backoffice, with several sub-chapters. 

A zone is visible when one of its links is visible for the current page.

To define a new zone, create a new service tagged with the `ezplatform.ui.zone` service tag,
using the `EzSystems\HybridPlatformUi\NavigationHub\Zone` class (or a subclass of it). You
may use`'%ezsystems.platformui.navigationhub.zone.class%'` instead of the class.

```yaml
service:
    my_bundle.platform_ui_navigationhub.zones.my_feature:
        class: '%ezsystems.platformui.navigationhub.zone.class%'
        # Recommended as we don't intend to get this service from the container
        public: false
        arguments:
            - "My feature"
            - "my_feature"
        tags:
            - {name: ezplatform.ui.zone}
``` 

|Argument|Description|
|--------|-----------|
|name|The human readable name of the zone, as displayed in the UI|
|identifier|The zone's identifier, a simple string. It will be used when defining links to refer to the zone|

Once the service has been defined, the new Zone should show up in the UI after refreshing.

## Adding links to a zone
Any number of links (within reason, it should fit a typical UI user screen) can be added to a zone.

Links can be added to any zone, defined by your code or not. A minor feature, identified as a subset of an existing
zone, such as Content, should be added to existing zones. Links that are part of a larger feature that defines its
own zone should be added there.

A Link is responsible for:
- Generating urls to any URI
- Saying which zone it should show up for
- Saying if it is active for a given request

To define a new link, create a service tagged with the `ezplatform.ui.link` service tag,
using the `EzSystems\HybridPlatformUi\NavigationHub\Link` class or one of its subclasses.
Several Links types are built-in, and new ones can be implemented.

### Subtree links
A subtree link uses the router and a location id. It will toggle
when browsing and operating inside that location's subtree (included).

```yaml
services:
    my_bundle.platform_ui_navigationhub.link.contentstructure:
        class: '%ezsystems.platformui.navigationhub.link.subtree.class%'
        arguments:
            - '@router'
            - "Blog"
            - "content"
            - {locationId: 123}
        tags:
            - {name: ezplatform.ui.link}
        public: false
```    

|Argument|Description|
|--------|-----------|
|`router`|The router service (`@router`)|
|`name`|The human readable name of the link, as displayed in the UI|
|`zoneIdentifier`|The zone this links should be visible for. In the example above, the link is visible inside the "content" zone|
|`route_parameters`|The parameters used to load the Location. The key indicates the parameter. Possible keys: `locationId`|

### Route links
A route link will link to any defined route, with any given set of parameters.
These links will be used to link directly to any feature in the system.

The link will match the same route and parameters exactly. An optional parameter allows
it to match a given route prefix, in order to toggle the link for all the parts that
belong to it (example: "Section" in the admin toggles on the section list, edit or view pages).

The following service defines the section link in the admin zone:

```yaml
services:
    my_bundle.platformui.navigationhub.link.admin.dashboard:
        class: '%ezsystems.platformui.navigationhub.link.route.class%'
        arguments:
            - '@router'
            - "admin_sectionlist"
            - "Sections"
            - "admin"
        calls:
            - [setRoutePrefix, ["admin_section"]]
        tags:
            - {name: ezplatform.ui.link}
        public: false
```    

|Argument|Description|
|--------|-----------|
|`router`|The router service (`@router`)|
|`route_name`|The Symfony route name used to generate the link and match requests|
|`name`|The human readable name of the link, as displayed in the UI|
|`zoneIdentifier`|The zone this links should be visible for. In the example above, the link is visible inside the "admin" zone|
|`route_parameters`|An array of route parameters that will be used to generate the link and match requests|

A setter method `setRoutePrefix` can be used to define a route prefix used to match requests.
If given, a Request's route with the same prefix will match (example: `admin_sectionedit` matches the
`admin_section` prefix).

## Position
Order of both, links and zones, can be set. It's implemented in a *Symfony way* as a `priority` parameter of service definition tag:

```yaml
services:
    ezsystems.platformui.navigationhub.link.admin.dashboard:
        # ...
        tags:
            - name: ezplatform.ui.link
              priority: 100
```

Lower `priority` values will make links appear first, those with the same `priority` value will appear in an order of definition, higher values obviously later.
Good practice is to make default values multiplier of `10` or `100`, so end-user will be able to inject own links (or zones) between default ones (ie. by setting `priority` to `50`).
Default value of omitted `priority` parameter is `0`. Negative values are accepted as well.
