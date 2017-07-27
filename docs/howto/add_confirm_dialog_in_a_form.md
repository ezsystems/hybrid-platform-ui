# How-to: Add a confirm dialog in a form

Asking the user for a confirmation before doing a *impacting* operation is a
very common pattern. For that purpose, the Hybrid Platform UI application
defines an easily reusable markup convention that can be used in various
contexts.

## Confirm dialog markup convention

This confirm dialog convention relies on [the HTML5 `<dialog>`
element](https://developer.mozilla.org/fr/docs/Web/HTML/Element/dialog) and a
`<button>` to open the dialog. To be properly styled, the `<dialog>` element
should have the class `ez-modal`. By default the `<dialog>` element is hidden to
the user, it becomes visible when the user clicks on a `<button>` having the
class `ez-js-open-modal` and a `value` attribute containing a selector matching
it. As soon as the user clicks on an element with the class `ez-js-close-modal`
the modal is closed.

## Example

### HTML code

Let's take the following form:


```html
<form action="/whatever/action" method="post">
    <!-- some markup -->

    <button type="submit" name="ImpactingOperation">
        Impacting operation
    </button>
</form>
```

To show a confirm dialog before doing the *Impacting operation*, you have to:

1. Replace the *ImpactingOperation* `submit` button with a button having the
   following attributes:
    * `type` set to `button` so that clicking on it does not submit the form
    * `class` containing `ez-js-open-modal` so the app recognizes this button as
      a button to open a dialog.
    * `value` set with a selector matching the `<dialog>` element to show
1. Add the actual `<dialog>` element in the form. It should contain the original
   submit button with the class `ez-js-close-modal` so that clicking on it
   submits the form and closes the dialog.

This results in the following HTML code:

```html
<form action="/whatever/action" method="post">
    <!-- some markup -->

    <button type="button" value="#confirm-impacting-operation" class="ez-js-open-modal">
        Impacting operation
    </button>

    <!-- it can be before or after the button -->
    <dialog class="ez-modal" id="confirm-impacting-operation">
        <!--
            it's also possible to have header here:
            <header>Sure?</header>
            it could also contain a button with the `ez-js-close-modal` class
            to close the dialog in addition/instead of the Cancel button below
        -->
        <section>
            <p>Are you sure you want to do the impacting operation?</p>
        </section>
        <footer>
            <button type="button" class="ez-js-close-modal">Cancel</button>
            <button type="submit" name="ImpactingOperation" class="ez-js-close-modal">
                Impacting operation
            </button>
        </footer>
    </dialog>
</form>
```

### Symfony form and Twig template

In a Symfony application, it's common to use [the Form Component to integrate
forms](https://symfony.com/doc/current/forms.html) and most forms in the
application are built this way. The Hybrid Platform UI application is also
generated with some Twig templates. In that context, the previous example could
be generated with the following template code:

```twig
{{ form_start(myForm, {'action': path('form_handling_route')}) }}
    <!-- some markup -->

    {{ form_widget(myForm.delete, {'label': 'Impacting Operation'}) }}
{{ form_end(myForm) }}
```

The changes explained in the previous section can be applied directly in the
Twig template. The Hybrid Platform UI application provides the template
`components/confirm_delete_dialog.html.twig` to make adding a confirm dialog in
such context easier. It can be used in the following way:

```twig
{% set dialogId = 'confirm-impacting-operation' %}
{{ form_start(myForm, {'action': path('form_handling_route')}) }}
    <!-- some markup -->

    <button type="button" value="#{{ dialogId }}" class="ez-js-open-modal">
        Impacting operation
    </button>

    {{
        include('@EzSystemsHybridPlatformUi/components/confirm_delete_dialog.html.twig', {
            'dialogId': dialogId,
            'message': 'Are you sure you want to do the impacting operation?',
            'confirmButton': myForm.delete,
        })
    }}
{{ form_end(myForm) }}
```

This code pattern is used in various places in the Hybrid Platform UI
application for instance when [removing a
Location](https://github.com/ezsystems/hybrid-platform-ui/blob/master/src/bundle/Resources/views/content/tabs/locations.html.twig#L2-L23)
or [a
Section](https://github.com/ezsystems/PlatformUIBundle/blob/2.0/Resources/views/Section/view.html.twig#L45-L67).
