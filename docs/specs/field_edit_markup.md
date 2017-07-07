# Field edit markup

## Common aspects

### Wrapped by a custom element

As described in [EZP-27576](https://jira.ez.no/browse/EZP-27576), when used
inside Hybrid Platform UI, the edit markup for a given field should be wrapped
by a custom element. By default, this custom element is `ez-field-edit` and it
expects `fieldtype` attribute to be filled with the Field Type identifier.

For a Content Type with a Text Line, a Map Location and a Text Block fields, the
Content edit form would look like:

```html
<form>
  <ez-field-edit fieldtype="ezstring">
     <!-- markup for Text Line -->
  </ez-field-edit>
  <ez-field-edit-maplocation fieldtype="ezgmaplocation">
     <!-- markup for Map Location -->
  </ez-field-edit>
  <ez-field-edit fieldtype="eztext">
     <!-- markup for Text Block -->
  </ez-field-edit>
</form>
```

### Base structure

The markup for a given field is always wrapped in a `div` with at least 2
classes:

* `ez-field-edit`
* `ez-field-edit-<fieldtypeidentifier>`

If the Field is marked as required, the class `ez-field-edit-required` is added
as well.

If the Field is not translatable and the user is translating the Content item
the class `ez-field-edit-disabled` is added.

The `div` starts with a paragraph containing the Field Definition name wrapped
in a label. The `for` attribute value of this label is filled with the `id` of
an input generated for the Field Type, usually the first one with some
exceptions.

Then the specific to the Field Type part is wrapped in a `div` with the class
`ez-field-edit-ui`.

If the Field has a description, this description is added in a paragraph as the
last element of the `div.ez-field-edit`. If the Field is not translatable and
the user is translating a Content, the description is replaced with the non
translatable notice.

So for a required Field with a description, the base structure is:

```html
<div class="ez-field-edit ez-field-edit-ezstring ez-field-edit-required">
    <p class="ez-field-definition-name"><label for="autogenerateid">Field Defintion Name</label></p>
    <div class="ez-field-edit-ui">
        <!-- specific to Field Type part -->
    </div>
    <p class="ez-field-definition-description">Description of the field type</p>
</div>
```

When the same Field is edited but can not be translated, the markup becomes:

```html
<div class="ez-field-edit ez-field-edit-ezstring ez-field-edit-required ez-field-edit-disabled">
    <p class="ez-field-definition-name"><label for="autogenerateid">Field Defintion Name</label></p>
    <div class="ez-field-edit-ui">
        <!-- specific to Field Type part -->
    </div>
    <p class="ez-field-not-translatable">This is not a translatable field and cannot be modified</p>
</div>
```

### HTML5 validation

Most of our Field Type can be edited and validated with pure HTML5 inputs or
with a composition of several HTML5 inputs. So when it comes to frontend
validation, HTML5 validation is the based on which `ez-field-edit` and
`ez-field-edit-*` custom element will rely on.

## Authors `ezauthor`

## File `ezbinaryfile`

## Checkbox `ezboolean`

### Options:

* can be required

### Current markup

```html
<div class="ezfield-type-ezboolean ezfield-identifier-new_ezboolean_3">
    <fieldset>
        <legend><label class="required">New ezboolean 3</label></legend>
        <input type="checkbox" id="ezrepoforms_content_edit_fieldsData_new_ezboolean_3_value" name="ezrepoforms_content_edit[fieldsData][new_ezboolean_3][value]" value="1">
    </fieldset>
</div>
```

### New markup

When not required:

```html
<div class="ez-field-edit ez-field-edit-ezboolean">
    <p class="ez-field-definition-name">
        <label for="checkbox-auto-generated-id">New ezboolean 3</label>
    </p>
    <div class="ez-field-edit-ui">
        <input type="checkbox" id="checkbox-auto-generated-id" name="auto-generated-name" value="1">
    </div>
</div>
```

When Field Definition is marked as required

```html
<div class="ez-field-edit ez-field-edit-ezboolean ez-field-edit-required">
    <p class="ez-field-definition-name">
        <label for="checkbox-auto-generated-id">New ezboolean 3</label>
    </p>
    <div class="ez-field-edit-input">
        <input type="checkbox" id="checkbox-auto-generated-id" name="auto-generated-name" value="1" required>
    </div>
</div>
```

## Country `ezcountry`

## Date `ezdate`

## Date and Time `ezdatetime`

## Email Address `ezemail`

## Float `ezfloat`

## Map Location `ezgmaplocation`

## Image `ezimage`

## Integer `ezinteger`

## ISBN `ezisbn`

## Keywords `ezkeyword`

## Media `ezmedia`

## Content Relation `ezobjectrelation`

## Content Relations `ezobjectrelationlist`

## Page `ezpage`

## Rich Text `ezrichtext`

## Selection `ezselection`

### Single

#### Current markup

```html
<div class="ezfield-type-ezselection ezfield-identifier-new_ezselection_19">
    <fieldset>
        <legend><label class="required">New ezselection 19</label></legend>
        <select id="ezrepoforms_content_edit_fieldsData_new_ezselection_19_value" name="ezrepoforms_content_edit[fieldsData][new_ezselection_19][value]"><option value=""></option></select>
    </fieldset>
</div>
```

### Multiple

#### Current markup

```
<div class="ezfield-type-ezselection ezfield-identifier-new_ezselection_19">
    <fieldset>
        <legend>New ezselection 19</label></legend>
        <select id="ezrepoforms_content_edit_fieldsData_new_ezselection_19_value" name="ezrepoforms_content_edit[fieldsData][new_ezselection_19][value][]" multiple=""></select>
    </fieldset>
</div>
```

## Text Line `ezstring`

### Current markup

```html
<div class="ezfield-type-ezstring ezfield-identifier-new_ezstring_21">
    <fieldset>
        <legend><label class="required">New ezstring 21</label></legend>
        <input type="text" id="ezrepoforms_content_edit_fieldsData_new_ezstring_21_value" name="ezrepoforms_content_edit[fieldsData][new_ezstring_21][value]">
    </fieldset>
</div>
```

## Text Block `eztextblock`

### Current markup

```html
<div class="ezfield-type-eztext ezfield-identifier-new_eztext_22">
    <fieldset>
        <legend><label class="required">New eztext 22</label></legend>
        <textarea id="ezrepoforms_content_edit_fieldsData_new_eztext_22_value" name="ezrepoforms_content_edit[fieldsData][new_eztext_22][value]" rows="10"></textarea>
    </fieldset>
</div>
```

## Time `eztime`

## URL `ezurl`

## User Accout `ezuser`

### Current markup

```html
<div class="ezfield-type-ezuser ezfield-identifier-new_ezuser_25">
    <fieldset>
        <legend><label class="required">New ezuser 25</label></legend>
        <div id="ezrepoforms_content_edit_fieldsData_new_ezuser_25_value">
            <div>
                <label for="ezrepoforms_content_edit_fieldsData_new_ezuser_25_value_username">Username:</label>
                <input type="text" id="ezrepoforms_content_edit_fieldsData_new_ezuser_25_value_username" name="ezrepoforms_content_edit[fieldsData][new_ezuser_25][value][username]">
            </div>
            <div>
                <label for="ezrepoforms_content_edit_fieldsData_new_ezuser_25_value_password_first">Password:</label>
                <input type="password" id="ezrepoforms_content_edit_fieldsData_new_ezuser_25_value_password_first" name="ezrepoforms_content_edit[fieldsData][new_ezuser_25][value][password][first]">
            </div>
            <div>
                <label for="ezrepoforms_content_edit_fieldsData_new_ezuser_25_value_password_second">Confirm password:</label>
                <input type="password" id="ezrepoforms_content_edit_fieldsData_new_ezuser_25_value_password_second" name="ezrepoforms_content_edit[fieldsData][new_ezuser_25][value][password][second]">
            </div>
            <div>
                <label for="ezrepoforms_content_edit_fieldsData_new_ezuser_25_value_email">E-mail:</label>
                <input type="email" id="ezrepoforms_content_edit_fieldsData_new_ezuser_25_value_email" name="ezrepoforms_content_edit[fieldsData][new_ezuser_25][value][email]">
            </div>
        </div>
    </fieldset>
</div>
```
