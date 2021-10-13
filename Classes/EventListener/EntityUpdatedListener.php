<?php

/**
 * Markus Hofmann
 * 13.10.21 11:15
 * churchevent
 */

declare(strict_types=1);

namespace SUDHAUS7\FeDataHistory\EventListener;

use SUDHAUS7\FeDataHistory\Domain\HistoryEntityInterface;
use SUDHAUS7\FeDataHistory\Traits\HistoryRecord;
use TYPO3\CMS\Extbase\Event\Persistence\EntityUpdatedInPersistenceEvent;

class EntityUpdatedListener
{
    use HistoryRecord;

    /**
     * __invoke
     * @param EntityUpdatedInPersistenceEvent $event
     * @throws \TYPO3\CMS\Extbase\Object\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\TooDirtyException
     */
    public function __invoke(EntityUpdatedInPersistenceEvent $event): void
    {
        $object = $event->getObject();
        if ($object instanceof HistoryEntityInterface) {
            if ($object->_isDirty()) {
                $this->writeModified($object);
            }
        }
    }
}
