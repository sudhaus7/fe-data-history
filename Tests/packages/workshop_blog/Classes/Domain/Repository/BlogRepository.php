<?php

namespace WORKSHOP\WorkshopBlog\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;
use WORKSHOP\WorkshopBlog\Domain\Model\Blog;

/**
 * @extends Repository<Blog>
 */
final class BlogRepository extends Repository
{
    protected $defaultOrderings = [
        'date' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING,
    ];
}
