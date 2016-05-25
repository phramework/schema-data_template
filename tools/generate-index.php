#!/bin/php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Phramework\Util\File;

$schemaDirectory = dirname(__DIR__) . '/data_template';

$indexFilePath = dirname(__DIR__) . '/index.json';

if (!file_exists($indexFilePath)) {
    throw new Exception('Index file not found');
}

$linkPrefix = 'https://raw.githubusercontent.com/phramework/schema-data_template/master/data_template/';

$list = function (&$structure, $categoryKey, $files) use ($linkPrefix, $schemaDirectory)
{
    $structure->{$categoryKey} = [];

    foreach ($files as $templateFilePath) {
        $templateFileName = str_replace('//', '/', $templateFilePath);
        $templateFileName = str_replace($schemaDirectory, '', $templateFileName);

        $template = json_decode(file_get_contents($templateFilePath));

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception(sprintf(
                '%s is not a valid JSON',
                $templateFilePath
            ));
        }

        $id = substr(md5($templateFileName), 0, 6);

        //trim spaces and slashes
        $templateFileName = trim($templateFileName, '/');

        //push
        $structure->{$categoryKey}[] = (object) [
            'id'          => $id,
            'title'       => $template->title,
            'description' => $template->description,
            //todo fix urlencode of /
            'link'        => $linkPrefix . implode('/', array_map('urlencode', explode('/', $templateFileName)))
        ];
    }
};

$structure = (object) [];

//list root files

$rootFiles = File::directoryToArray(
    $schemaDirectory,
    false,
    false,
    true,
    '/^\.|\.\.$/',
    ['json'],
    false,
    false
);

$list($structure, 'root', $rootFiles);

//list root directories

$directories = File::directoryToArray(
    $schemaDirectory,
    false,
    true,
    false,
    '/^\.|\.\.$/',
    ['json'],
    false,
    false
);

foreach ($directories as $directory) {
    $directoryFiles = File::directoryToArray(
        $directory,
        true,
        false,
        true,
        '/^\.|\.\.$/',
        ['json'],
        false,
        false
    );

    if (empty($directoryFiles)) {
        continue;
    }

    $list($structure, str_replace($schemaDirectory, '' ,$directory), $directoryFiles);
}

file_put_contents(
    $indexFilePath,
    json_encode($structure, JSON_PRETTY_PRINT)
);

echo 'index.json is updated' . PHP_EOL;