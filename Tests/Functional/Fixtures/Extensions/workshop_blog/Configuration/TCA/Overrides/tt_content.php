<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

(static function (): void {
    ExtensionUtility::registerPlugin(
        'WorkshopBlog',
        'List',
        'Workshop Blog List',
        'EXT:workshop_blog/Resources/Public/Icons/Extension.svg'
    );
    ExtensionUtility::registerPlugin(
        'WorkshopBlog',
        'Latest',
        'Workshop Blog Latest',
        'EXT:workshop_blog/Resources/Public/Icons/Extension.svg'
    );
    ExtensionUtility::registerPlugin(
        'WorkshopBlog',
        'Detail',
        'Workshop Blog Detail',
        'EXT:workshop_blog/Resources/Public/Icons/Extension.svg'
    );
    ExtensionUtility::registerPlugin(
        'WorkshopBlog',
        'Edit',
        'Workshop Blog Edit',
        'EXT:workshop_blog/Resources/Public/Icons/Extension.svg'
    );

    ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        '--div--;Configuration,pi_flexform,',
        'workshopblog_list',
        'after:subheader',
    );
    ExtensionManagementUtility::addPiFlexFormValue(
        '*',
        'FILE:EXT:workshop_blog/Configuration/Flexforms/Flexform.xml',
        'workshopblog_list'
    );

    ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        '--div--;Configuration,pi_flexform,',
        'workshopblog_latest',
        'after:subheader',
    );
    ExtensionManagementUtility::addPiFlexFormValue(
        '*',
        'FILE:EXT:workshop_blog/Configuration/Flexforms/Flexform.xml',
        'workshopblog_latest'
    );
    ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        '--div--;Configuration,pi_flexform,',
        'workshopblog_detail',
        'after:subheader',
    );
    ExtensionManagementUtility::addPiFlexFormValue(
        '*',
        'FILE:EXT:workshop_blog/Configuration/Flexforms/FlexformEdit.xml',
        'workshopblog_detail'
    );
})();
