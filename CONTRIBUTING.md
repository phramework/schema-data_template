# `data_template`'s
Pushes to `master` branch require successful pass of tests.

Tests can be executed using `composer test`, *NOTE* [composer](https://getcomposer.org/) and PHP 7 is required see README.
Or they are automatically executed on commits at [Travis CI](https://travis-ci.org/phramework/schema-data_template)
The test validate's the definition of JSON schema in `data_template/` directory.

New data template MUST follow the specification available at [specification/data_template.md](https://github.com/phramework/schema-data_template/blob/master/specification/data_template.md)

When witting additional specifications and features additional tests must be written.

When changing file files or file structure at data_template, before creating a push request
"generate new `index.json`" MUST be executed to generate a fresh `index.json`

execute
```bash
composer generate
```

Push requests with failed CI build status cannot be accepted.

# JSON
- JSON files must be formatted using "pretty print" with 2 spaces indentation.
 
# PHP Validator and tests
All php code MUST follow
- [PSR-2: Coding Style Guide](http://www.php-fig.org/psr/psr-2/) 
- [PSR-4: Autoloader](http://www.php-fig.org/psr/psr-4/) specification