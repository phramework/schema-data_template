# schema-data_template
A collection of a demo schema data_templates, used to test visually and validate forms created for phramework/validate library

[![Build Status](https://travis-ci.org/phramework/schema-data_template.svg?branch=master)](https://travis-ci.org/phramework/schema-data_template)

## Instruction
A client may fetch `https://raw.githubusercontent.com/phramework/schema-data_template/master/index.json` and display the available templates in a list.
Templates are group into categories, each category contains a list of templates.
Each list template on selection should fetch the schema file from `/link` and render it.

For example:
```json
{
    "root": [
        {
            "id": "84aa5c",
            "title": "A form with enum",
            "description": "This is a simple form containing a single enum",
            "link": "https:\/\/raw.githubusercontent.com\/phramework\/schema-data_template\/master\/data_template\/enum.json"
        }
    ],
    "\/x-visibility": [
        {
            "id": "4922b9",
            "title": "A form with x-visibility",
            "description": "This is a simple form containing a enum and a number with x-visibility",
            "link": "https:\/\/raw.githubusercontent.com\/phramework\/schema-data_template\/master\/data_template\/x-visibility\/enum-number.json"
        }
    ]
}
```

## Development

### test `data_template`s in `./data_template` directory

Requires: 
- PHP 7 or newer
- [composer](https://getcomposer.org/)

To install composer dependencies:
```bash
composer update
```

To run the tests:
```bash
composer test
```

## generate new `index.json`
execute
```bash
composer generate
```

## Contributing

Read CONTRIBUTING.md for contribution guidelines

## License
Copyright 2016 Xenofon Spafaridis

Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at

```
http://www.apache.org/licenses/LICENSE-2.0
```

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.