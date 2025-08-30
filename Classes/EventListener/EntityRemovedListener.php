<?php

declare(strict_types=1);

namespace SUDHAUS7\FeDataHistory\EventListener;

use SUDHAUS7\FeDataHistory\Domain\HistoryEntityInterface;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\Event\Persistence\EntityRemovedFromPersistenceEvent;
use TYPO3\CMS\Extbase\Persistence\Generic\Exception;

/**
 * This event listener triggers the sys_history entry, if a frontend user deletes a dataset
 * via extbase persistence.
 * @see EntityRemovedFromPersistenceEvent
 */
final class EntityRemovedListener extends AbstractEntityEventListener
{
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

    /**
     * @param DomainObjectInterface $object
     * @throws Exception|AspectNotFoundException
     */
    private function writeDeleted(DomainObjectInterface $object): void
    {
        $tableName = $this->getTableName($object);
        if (!empty($tableName)) {
            $this->getRecordHistoryStore()->deleteRecord($tableName, (int)$object->getUid());
        }
    }
}
