<?php

require __DIR__ . '/../vendor/autoload.php';

$schemaDirectory = __DIR__ . '/../data_template/';

$schemaValidatorFilePath = __DIR__ . '/../validator/schema-data_template.json';

if (!file_exists($schemaValidatorFilePath)) {
    throw new Exception('Schema file not found');
}

$schema = file_get_contents(
    $schemaValidatorFilePath
);


$validator = \Phramework\Validate\BaseValidator::createFromJSON(
    $schema
)->setSource(
    new \Phramework\Exceptions\Source\Pointer('/')
);

$dataTemplates = \Phramework\Util\File::directoryToArray(
    $schemaDirectory,
    true,
    false,
    true,
    '/^\.|\.\.$/',
    ['json'],
    false,
    false
);

foreach($dataTemplates as $template) {
    echo $template . PHP_EOL;

    try {
        $validator->parse(json_decode(file_get_contents($template)));
    } catch (\Phramework\Exceptions\IncorrectParametersException $e) {
        echo $e->getMessage() . PHP_EOL;

        foreach ($e->getExceptions() as $e) {
            print_r(json_encode([
                $e->getSource(),
                $e->getFailure(),
                $e->getDetail()
            ]));
        }

        goto error;
    } catch (\Phramework\Exceptions\IncorrectParameterException $e) {
        echo $e->getMessage() . PHP_EOL;


        goto error;
    } catch (\Phramework\Exceptions\MissingParametersException $e) {
        echo $e->getMessage() . PHP_EOL;
        print_r([
            $e->getSource(),
            $e->getParameters()
        ]);
        goto error;
    } catch (Exception $e) {
        echo $e->getMessage() . PHP_EOL;
        var_dump($e);
        goto error;
    }
}

return 0;

error:

return 1;