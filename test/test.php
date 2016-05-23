#!/bin/php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Phramework\Exceptions\IncorrectParameterException;
use Phramework\Exceptions\IncorrectParametersException;
use Phramework\Exceptions\MissingParametersException;

$schemaDirectory = dirname(__DIR__) . '/data_template';

$dataTemplateValidator = new \Phramework\DataTemplate\Validator\DataTemplate();

$validator = $dataTemplateValidator->getValidator()
    ->setSource(
        new \Phramework\Exceptions\Source\Pointer('')
    );

//Get all .json files from data_template folder
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

    $stats->files++;\Phramework\Exceptions\IncorrectParameterException::class;

    try {
        $validator->parse(json_decode(file_get_contents($template)));

        $stats->success++;
    } catch (Exception $e) {
        $stats->failure++;

        echo $e->getMessage() . PHP_EOL;
        switch (get_class($e)) {
            case IncorrectParameterException::class:
                print_r(([
                    $e->getSource(),
                    $e->getFailure(),
                    $e->getDetail()
                ]));
                break;
            case IncorrectParametersException::class:
                foreach ($e->getExceptions() as $ex) {
                    $error = [$ex->getSource()];

                    echo $ex->getMessage() . PHP_EOL;

                    if (get_class($ex) == IncorrectParameterException::class) {
                        $error[] = $ex->getFailure();
                        $error[] = $ex->getDetail();
                    } elseif (get_class($ex) == MissingParametersException::class) {
                        $error[] = $ex->getParameters();
                    }

                    print_r($error);
                }
                break;
            case MissingParametersException::class:
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