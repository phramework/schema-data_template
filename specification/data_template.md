# Definitions

## data_template

We are using `data_template`s in API and clients to both to:
- Specify the validation and sanitization process of input forms
- To specify the input form interface of clients

For short we are calling `data_template`s and their extension or aliases as `"template"`.

Templates are relying upon [JSON Schema](http://json-schema.org/) specification (draft v4). On the back-end we are using [phramework/validate](https://github.com/phramework/validate) library's implementation.

JSON schema is allowing us to dynamically define input form, so using API to server the templates clients are not longer required to write (hard-coded) the forms into their code.

## `data_template`s extensions

`data_template`s MAY be extended by API and appear with different resource (JSON API resource) type. An extension doesn't always mean that additional functionality is specified, they MAY server as aliases of `data_template`s.


## Client platforms
We define the following client platforms:
- `www`
- `ios`
- `android`


We define as `all` all the above client platforms.

We define as `mobile` a client platform that one of `ios` or `android`.

The terms `client` and `platforms` are synonyms for the purposes of this document.

## Notation

- Members in square brackets `[]` are optional
- By default all member directives are supported by `all` client platforms, otherwise it must be written explicitly.
- Value `"platform"` in enum values means that the client platform should decide
- When default keyword member values are omitted, clients **MUST** use the provided default values when they are not set.

# JSON API resource of type data_template
`data_template` are return in various API requests either as a single resource or a collection of resources on primary data or at included resources (Note provide links).

A `data_template` resource define the following attributes:
- `structure`, JSON schema definition of input form
- `tag`, used to strongly identify a template
- `order`, used to sort templates when a collection of template resources is returned

A resource example:
```json
{
  "type": "data_template",
  "id": "45",
  "attributes": {
    "order": -10,
    "structure": {
      "type": "object",
      "properties": {
        "name": {
          "type": "string"
        }
      }
    }
  },
  "tag": [
    "a tag"
  ]
}
```

## structure
Structure attribute contains the JSON schema definition of input form. The structure MUST be JSON schema of type `object`, we define that object as *root object*.
If some reason a template is no "questions" defined **(NOTE define question)** the properties member of root object should be empty.

### root object
Root object is always a JSON schema of type object. It can defined the following keyword members:

key              | type |default| description
-----------------|------|-------|------------
`type`           |string|       |JSON schema type, root object always must be `"object"`
`properties`     |object|       |Define template's properties - questions (fields *TODO*)
[`required`]     |array |`[]`     |Optional, an array which defines properties that are required
[`title`]        |string|`null`   |Optional template's title
[`description`]  |string|`null`   |Optional template's description
[`meta`]         |object|`{}`     |Root object display and behavior meta
[`additionalProperties`]|bool   |`true`|
[`minProperties`]|integer|`0`     |
[`maxProperties`]|integer&#124;`null`|`null`|
[`x-visibility`] |object|`{}`   |A set of rules to conditionally control the visibility/existance of a property

*Members in square brackets `[]` are optional*

#### required

See [understanding-json-schema](https://spacetelescope.github.io/understanding-json-schema/reference/object.html#required)

> By default, the properties defined by the properties keyword are not required. However, one can provide a list of required properties using the required keyword.
The required keyword takes an array of one or more strings. Each of these strings must be unique.

#### meta
key                            |type| default    |platform| description
-------------------------------|----|------------|--------|----
[`show_title`]                 |bool|`false`     |        |Show form's title
[`show_description`]           |bool|`false`     |        |Show form's description
[`show_properties_title`]      |bool|`true`      |        |Show title of each question
[`show_properties_description`]|bool|`false`     |        |Show description of each question
[`show_in_groups_of`]          |string|`"1"`     |`mobile`|Display form's questions in groups, this directive member is only supported by mobiles *TODO*
[`submit_trigger`]             |bool|`"platform"`|        |Allowed values: `"platform"`, `"on_change"`, `"button"`

Example:
```json
{
  "type": "object",
  "properties": {
    "name": {
      "type": "string",
      "title": "Your name",
      "description": "Let us know you better, enter your name"
    }
  },
  "required": ["name"],
  "meta": {
    "show_title": true,
    "show_description": true,
    "submit_trigger": "button"
  }
}
```

#### properties

See [understanding-json-schema](https://spacetelescope.github.io/understanding-json-schema/reference/object.html#properties)

> The properties (key-value pairs) on an object are defined using the properties keyword. The value of properties is an object, where each key is the name of a property and each value is a JSON schema used to validate that property.

#### x-visibility

**TODO**

- See examples:
https://github.com/phramework/schema-data_template/tree/master/data_template/x-visibility

- See phramework/validate implementation
https://github.com/phramework/validate/issues/19

Supported property types are :
- enum *(not a vanilla JSON schema)*
- number
- integer
- string
- date *(not a vanilla JSON schema)*
- date-time *(not a vanilla JSON schema)*
- array

###### enum

keyword members:

key          | type | default    | description
-------------|------|------------|-------------
type         |string||
[order]      |integer||
enum         |array ||An array with the allowed values
title        |string||
description  |string||
[meta]       |object|`{}`| 

###### meta

meta keyword members for enum:

key            | type  | default    |platform| description
---------------|-------|------------|--------|-------------
[`display`]    |string |`"platform"`|        |How enum component should be displayed, allowed values: `"platform"`, `"radio"`, `"select"`, `"stars"`, `"dropdown"`
[`enum-titles`]|object |`{}`        |        |It can define a label to display instead of enum value, it's no required to be set for all the available values.


Example:
```json
{
  "type": "object",
  "properties": {
    "platform": {
      "type": "enum",
      "enum": ["www", "ios", "android"],
      "title": "Your platform",
      "description": "Tell us your platform",
      "meta" : {
        "display": "radio",
        "enum-titles": {
          "www": "Web browser",
          "android": "Android"
        }
      }
    }
  }
}
```



###### number
keyword members:

key           | type | default    | description
--------------|------|------------|-------------
type          |string||
[order]       |integer||
title         |string||
description   |string||
[minimum]     |number|`null`|
[maximum]     |number|`null`|
[meta]        |object|`{}`  |    

###### integer

Same as number with additional restriction of default value of `multipleOf` = 1

###### string

keyword members:

key           | type  | default    | description
--------------|-------|------------|-------------
type          |string ||
[order]       |integer||
title         |string ||
description   |string ||
[minLength]   |integer|`0`|
[maxLength]   |integer|`null`|
[meta]        |object |`{}`|

###### date
SQL date `Y-m-d` for example `"2015-03-09"`

keyword members:

key            | type  | default    | description
---------------|-------|------------|-------------
type           |string ||
[order]        |integer||
title          |string ||
description    |string ||
[formatMinimum]|string |`null`|
[formatMaximum]|string |`null`|

###### date-type
SQL date `Y-m-d H:i:s` for example `"2015-03-09 00:10:00"`

keyword members:

key            | type | default    | description
---------------|------|------------|-------------
type           |string||
[order]        |integer||
title          |string||
description    |string||
[formatMinimum]|string|`null`|
[formatMaximum]|string|`null`|

###### array
Arrays allow the validation of lists, for purposes of `data_template` we use array combined with enum to display a multi selection component.

keyword members:

key           | type  | default    | description
--------------|-------|------------|-------------
type          |string ||
items         |object |`null`|Describes what elements are allowed to use as array items, must be a valid schema, only type `"enum"` is supported.
[order]       |integer||
title         |string ||
description   |string ||
uniqueItems   |bool   |`false`|Require unique items
[minItems]    |integer|`0`|
[maxItems]    |integer|`null`|
[meta]        |object |`{}`|

Example:
```json
{
  "type": "object",
  "properties": {
    "my_multi_select": {
      "type": "array",
      "minItems": 1,
      "maxItems": 2,
      "title": "Demo multi select (array)",
      "description": "Pick 1 or 2 options",
      "items": {
        "type": "enum",
        "enum": [
          "one",
          "two",
          "three",
          "four"
        ],
        "meta": {
          "enum-titles": {
            "one": "one",
            "two": "two",
            "three": "three",
            "four": "four"
          }
        }
      },
      "uniqueItems": true
    }
  }
}
```
