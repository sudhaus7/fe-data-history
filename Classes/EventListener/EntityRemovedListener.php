<?php

/**
 * Markus Hofmann
 * 13.10.21 11:32
 * churchevent
 */

declare(strict_types=1);

namespace SUDHAUS7\FeDataHistory\EventListener;

use SUDHAUS7\FeDataHistory\Domain\HistoryEntityInterface;
use SUDHAUS7\FeDataHistory\Traits\HistoryRecord;
use TYPO3\CMS\Extbase\Event\Persistence\EntityRemovedFromPersistenceEvent;

class EntityRemovedListener
{
    use HistoryRecord;

    public function __invoke(EntityRemovedFromPersistenceEvent $event)
    {
        $object = $event->getObject();
        if ($object instanceof HistoryEntityInterface) {
            $this->writeDeleted($object);
        }
    }
}
