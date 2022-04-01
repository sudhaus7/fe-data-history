<?php

declare(strict_types=1);

/**
 * Created by: markus
 * Created at: 30.01.20 15:09
 */

namespace SUDHAUS7\FeDataHistory\Traits;

use SUDHAUS7\FeDataHistory\History\RecordHistoryStore;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Exception;
use TYPO3\CMS\Extbase\Persistence\Generic\Exception\TooDirtyException;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Class HistoryRecord
 * @package SUDHAUS7\FeDataHistory\Traits
 */
trait HistoryRecord
{
    /**
     * dataMapper
     *
     * @var DataMapper
     */
    protected $dataMapper = null;

    /**
     * @param DataMapper $dataMapper
     */
    public function injectDataMapper(DataMapper $dataMapper)
    {
         = $dataMapper;
    }
    /**
     * @return RecordHistoryStore
     * @throws AspectNotFoundException
     */
    protected function getRecordHistoryStore(): RecordHistoryStore
    {
        $context = GeneralUtility::makeInstance(Context::class);
        $frontendUserID = (int)$context->getPropertyFromAspect('frontend.user', 'id');
        return GeneralUtility::makeInstance(
            RecordHistoryStore::class,
            RecordHistoryStore::USER_FRONTEND,
            $frontendUserID,
            null,
            time(),
            0
        );
    }

    /**
     * @param DomainObjectInterface $object
     * @throws TooDirtyException
     * @throws Exception|AspectNotFoundException
     */
    protected function writeModified(DomainObjectInterface $object)
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
                $this->getRecordHistoryStore()->modifyRecord($tableName, $object->getUid(), ['oldRecord' => $oldRecord, 'newRecord' => $newRecord]);
            }
        }
    }

    /**
     * @param DomainObjectInterface $object
     * @throws Exception|AspectNotFoundException
     */
    protected function writeNew(DomainObjectInterface $object)
    {
        $newRecord = [];
        foreach ($object->_getProperties() as $property => $value) {
            $newRecord[$property] = $value;
        }

        if (count($newRecord) > 0) {
            $tableName = $this->getTableName($object);

            if (!empty($tableName)) {
                $this->getRecordHistoryStore()->addRecord($tableName, $object->getUid(), ['newRecord' => $newRecord]);
            }
        }
    }

    /**
     * @param DomainObjectInterface $object
     * @throws Exception|AspectNotFoundException
     */
    protected function writeDeleted(DomainObjectInterface $object)
    {
        $tableName = $this->getTableName($object);
        if (!empty($tableName)) {
            $this->getRecordHistoryStore()->deleteRecord($tableName, $object->getUid());
        }
    }

    /**
     * @param DomainObjectInterface $obj
     * @return string
     * @throws Exception
     */
    private function getTableName(DomainObjectInterface $obj): string
    {
        return $this->dataMapper->getDataMap(\get_class($obj))->getTableName();
    }

    /**
     * @param string $property
     * @param DomainObjectInterface $obj
     * @return string
     */
    private function getDbFieldName(string $property, DomainObjectInterface $obj): string
    {
        return $this->dataMapper->convertPropertyNameToColumnName($property, \get_class($obj));
    }
}
