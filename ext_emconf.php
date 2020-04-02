<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'FE Data History',
    'description' => 'Saves history for frontend edited Extbase models',
    'category' => 'frontend',
    'state' => 'beta',
    'author' => 'Markus Hofmann',
    'author_email' => 'mhofmann@sudhaus7.de',
    'version' => '1.0.3',
    'shy' => '',
    'dependencies' => 'cms',
    'conflicts' => '',
    'priority' => '',
    'module' => '',
    'internal' => '',
    'uploadfolder' => 0,
    'createDirs' => '',
    'modify_tables' => '',
    'clearCacheOnLoad' => 0,
    'lockType' => '',
    'author_company' => 'Sudhaus7',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.13-10.2.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
];
