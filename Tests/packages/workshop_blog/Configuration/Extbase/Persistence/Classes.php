<?php

use WORKSHOP\WorkshopBlog\Domain\Model\Blog;
use WORKSHOP\WorkshopBlog\Domain\Model\Comment;

return [
    Comment::class => [
        'tableName' => 'tx_workshopblog_domain_model_comment',
        'properties' => [
            'tstamp' => ['fieldName' => 'tstamp'],
        ],
    ],
    Blog::class => [
        'tableName' => 'tx_workshopblog_domain_model_blog',
        'properties' => [
            'tstamp' => ['fieldName' => 'tstamp'],
        ],
    ],
];
