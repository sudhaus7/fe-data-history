<?php

$EM_CONF['fe_data_history'] = [
    'title' => 'FE Data History',
    'description' => 'Saves history for frontend edited Extbase models',
    'category' => 'fe',
    'state' => 'beta',
    'author' => 'Markus Hofmann & Frank Berger',
    'author_email' => 'fberger@sudhaus7.de',
    'version' => '3.0.0',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'author_company' => 'Sudhaus7',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-12.4.99',
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
