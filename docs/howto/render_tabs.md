# How-to: render tabs in the UI

Using tabs in a graphical user interface is a very common pattern. Several pages
in the Hybrid Platform UI application have tabs so this pattern can easily be
reused in any custom page.

## Basic tabs

### Markup

Here is a markup example to render 3 tabs:

```html
<ez-server-side-content><!-- or any custom element extending ez-server-side-content -->
    <div class="ez-tabs">
        <ul class="ez-tabs-list">
            <li class="ez-tabs-label is-tab-selected"><a href="#tab1">Tab 1</a></li>
            <li class="ez-tabs-label"><a href="#tab2">Tab 2</a></li>
            <li class="ez-tabs-label"><a href="#tab3">Tab 3</a></li>
        </ul>
        <div class="ez-tabs-panels">
            <div class="ez-tabs-panel is-tab-selected" id="tab1">
                Tab 1 content
            </div>
            <div class="ez-tabs-panel" id="tab2">
                Tab 2 content
            </div>
            <div class="ez-tabs-panel" id="tab3">
                Tab 3 content
            </div>
        </div>
    </div>
</ez-server-side-content>

```

Constraints:

* To be recognized, the tabs markup must be inside an `ez-server-side-content`
  custom element or a custom element extending this one (`ez-content-view`, ...)
* Each tab consists of a *tab label* and a *tab panel*. A panel must have an
  `id` and the corresponding tab label must contain a link whose
  `href` attribute value is that id.
* To initially select a tab, the class `is-tab-selected` must be set on both the
  label and panel HTML element.

### JavaScript API

When switching tabs, the `ez:tabChange` custom event is dispatched from the
`ez-server-side-content` custom element. This event carries the tab label
element and the tab panel element that are going to be selected. It is
configured [to
*bubble*](https://www.w3.org/TR/DOM-Level-2-Events/events.html#Events-flow-bubbling)
and to [be
*cancelable*](https://www.w3.org/TR/DOM-Level-2-Events/events.html#Events-flow-cancelation).

So it's possible to listen to `ez:tabChange` from the custom element dispatching
it or from any parent HTML element (and even from the document).

Example:

```js
document.addEventListener('ez:tabChange', function (e) {
    console.log('A new tab is about to be selected');
    console.log('Panel element', e.detail.panel);
    console.log('Label element', e.detail.label);

    if (whatEverReason) {
        console.log('I don\'t want this new panel to be selected');
        e.preventDefault();
    }
});
```

## Asynchronously loaded tabs

By definition, only one tab is visible at a time. So when you have tabs, it is
common to not load the tab panel content up front but only when it becomes
visible.

### Markup

This feature relies on the `<ez-asynchronous-block>` custom element. Here is the
markup to transform the previous example so that *Tab 3* panel is loaded
asynchronously:

```html
<ez-server-side-content><!-- or any custom element extending ez-server-side-content -->
    <div class="ez-tabs">
        <ul class="ez-tabs-list">
            <li class="ez-tabs-label is-tab-selected"><a href="#tab1">Tab 1</a></li>
            <li class="ez-tabs-label"><a href="#tab2">Tab 2</a></li>
            <li class="ez-tabs-label"><a href="#tab3">Tab 3</a></li>
        </ul>
        <div class="ez-tabs-panels">
            <div class="ez-tabs-panel is-tab-selected" id="tab1">
                Tab 1 content
            </div>
            <div class="ez-tabs-panel" id="tab2">
                Tab 2 content
            </div>
            <ez-asynchronous-block
                class="ez-tabs-panel" id="tab3"
                url="/url/to/request/to/get/tab3/content"
            ></ez-asynchronous-block>
        </div>
    </div>
</ez-server-side-content>
```

With this markup, *Tab 3* content will be loaded by fetching the `url` attribute
value of the `ez-asynchronous-block` representing the panel when *Tab 3* becomes
the selected panel.

### Local navigation provided by `ez-asynchronous-block`

In addition to the asynchronous loading, the `ez-asynchronous-block` custom
element also provides the concept of navigation local to the block.

#### Form handling

If a form in an `ez-asynchronous-block` element is submitted, the block will
submit the form using an AJAX request. The block expects the corresponding
response to only contain the HTML code for the tab panel.

#### Links

By default, a link in an `ez-asynchronous-block` behaves like [any link in the
application](prevent_navigation_enhancement.md). By assigning the class
`ez-js-local-navigation` to a link or one of its ancestors in the block, the
`ez-asynchronous-block` custom element considers a click on this link as a
*local navigation*. That means the block will prevent the default link behavior
and will trigger an AJAX request to only update the block content. So, as in the
form case, the block expects the corresponding response to only contain the HTML
code for the tab panel.

Example:

```html
<ez-asynchronous-block class="ez-tabs-panel" id="tab3"
    url="/url/to/request/to/get/tab3/content">
    <!-- HTML code returned by /url/to/request/to/get/tab3/content -->
    <a href="/whatever/url">I'm a "normal" link</a>
    <ul>
        <li>
            <a href="/whatever/url" class="ez-js-local-navigation">Local to the block link</a>
        </li>
        <li class="ez-js-local-navigation">
            <a href="/whatever/url">Also local to the block link</a>
        </li>
    </ul>
</ez-asynchronous-block>
```

### JavaScript API

After any asynchronous update (initial loading or local navigation), the
`ez-asynchronous-block` custom element dispatches the
`ez:asynchronousBlock:updated` custom event. This event is configured [to
*bubble*](https://www.w3.org/TR/DOM-Level-2-Events/events.html#Events-flow-bubbling).

So it's possible to listen to `ez:asynchronousBlock:updated` from the block
itself or from any ancestor HTML element (and even from the document) to apply
any kind of JavaScript enhancement on the newly added markup.

Example:

```js
aParentElementOfAsynchronousBlock.addEventListener('ez:asynchronousBlock:updated', function (e) {
    console.log(e.target, 'asynchronous block was updated');
});
```
