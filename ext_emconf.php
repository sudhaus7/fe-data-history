<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Frontend Data History',
    'description' => 'Saves history for frontend edited Extbase models',
    'category' => 'fe',
    'state' => 'stable',
    'author' => 'Markus Hofmann & Frank Berger',
    'author_email' => 'fberger@sudhaus7.de',
    'version' => '3.0.0',
    'author_company' => 'Sudhaus7',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-13.4.99',
            'extbase' => '12.4.0-13.4.99',
            'beuser' => '12.4.0-13.4.99',
            'backend' => '12.4.0-13.4.99',
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
