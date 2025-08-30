<?php

declare(strict_types=1);

namespace SUDHAUS7\FeDataHistory\EventListener;

use SUDHAUS7\FeDataHistory\Domain\HistoryEntityInterface;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\Event\Persistence\EntityUpdatedInPersistenceEvent;
use TYPO3\CMS\Extbase\Persistence\Generic\Exception;
use TYPO3\CMS\Extbase\Persistence\Generic\Exception\TooDirtyException;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * With this EventListener a sys_history entry is added when a frontend user changes
 * an Extbase entity.
 * @see EntityUpdatedInPersistenceEvent
 */
final class EntityUpdatedListener extends AbstractEntityEventListener
{
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

    /**
     * @param DomainObjectInterface $object
     * @throws TooDirtyException
     * @throws Exception|AspectNotFoundException
     */
    protected function writeModified(DomainObjectInterface $object): void
    {
        $oldRecord = [];
        $newRecord = [];
        foreach ($object->_getProperties() as $property => $value) {
            if ($object->_isDirty($property)) {
                $dbProperty = $this->getDbFieldName($property, $object);
                if ($value instanceof ObjectStorage) {
                    $oldValue = [];
                    /** @var AbstractEntity $cleanObject */
                    foreach ($object->_getCleanProperty($property) as $cleanObject) {
                        $oldValue[] = $cleanObject->getUid();
                    }
                    $newValue = [];
                    /** @var AbstractEntity $dirtyObject */
                    foreach ($value as $dirtyObject) {
                        $newValue[] = sprintf('%s_%s', $this->getTableName($dirtyObject), $dirtyObject->getUid());
                    }
                    $oldRecord[$dbProperty] = implode(',', $oldValue);
                    $newRecord[$dbProperty] = implode(',', $newValue);
                } elseif ($value instanceof FileReference) {
                    $oldRecord[$dbProperty] = ($object->_getCleanProperty($property) ? $object->_getCleanProperty($property)->getUid() : null);
                    $newRecord[$dbProperty] = $value->getUid();
                } else {
                    $oldRecord[$dbProperty] = $object->_getCleanProperty($property);
                    $newRecord[$dbProperty] = $value;
                }
            }
        }

        if (count($oldRecord) > 0 && count($newRecord) > 0) {
            $oldRecord['l10n_diffsource'] = serialize(
                []
            );
            $newRecord['l10n_diffsource'] = serialize(
                []
            );

            $tableName = $this->getTableName($object);

            if (!empty($tableName)) {
                $this->getRecordHistoryStore()->modifyRecord($tableName, (int)$object->getUid(), ['oldRecord' => $oldRecord, 'newRecord' => $newRecord]);
            }
        }
    }
}
