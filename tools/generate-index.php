#!/bin/php
<?php

require __DIR__ . '/../vendor/autoload.php';

$schemaDirectory = dirname(__DIR__) . '/data_template/';

$indexFilePath = dirname(__DIR__) . '/index.json';

if (!file_exists($indexFilePath)) {
    throw new Exception('Index file not found');
}

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

$structure = (object) [
    'data' => []
];

$linkPrefix = 'https://raw.githubusercontent.com/phramework/schema-data_template/master/data_template/';

foreach($dataTemplates as $templateFilePath) {
    $templateFileName = str_replace('//', '/', $templateFilePath);
    $templateFileName = str_replace($schemaDirectory, '' , $templateFileName);

    $template = json_decode(file_get_contents($templateFilePath));

    $id = substr(md5($templateFileName), 0 , 6);

    $structure->data[] = (object) [
        'id' => $id,
        'attributes' => (object) [
            'title'       => $template->title,
            'description' => $template->description,
            //todo fix urlencode of /
            'link'        => $linkPrefix . urlencode($templateFileName)
        ]
    ];
}

file_put_contents(
    $indexFilePath,
    json_encode($structure, JSON_PRETTY_PRINT)
);

echo "index.json is updated";