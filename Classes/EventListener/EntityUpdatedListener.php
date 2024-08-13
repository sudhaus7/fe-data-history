<?php

/**
 * Markus Hofmann
 * 13.10.21 11:15
 */

declare(strict_types=1);

namespace SUDHAUS7\FeDataHistory\EventListener;

use SUDHAUS7\FeDataHistory\Domain\HistoryEntityInterface;
use SUDHAUS7\FeDataHistory\Traits\HistoryRecord;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Extbase\Event\Persistence\EntityUpdatedInPersistenceEvent;
use TYPO3\CMS\Extbase\Persistence\Generic\Exception;
use TYPO3\CMS\Extbase\Persistence\Generic\Exception\TooDirtyException;

class EntityUpdatedListener
{
    use HistoryRecord;

    /**
     * __invoke
     * @param EntityUpdatedInPersistenceEvent $event
     * @throws AspectNotFoundException
     * @throws Exception
     * @throws TooDirtyException
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
