<?php

declare(strict_types=1);

call_user_func(function (): void {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'WORKSHOP.WorkshopBlog',
        'List',
        'Workshop Blog List',
        'EXT:workshop_blog/Resources/Public/Icons/Extension.svg'
    );
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'WORKSHOP.WorkshopBlog',
        'Latest',
        'Workshop Blog Latest',
        'EXT:workshop_blog/Resources/Public/Icons/Extension.svg'
    );
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'WORKSHOP.WorkshopBlog',
        'Detail',
        'Workshop Blog Detail',
        'EXT:workshop_blog/Resources/Public/Icons/Extension.svg'
    );
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'WORKSHOP.WorkshopBlog',
        'Edit',
        'Workshop Blog Edit',
        'EXT:workshop_blog/Resources/Public/Icons/Extension.svg'
    );

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['workshopblog_list'] = 'pi_flexform';
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        'workshopblog_list',
        'FILE:EXT:workshop_blog/Configuration/Flexforms/Flexform.xml'
    );

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['workshopblog_latest'] = 'pi_flexform';
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        'workshopblog_latest',
        'FILE:EXT:workshop_blog/Configuration/Flexforms/Flexform.xml'
    );
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['workshopblog_detail'] = 'pi_flexform';
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        'workshopblog_detail',
        'FILE:EXT:workshop_blog/Configuration/Flexforms/FlexformEdit.xml'
    );
});
