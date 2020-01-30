<?php
/**
 * Created by: markus
 * Created at: 30.01.20 15:02
 */

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Backend\Controller\ContentElement\ElementHistoryController::class] = [
    'className' => \SUDHAUS7\FeDataHistory\Controller\Backend\ElementHistoryController::class
];

/** @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher */

$signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);
$signalSlotDispatcher->connect(
    \TYPO3\CMS\Extbase\Persistence\Generic\Backend::class,
    'afterUpdateObject',
    \SUDHAUS7\FeDataHistory\Hooks\PersistenceBackendHook::class,
    'afterUpdate',
    false
);
$signalSlotDispatcher->connect(
    \TYPO3\CMS\Extbase\Persistence\Generic\Backend::class,
    'endInsertObject',
    \SUDHAUS7\FeDataHistory\Hooks\PersistenceBackendHook::class,
    'endInsert',
    false
);
$signalSlotDispatcher->connect(
    \TYPO3\CMS\Extbase\Persistence\Generic\Backend::class,
    'afterRemoveObject',
    \SUDHAUS7\FeDataHistory\Hooks\PersistenceBackendHook::class,
    'afterRemove',
    false
);