# How-to: Prevent navigation enhancement

The `<ez-platform-ui-app>` custom element represents the Hybrid Platform UI
Application. It is mainly responsible for enhancing the navigation by handling
click on links and form submit to replace the default browser behavior by a
lighter AJAX request which is supposed to return a JSON Update structure. This
is the default Hybrid Platform Application behavior but it's possible to opt-out
from it.

## Prevent navigation enhancement on links

To prevent the application from enhancing some links, just set the class
`ez-js-standard-navigation` on the corresponding link(s) or on one of their
ancestor in the DOM tree.

Example:

```html
<ul class="ez-js-standard-navigation">
  <li><a href="/">This link won't be enhanced</a></li>
  <li><a href="/path">Same for this one</a>
</ul>

<a href="/path2" class="ez-js-standard-navigation">Or this one</a>
```

## Prevent navigation enhancement on forms

To prevent the application from enhancing some forms, just set the class
`ez-js-standard-form` on the corresponding form(s) or on one of their ancestor
in the DOM tree.

Example:

```html
<form action="/whatever" class="ez-js-standard-form">
  <input type="text" name="something" value="the app ignores me!">
  <button>Submit normally</button>
</form>

<div class="ez-js-standard-form">
  <form action="/whatever">
    <input type="text" name="name" value="the app ignores me as well!">
    <button>Submit normally</button>
  </form>
</div>
```
