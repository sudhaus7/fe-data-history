<?php
declare(strict_types=1);
/**
 * Created by: markus
 * Created at: 30.01.20 15:08
 */

namespace SUDHAUS7\FeDataHistory\Hooks;

use SUDHAUS7\FeDataHistory\Domain\HistoryEntityInterface;
use SUDHAUS7\FeDataHistory\Traits\HistoryRecord;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\Generic\Exception\TooDirtyException;

/**
 * Class PersistenceBackendHook
 * @package SUDHAUS7\FeDataHistory\Hooks
 */
class PersistenceBackendHook
{
    use HistoryRecord;
    /**
     * @param AbstractEntity $object
     * @return array
     * @throws TooDirtyException
     */
    public function afterUpdate(AbstractEntity $object)
    {
        if ($object instanceof HistoryEntityInterface) {
            if ($object->_isDirty()) {
                $this->writeModified($object);
            }
        }

        return [$object];
    }

    /**
     * @param AbstractEntity $object
     * @return array
     */
    public function endInsert(AbstractEntity $object)
    {
        if ($object instanceof HistoryEntityInterface) {
            $this->writeNew($object);
        }

        return [$object];
    }

    /**
     * @param AbstractEntity $object
     * @return array
     */
    public function afterRemove(AbstractEntity $object)
    {
        if ($object instanceof HistoryEntityInterface) {
            $this->writeDeleted($object);
        }

        return [$object];
    }
}
