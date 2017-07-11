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

The `div` starts with a `div` with the class `ez-field-edit-text-zone`. This
`div` contains the Field Definition name wrapped in a `label` element. The
`for` attribute value of this label is filled with the `id` of an input
generated for the Field Type, usually the first one with some exceptions.

Then the specific to the Field Type part is wrapped in a `div` with the class
`ez-field-edit-ui`.

If the Field has a description, this description is added in a paragraph as the
last element of the `div.ez-field-edit`. If the Field is not translatable and
the user is translating a Content, the description is replaced with the non
translatable notice.

So for a required Field with a description, the base structure is:

```html
<div class="ez-field-edit ez-field-edit-ezstring ez-field-edit-required">
    <div class="ez-field-edit-text-zone">
        <label class="ez-field-definition-name" for="auto-generated-id">
            Field Definition Name
        </label>
    </div>
    <div class="ez-field-edit-ui">
        <!-- specific to Field Type part -->
    </div>
    <p class="ez-field-definition-description">Description of the field type</p>
</div>
```

When the same Field is edited but can not be translated, the markup becomes:

```html
<div class="ez-field-edit ez-field-edit-ezstring ez-field-edit-required ez-field-edit-disabled">
    <div class="ez-field-edit-text-zone">
        <label class="ez-field-definition-name" for="auto-generated-id">
            Field Definition Name
        </label>
    </div>
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

* Required

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
    <div class="ez-field-edit-text-zone">
        <label class="ez-field-definition-name" for="checkbox-auto-generated-id">
            Field Definition Name
        </label>
    </div>
    <div class="ez-field-edit-ui">
        <input type="checkbox" id="checkbox-auto-generated-id" name="auto-generated-name" value="1">
    </div>
</div>
```

When Field Definition is marked as required

```html
<div class="ez-field-edit ez-field-edit-ezboolean ez-field-edit-required">
    <div class="ez-field-edit-text-zone">
        <label class="ez-field-definition-name" for="checkbox-auto-generated-id">
            Field Definition Name
        </label>
    </div>
    <div class="ez-field-edit-input">
        <input type="checkbox" id="checkbox-auto-generated-id" name="auto-generated-name" value="1" required>
    </div>
</div>
```

## Country `ezcountry`

Same as for Selection.

## Date `ezdate`

### Options

* Required

### Markup

```html
<div class="ez-field-edit ez-field-edit-ezdate">
    <div class="ez-field-edit-text-zone">
        <label class="ez-field-definition-name" for="auto-generated-id">
            Field Definition Name
        </label>
    </div>
    <div class="ez-field-edit-ui">
        <input type="date" id="auto-generated-id" name="auto-generated-name" value="">
    </div>
</div>
```

When set as required:

As for others Field Type, the outermost `div` gets the `ez-field-edit-required`
class and the `input` gets the `required` attribute.


## Date and Time `ezdatetime`

### Options

* Required
* Use seconds

### Markup

```html
<div class="ez-field-edit ez-field-edit-ezdatetime">
    <div class="ez-field-edit-text-zone">
        <label class="ez-field-definition-name" for="auto-generated-id">
            Field Definition Name
        </label>
    </div>
    <div class="ez-field-edit-ui">
        <input type="datetime-local" id="auto-generated-id" name="auto-generated-name" value="">
    </div>
</div>
```

When configured to store/show seconds, the time input gets the `step` attribute
with the value `1`:

```html
<div class="ez-field-edit ez-field-edit-ezdatetime">
    <div class="ez-field-edit-text-zone">
        <label class="ez-field-definition-name" for="auto-generated-id">
            Field Definition Name
        </label>
    </div>
    <div class="ez-field-edit-ui">
        <input type="datetime-local" step="1" id="auto-generated-id" name="auto-generated-name" value="">
    </div>
</div>
```

When set as required:

As for others Field Type, the outermost `div` gets the `ez-field-edit-required`
class and the `input` gets the `required` attribute.

## Email Address `ezemail`

### Options

* Required

### Markup

```html
<div class="ez-field-edit ez-field-edit-ezemail">
    <div class="ez-field-edit-text-zone">
        <label class="ez-field-definition-name" for="auto-generated-id">
            Field Definition Name
        </label>
    </div>
    <div class="ez-field-edit-ui">
        <input type="email" id="auto-generated-id" name="auto-generated-name" value="">
    </div>
</div>
```

When set as required:

As for others Field Type, the outermost `div` gets the `ez-field-edit-required`
class and the `input` gets the `required` attribute.


## Float `ezfloat`

### Options

* Minimum value
* Maximum value
* Required

### Markup

Without any constraints:

```html
<div class="ez-field-edit ez-field-edit-ezfloat">
    <div class="ez-field-edit-text-zone">
        <label class="ez-field-definition-name" for="auto-generated-id">
            Field Definition Name
        </label>
    </div>
    <div class="ez-field-edit-ui">
        <input type="number" step="any" id="auto-generated-id" name="auto-generated-name" value="">
    </div>
</div>
```

When set required and with a min and max value:

```html
<div class="ez-field-edit ez-field-edit-ezfloat ez-field-edit-required">
    <div class="ez-field-edit-text-zone">
        <label class="ez-field-definition-name" for="auto-generated-id">
            Field Definition Name
        </label>
    </div>
    <div class="ez-field-edit-ui">
        <input type="number" step="any" min="5.2" max="10.6" required id="auto-generated-id" name="auto-generated-name" value="">
    </div>
</div>
```

## Map Location `ezgmaplocation`

### Options

* Required

### Markup

```html
<div class="ez-field-edit ez-field-edit-ezgmaplocation">
    <div class="ez-field-edit-text-zone">
        <label class="ez-field-definition-name"><!-- no for attribute -->
            Field Definition Name
        </label>
    </div>
    <div class="ez-field-edit-ui">
        <fieldset>
            <div class="ez-sub-field ez-sub-field-latitude">
                <div class="ez-sub-field-text-zone">
                    <label class="ez-sub-field-name" for="auto-generated-id-lat">
                        Latitude
                    </label>
                </div>
                <div class="ez-sub-field-ui">
                    <input type="number" step="any" min="-90" max="90" id="auto-generated-id-lat" name="auto-generated-name-lat">
                </div>
            </div>
            <div class="ez-sub-field ez-sub-field-longitude">
                <div class="ez-sub-field-text-zone">
                    <label class="ez-sub-field-name" for="auto-generated-id-lon">
                        Longitude
                    </label>
                </div>
                <div class="ez-sub-field-ui">
                    <input type="number" step="any" min="-90" max="90" id="auto-generated-id-lon" name="auto-generated-name-lon">
                </div>
            </div>
            <div class="ez-sub-field ez-sub-field-address">
                <div class="ez-sub-field-text-zone">
                    <label class="ez-sub-field-name" for="auto-generated-id-address">
                        Address
                    </label>
                </div>
                <div class="ez-sub-field-ui">
                    <input type="text" id="auto-generated-id-address" name="auto-generated-name-address">
                </div>
            </div>
        </fieldset>
    </div>
</div>
```

When set required, the latitude and longitude inputs receives the `required`
attribute.

## Image `ezimage`

## Integer `ezinteger`

### Options

* Minimum value
* Maximum value
* Required

### Markup

Without any constraints:

```html
<div class="ez-field-edit ez-field-edit-ezinteger">
    <div class="ez-field-edit-text-zone">
        <label class="ez-field-definition-name" for="auto-generated-id">
            Field Definition Name
        </label>
    </div>
    <div class="ez-field-edit-ui">
        <input type="number" step="1" id="auto-generated-id" name="auto-generated-name" value="">
    </div>
</div>
```

When set required and with a min and max value:

```html
<div class="ez-field-edit ez-field-edit-ezinteger ez-field-edit-required">
    <div class="ez-field-edit-text-zone">
        <label class="ez-field-definition-name" for="auto-generated-id">
            Field Definition Name
        </label>
    </div>
    <div class="ez-field-edit-ui">
        <input type="number" step="1" min="5" max="10" required id="auto-generated-id" name="auto-generated-name" value="">
    </div>
</div>
```

## ISBN `ezisbn`

### Options

* Required
* ISBN13 (ISBN10 by default)

### Markup

For ISBN10:

```html
<div class="ez-field-edit ez-field-edit-ezisbn">
    <div class="ez-field-edit-text-zone">
        <label class="ez-field-definition-name" for="auto-generated-id">
            Field Definition Name
        </label>
    </div>
    <div class="ez-field-edit-ui">
        <input type="text" pattern="^([0-9]{9}[0-9X])$" id="auto-generated-id" name="auto-generated-name" value="">
    </div>
</div>
```

For ISBN13:

```html
<div class="ez-field-edit ez-field-edit-ezisbn">
    <div class="ez-field-edit-text-zone">
        <label class="ez-field-definition-name" for="auto-generated-id">
            Field Definition Name
        </label>
    </div>
    <div class="ez-field-edit-ui">
        <input type="text" pattern="^(97[89][0-9]{10})$" id="auto-generated-id" name="auto-generated-name" value="">
    </div>
</div>
```

When set as required:

As for others Field Type, the outermost `div` gets the `ez-field-edit-required`
class and the `input` gets the `required` attribute.


## Keywords `ezkeyword`

### Options

* Required

### Markup

```html
<div class="ez-field-edit ez-field-edit-ezkeyword">
    <div class="ez-field-edit-text-zone">
        <label class="ez-field-definition-name" for="auto-generated-id">
            Field Definition Name
        </label>
    </div>
    <div class="ez-field-edit-ui">
        <input type="text" id="auto-generated-id" name="auto-generated-name" value="">
    </div>
</div>
```
When set as required:

As for others Field Type, the outermost `div` gets the `ez-field-edit-required`
class and the `input` gets the `required` attribute.


## Media `ezmedia`

## Content Relation `ezobjectrelation`

### Options

* Default Location
* Required

### Markup

```html
<div class="ez-field-edit ez-field-edit-ezobjectrelation">
    <div class="ez-field-edit-text-zone">
        <label class="ez-field-definition-name" for="auto-generated-id">
            Field Definition Name
        </label>
    </div>
    <div class="ez-field-edit-ui">
        <input type="number" step="1" min="1"  id="auto-generated-id" name="auto-generated-name" value="">
    </div>
</div>
```

The `input` field expects a Content id.

When set as required:

As for others Field Type, the outermost `div` gets the `ez-field-edit-required`
class and the `input` gets the `required` attribute.

## Content Relations `ezobjectrelationlist`

### Options

* Default Location
* Allowed Content Types
* Required

### Markup

```html
<div class="ez-field-edit ez-field-edit-ezobjectrelation">
    <div class="ez-field-edit-text-zone">
        <label class="ez-field-definition-name" for="auto-generated-id">
            Field Definition Name
        </label>
    </div>
    <div class="ez-field-edit-ui">
        <input type="text" id="auto-generated-id" name="auto-generated-name" value="">
    </div>
</div>
```

The `input` field expects a coma separated list of Content id.

When set as required:

As for others Field Type, the outermost `div` gets the `ez-field-edit-required`
class and the `input` gets the `required` attribute.


## Page `ezpage`

## Rich Text `ezrichtext`

### Options

* Required

### Markup

```html
<div class="ez-field-edit ez-field-edit-ezrichtext">
    <div class="ez-field-edit-text-zone">
        <label class="ez-field-definition-name" for="auto-generated-id">
            Field Definition Name
        </label>
    </div>
    <div class="ez-field-edit-ui">
        <textarea id="auto-generated-id" name="auto-generated-name"></textarea>
    </div>
</div>
```

## Selection `ezselection`

### Options

* Multiple
* Required

### Current markup

```html
<div class="ezfield-type-ezselection ezfield-identifier-new_ezselection_19">
    <fieldset>
        <legend><label class="required">New ezselection 19</label></legend>
        <select id="ezrepoforms_content_edit_fieldsData_new_ezselection_19_value" name="ezrepoforms_content_edit[fieldsData][new_ezselection_19][value]"><option value=""></option></select>
    </fieldset>
</div>
```

When configured as multiple, the `select` element receives the `multiple`
attribute.

### New markup

```html
<div class="ez-field-edit ez-field-edit-ezselect">
    <div class="ez-field-edit-text-zone">
        <label class="ez-field-definition-name" for="auto-generated-id">
            Field Definition Name
        </label>
    </div>
    <div class="ez-field-edit-ui">
        <select id="auto-generated-id" name="auto-generated-name">
            <!-- list of option -->
        </select>
    </div>
</div>
```

When set as multiple selection, the `select` element receives the `multiple`
attribute.

When set as required:

As for others Field Type, the outermost `div` gets the `ez-field-edit-required`
class and the `select` gets the `required` attribute.

## Text Line `ezstring`

### Options

* Minimum length
* Maximum length
* Required

### Current markup

```html
<div class="ezfield-type-ezstring ezfield-identifier-new_ezstring_21">
    <fieldset>
        <legend><label class="required">New ezstring 21</label></legend>
        <input type="text" id="ezrepoforms_content_edit_fieldsData_new_ezstring_21_value" name="ezrepoforms_content_edit[fieldsData][new_ezstring_21][value]">
    </fieldset>
</div>
```

### New markup

Without any length constraints:

```html
<div class="ez-field-edit ez-field-edit-ezstring">
    <div class="ez-field-edit-text-zone">
        <label class="ez-field-definition-name" for="auto-generated-id">
            Field Definition Name
        </label>
    </div>
    <div class="ez-field-edit-ui">
        <input type="text" id="auto-generated-id" name="auto-generated-name" value="">
    </div>
</div>
```

With length constraints:

To have a validation consistency between *Mininum length* and *Maximum length*
constraint, both are expressed with the `pattern` input attribute (no
`maxlength` attribute). So depending on the length constraints, the `input`
element will have the following `pattern` attribute:

* Only *Minimum length* constraint (10 characters for example): `pattern=".{10,}"`
* Only *Maximum length* constraint (10 characters for example): `pattern=".{,10}"`
* Both *Minimum length* and *Maximum length* constraints (5 and 10 characters
  for example): `pattern=".{5,10}"`

When set as required:

As for others Field Type, the outermost `div` gets the `ez-field-edit-required`
class and the `input` gets the `required` attribute.

## Text Block `eztextblock`

### Options

* Required
* Number of rows

### Current markup

```html
<div class="ezfield-type-eztext ezfield-identifier-new_eztext_22">
    <fieldset>
        <legend><label class="required">New eztext 22</label></legend>
        <textarea id="ezrepoforms_content_edit_fieldsData_new_eztext_22_value" name="ezrepoforms_content_edit[fieldsData][new_eztext_22][value]" rows="10"></textarea>
    </fieldset>
</div>
```

### Markup

```html
<div class="ez-field-edit ez-field-edit-eztext">
    <div class="ez-field-edit-text-zone">
        <label class="ez-field-definition-name" for="auto-generated-id">
            Field Definition Name
        </label>
    </div>
    <div class="ez-field-edit-ui">
        <textarea rows="10" name="auto-generated-name" id="auto-generated-id"></textarea>
    </div>
</div>
```

Notes: the `rows` attribute value comes from the field definition settings.

## Time `eztime`

### Options

* Required
* Use seconds

### Markup

```html
<div class="ez-field-edit ez-field-edit-eztime">
    <div class="ez-field-edit-text-zone">
        <label class="ez-field-definition-name" for="auto-generated-id">
            Field Definition Name
        </label>
    </div>
    <div class="ez-field-edit-ui">
        <input type="time" id="auto-generated-id" name="auto-generated-name" value="">
    </div>
</div>
```

When configured to store/show seconds, the time input gets the `step` attribute
with the value `1`:

```html
<div class="ez-field-edit ez-field-edit-eztime">
    <div class="ez-field-edit-text-zone">
        <label class="ez-field-definition-name" for="auto-generated-id">
            Field Definition Name
        </label>
    </div>
    <div class="ez-field-edit-ui">
        <input type="time" step="1" id="auto-generated-id" name="auto-generated-name" value="">
    </div>
</div>
```

When set as required:

As for others Field Type, the outermost `div` gets the `ez-field-edit-required`
class and the `input` gets the `required` attribute.


## URL `ezurl`

### Options

* Required

### Markup

```html
<div class="ez-field-edit ez-field-edit-ezurl">
    <div class="ez-field-edit-text-zone">
        <label class="ez-field-definition-name"><!-- no for attribute! -->
            Field Definition Name
        </label>
    </div>
    <div class="ez-field-edit-ui">
        <fieldset>
            <div class="ez-sub-field ez-sub-field-url">
                <div class="ez-sub-field-text-zone">
                    <label class="ez-sub-field-name" for="auto-generated-id-url">
                        URL
                    </label>
                </div>
                <div class="ez-sub-field-ui">
                    <input type="text" id="auto-generated-id-url" name="auto-generated-name-url">
                </div>
            </div>
            <div class="ez-sub-field ez-sub-field-text">
                <div class="ez-sub-field-text-zone">
                    <label class="ez-sub-field-name" for="auto-generated-id-text">
                        Text
                    </label>
                </div>
                <div class="ez-sub-field-ui">
                    <input type="text" id="auto-generated-id-text" name="auto-generated-name-text">
                </div>
            </div>
        </fieldset>
    </div>
</div>
```

When set as required:

As for others Field Type, the outermost `div` gets the `ez-field-edit-required`
class but only the URL `input` gets the `required` attribute.

## User Accout `ezuser`

### Options

* Required

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

### New markup

```html
<div class="ez-field-edit ez-field-edit-ezuser">
    <div class="ez-field-edit-text-zone">
        <label class="ez-field-definition-name"><!-- no for attribute! -->
            Field Definition Name
        </label>
    </div>
    <div class="ez-field-edit-ui">
        <fieldset>
            <div class="ez-sub-field ez-sub-field-login">
                <div class="ez-sub-field-text-zone">
                    <label class="ez-sub-field-name" for="auto-generated-id-login">
                        Login
                    </label>
                </div>
                <div class="ez-sub-field-ui">
                    <input type="text" id="auto-generated-id-login" name="auto-generated-name-login">
                </div>
            </div>
            <div class="ez-sub-field ez-sub-field-email">
                <div class="ez-sub-field-text-zone">
                    <label class="ez-sub-field-name" for="auto-generated-id-email">
                        Email
                    </label>
                </div>
                <div class="ez-sub-field-ui">
                    <input type="email" id="auto-generated-id-email" name="auto-generated-name-email">
                </div>
            </div>
            <div class="ez-sub-field ez-sub-field-password">
                <div class="ez-sub-field-text-zone">
                    <label class="ez-sub-field-name" for="auto-generated-id-password1">
                        Password
                    </label>
                </div>
                <div class="ez-sub-field-ui">
                    <input type="password" id="auto-generated-id-password1" name="auto-generated-name-password1">
                </div>
            </div>
            <div class="ez-sub-field ez-sub-field-confirm-password">
                <div class="ez-sub-field-text-zone">
                    <label class="ez-sub-field-name" for="auto-generated-id-password2">
                        Confirm password
                    </label>
                </div>
                <div class="ez-sub-field-ui">
                    <input type="password" id="auto-generated-id-password2" name="auto-generated-name-password2">
                </div>
            </div>
        </fieldset>
    </div>
</div>
```

When set as required:

As for others Field Type, the outermost `div` gets the `ez-field-edit-required`
class. In addition, all `input` receives the `required` attribute.

Note: the login can not change, so the login `input` is set as read only (with
the `readonly` HTML5 attribute) when editing a Content item with a User field.
