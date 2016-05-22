# schema-data_template
A collection of a demo schema data_templates, used to test visually and validate forms created for phramework/validate library

## Instruction
A client may fetch `https://raw.githubusercontent.com/phramework/schema-data_template/master/index.json` and display the available templates (`/data`) in a list.
Each list item on selection should fetch the schema file from `/data/attributes/link` path and render it.

For example:
```json
{
  "data": [
    {
      "id": "1",
      "attributes": {
        "link": "https://raw.githubusercontent.com/phramework/schema-data_template/master/data_template/enum.json",
        "title": "A form with a single enum"
      }
    }
  ]
}
```

## Development

### test `data_template`s in `./data_template` directory

Requires: 
- php 7 or newer
- composer

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
composer generate-index
```