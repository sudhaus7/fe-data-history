<?php

namespace WORKSHOP\WorkshopBlog\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;
use WORKSHOP\WorkshopBlog\Domain\Model\Comment;

/**
 * @extends Repository<Comment>
 */
final class CommentRepository extends Repository
{
    protected $defaultOrderings = [
        'date' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING,
    ];
}
