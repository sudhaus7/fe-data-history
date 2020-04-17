<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'FE Data History',
    'description' => 'Saves history for frontend edited Extbase models',
    'category' => 'fe',
    'state' => 'beta',
    'author' => 'Markus Hofmann & Frank Berger',
    'author_email' => 'mhofmann@sudhaus7.de',
    'version' => '1.0.8',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'author_company' => 'Sudhaus7',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.13-10.4.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'SUDHAUS7\\FeDataHistory\\' => 'Classes'
        ]
    ],
];
