<?php

/**
 * Markus Hofmann
 * 13.10.21 11:29
 * churchevent
 */

declare(strict_types=1);

namespace SUDHAUS7\FeDataHistory\EventListener;

use SUDHAUS7\FeDataHistory\Domain\HistoryEntityInterface;
use SUDHAUS7\FeDataHistory\Traits\HistoryRecord;
use TYPO3\CMS\Extbase\Event\Persistence\EntityFinalizedAfterPersistenceEvent;

class EntityNewListener
{
    use HistoryRecord;

    /**
     * __invoke
     * @param EntityFinalizedAfterPersistenceEvent $event
     * @throws \TYPO3\CMS\Core\Context\Exception\AspectNotFoundException
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     */
    public function __invoke(EntityFinalizedAfterPersistenceEvent $event)
    {
        $object = $event->getObject();
        if ($object instanceof HistoryEntityInterface) {
            $this->writeNew($object);
        }
    }
}
