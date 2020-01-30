<?php
declare(strict_types=1);
/**
 * Created by: markus
 * Created at: 30.01.20 15:09
 */

namespace SUDHAUS7\FeDataHistory\Traits;

use SUDHAUS7\FeDataHistory\History\RecordHistoryStore;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Class HistoryRecord
 * @package SUDHAUS7\FeDataHistory\Traits
 */
trait HistoryRecord
{
    /**
     * @return RecordHistoryStore
     */
    protected function getRecordHistoryStore(): RecordHistoryStore
    {
        return GeneralUtility::makeInstance(
            RecordHistoryStore::class,
            RecordHistoryStore::USER_FRONTEND,
            $GLOBALS['TSFE']->fe_user->user['uid'],
            null,
            time(),
            0
        );
    }

    /**
     * @param AbstractEntity $object
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\TooDirtyException
     */
    protected function writeModified(AbstractEntity $object)
    {
        $oldRecord = [];
        $newRecord = [];
        foreach ($object->_getProperties() as $property => $value) {
            if ($object->_isDirty($property)) {
                $dbProperty = GeneralUtility::camelCaseToLowerCaseUnderscored($property);
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

        if (count($oldRecord)>0 && count($newRecord) > 0) {
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
     * @param AbstractEntity $object
     */
    protected function writeNew(AbstractEntity $object)
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
     * @param AbstractEntity $object
     */
    protected function writeDeleted(AbstractEntity $object)
    {
        $tableName = $this->getTableName($object);
        if (!empty($tableName)) {
            $this->getRecordHistoryStore()->deleteRecord($tableName, $object->getUid());
        }
    }

    /**
     * @param AbstractEntity $obj
     * @return string
     */
    private function getTableName(AbstractEntity $obj)
    {
        $tableName = '';

        $class = get_class($obj);
        $classArray = explode('\\', $class);
        array_shift($classArray);
        foreach ($classArray as &$name) {
            $name = GeneralUtility::camelCaseToLowerCaseUnderscored($name);
        }

        $model = array_pop($classArray);
        $extension = array_shift($classArray);
        $extension = strtolower(GeneralUtility::underscoredToLowerCamelCase($extension));

        if (!empty($model) && !empty($extension)) {
            $tableName = sprintf('tx_%1$s_domain_model_%2$s', $extension, $model);
        }

        return $tableName;
    }
}
