<?php

/**
 * Markus Hofmann
 * 13.10.21 11:32
 */

declare(strict_types=1);

namespace SUDHAUS7\FeDataHistory\EventListener;

use SUDHAUS7\FeDataHistory\Domain\HistoryEntityInterface;
use SUDHAUS7\FeDataHistory\Traits\HistoryRecord;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Extbase\Event\Persistence\EntityRemovedFromPersistenceEvent;
use TYPO3\CMS\Extbase\Persistence\Generic\Exception;

class EntityRemovedListener
{
    use HistoryRecord;

    /**
     * @throws AspectNotFoundException
     * @throws Exception
     */
    public function __invoke(EntityRemovedFromPersistenceEvent $event): void
    {
        $object = $event->getObject();
        if ($object instanceof HistoryEntityInterface) {
            $this->writeDeleted($object);
        }
    }
}
