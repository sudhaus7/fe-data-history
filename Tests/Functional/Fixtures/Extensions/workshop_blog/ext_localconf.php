<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use WORKSHOP\WorkshopBlog\Controller\DetailController;
use WORKSHOP\WorkshopBlog\Controller\EditController;
use WORKSHOP\WorkshopBlog\Controller\LatestController;
use WORKSHOP\WorkshopBlog\Controller\ListController;

(static function (): void {
    ExtensionUtility::configurePlugin(
        'WorkshopBlog',
        'List',
        [
            ListController::class => 'index',
        ],
        [],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );
    ExtensionUtility::configurePlugin(
        'WorkshopBlog',
        'Latest',
        [
            LatestController::class => 'index',
        ],
        [],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );
    ExtensionUtility::configurePlugin(
        'WorkshopBlog',
        'Detail',
        [

            DetailController::class => 'detail,savecomment',
        ],
        [],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );

    ExtensionUtility::configurePlugin(
        'WorkshopBlog',
        'Edit',
        [
            EditController::class => 'edit,save',
        ],
        [
            'Edit' => 'edit,save',
        ],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );
})();
