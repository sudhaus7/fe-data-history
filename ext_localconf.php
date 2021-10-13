<?php
/**
 * Created by: markus
 * Created at: 30.01.20 15:02
 */

(function () {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Backend\Controller\ContentElement\ElementHistoryController::class] = [
        'className' => \SUDHAUS7\FeDataHistory\Controller\Backend\ElementHistoryController::class
    ];
})();
