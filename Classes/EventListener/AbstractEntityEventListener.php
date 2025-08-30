<?php

declare(strict_types=1);

namespace SUDHAUS7\FeDataHistory\EventListener;

use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\DataHandling\History\RecordHistoryStore;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Exception;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;

/**
 * Class HistoryRecord
 */
abstract class AbstractEntityEventListener
{
    protected ?DataMapper $dataMapper = null;

    public function injectDataMapper(DataMapper $dataMapper): void
    {
        $this->dataMapper = $dataMapper;
    }

    /**
     * @throws AspectNotFoundException
     */
    protected function getRecordHistoryStore(): RecordHistoryStore
    {
        /** @var Context $context */
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
     * @param DomainObjectInterface $obj
     * @return string
     * @throws Exception
     */
    protected function getTableName(DomainObjectInterface $obj): string
    {
        return (string)$this->dataMapper?->getDataMap(\get_class($obj))->getTableName();
    }

    /**
     * @param string $property
     * @param DomainObjectInterface $obj
     * @return string
     */
    protected function getDbFieldName(string $property, DomainObjectInterface $obj): string
    {
        return (string)$this->dataMapper?->convertPropertyNameToColumnName($property, \get_class($obj));
    }
}
