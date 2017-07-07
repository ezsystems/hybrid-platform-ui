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

### HTML5 validation

Most of our Field Type can be edited and validated with pure HTML5 inputs or
with a composition of several HTML5 inputs. So when it comes to frontend
validation, HTML5 validation is the based on which `ez-field-edit` and
`ez-field-edit-*` custom element will rely on.

## Authors `ezauthor`

## File `ezbinaryfile`

## Checkbox `ezboolean`

### Current markup

```html
<div class="ezfield-type-ezboolean ezfield-identifier-new_ezboolean_3">
    <fieldset>
        <legend><label class="required">New ezboolean 3</label></legend>
        <input type="checkbox" id="ezrepoforms_content_edit_fieldsData_new_ezboolean_3_value" name="ezrepoforms_content_edit[fieldsData][new_ezboolean_3][value]" value="1">
    </fieldset>
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
