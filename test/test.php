#!/bin/php
<?php

require __DIR__ . '/../vendor/autoload.php';

$schemaDirectory = dirname(__DIR__) . '/data_template';

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
    new \Phramework\Exceptions\Source\Pointer('')
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

/**
 * @property int $files
 */
$stats = (object) [
    'files'   => 0,
    'success' => 0,
    'failure' => 0
];

foreach($dataTemplates as $template) {
    echo $template . PHP_EOL;

    $stats->files++;

    try {
        $validator->parse(json_decode(file_get_contents($template)));

        $stats->success++;
        /*} catch (\Phramework\Exceptions\IncorrectParametersException $e) {
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
            goto error;*/
    } catch (Exception $e) {
        $stats->failure++;

        echo $e->getMessage() . PHP_EOL;
        switch (get_class($e)) {
            case \Phramework\Exceptions\IncorrectParameterException::class:
                print_r(([
                    $e->getSource(),
                    $e->getFailure(),
                    $e->getDetail()
                ]));
                break;
            case \Phramework\Exceptions\IncorrectParametersException::class:
                foreach ($e->getExceptions() as $ex) {
                    print_r(([
                        $ex->getSource(),
                        $ex->getFailure(),
                        $ex->getDetail()
                    ]));
                }
                break;
            case \Phramework\Exceptions\MissingParametersException::class:
                print_r([
                    $e->getSource(),
                    $e->getParameters()
                ]);
                break;
            default:
                var_dump($e);
                break;
        }
        echo PHP_EOL;
    }
}

echo sprintf(
    'Files: %s, Success: %s, Failure %s',
    $stats->files,
    $stats->success,
    $stats->failure
) . PHP_EOL;

if ($stats->failure > 0) {
    return 1;
}

return 0;