<?php

$data = [
    'name' => 'Igor',
    'index' => '57749',
    'date' => date(DATE_ATOM),
];

$yaml = yaml_emit($data);

echo $yaml;