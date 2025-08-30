<?php

declare(strict_types=1);

namespace SUDHAUS7\FeDataHistory\EventListener;

use SUDHAUS7\FeDataHistory\Domain\HistoryEntityInterface;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\Event\Persistence\EntityFinalizedAfterPersistenceEvent;
use TYPO3\CMS\Extbase\Persistence\Generic\Exception;

/**
 * This event listener adds a sys_history record, if the given Extbase model
 * is added via frontend and has the {@see HistoryEntityInterface} added.
 * @see EntityFinalizedAfterPersistenceEvent
 */
final class EntityNewListener extends AbstractEntityEventListener
{
    /**
     * __invoke
     * @param EntityFinalizedAfterPersistenceEvent $event
     * @throws AspectNotFoundException
     * @throws Exception
     */
    public function __invoke(EntityFinalizedAfterPersistenceEvent $event): void
    {
        $object = $event->getObject();
        if ($object instanceof HistoryEntityInterface) {
            $this->writeNew($object);
        }
    }

    /**
     * @param DomainObjectInterface $object
     * @throws Exception|AspectNotFoundException
     */
    protected function writeNew(DomainObjectInterface $object): void
    {
        $newRecord = array_map(function ($value) {
            return $value;
        }, $object->_getProperties());

        if (count($newRecord) > 0) {
            $tableName = $this->getTableName($object);

            if (!empty($tableName)) {
                $this->getRecordHistoryStore()->addRecord($tableName, (int)$object->getUid(), ['newRecord' => $newRecord]);
            }
        }
    }
}
