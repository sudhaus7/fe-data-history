<?php
declare(strict_types=1);
/**
 * Created by: markus
 * Created at: 30.01.20 15:06
 */

namespace SUDHAUS7\FeDataHistory\Controller\Backend;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\DataHandling\History\RecordHistoryStore;

/**
 * Class ElementHistoryController
 * @package SUDHAUS7\FeDataHistory\Controller\Backend
 */
class ElementHistoryController extends \TYPO3\CMS\Backend\Controller\ContentElement\ElementHistoryController
{
    /**
     * Shows the full change log
     *
     * @param array $historyEntries
     */
    protected function displayHistory(array $historyEntries)
    {
        if (empty($historyEntries)) {
            return;
        }
        $languageService = $this->getLanguageService();
        $lines = [];
        $beUserArray = BackendUtility::getUserNames();

        $feUserArray = [];

        // Traverse changeLog array:
        foreach ($historyEntries as $entry) {
            // Build up single line
            $singleLine = [];

            // Get user names
            if ($entry['usertype'] == "FE") {
                if (array_key_exists($entry['userid'], $feUserArray)) {
                    $feUser = $feUserArray[$entry['userid']];
                } else {
                    $feUser = BackendUtility::getRecord('fe_users', $entry['userid'], 'uid,username');
                    $feUserArray[$feUser['uid']] = $feUser;
                }
                $singleLine['backendUserUid'] = $feUser['uid'];
                $singleLine['backendUserName'] = 'FE-User: ' .($feUser['uid'] ? $feUser['username'] : '');
            } else {
                $singleLine['backendUserUid'] = $entry['userid'];
                $singleLine['backendUserName'] = $entry['userid'] ? $beUserArray[$entry['userid']]['username'] : '';
            }
            // Executed by switch user
            if (!empty($entry['originaluserid'])) {
                $singleLine['originalBackendUserName'] = $beUserArray[$entry['originaluserid']]['username'];
            }

            // Diff link
            $singleLine['diffUrl'] = $this->buildUrl(['historyEntry' => $entry['uid']]);
            // Add time
            $singleLine['time'] = BackendUtility::datetime($entry['tstamp']);
            // Add age
            $singleLine['age'] = BackendUtility::calcAge($GLOBALS['EXEC_TIME'] - $entry['tstamp'], $languageService->sL('LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.minutesHoursDaysYears'));

            $singleLine['title'] = $this->generateTitle($entry['tablename'], $entry['recuid']);
            $singleLine['elementUrl'] = $this->buildUrl(['element' => $entry['tablename'] . ':' . $entry['recuid']]);
            $singleLine['actiontype'] = $entry['actiontype'];
            if ((int)$entry['actiontype'] === RecordHistoryStore::ACTION_MODIFY) {
                // show changes
                if (!$this->showDiff) {
                    // Display field names instead of full diff
                    // Re-write field names with labels
                    $tmpFieldList = array_keys($entry['newRecord']);
                    foreach ($tmpFieldList as $key => $value) {
                        $tmp = str_replace(':', '', $languageService->sL(BackendUtility::getItemLabel($entry['tablename'], $value)));
                        if ($tmp) {
                            $tmpFieldList[$key] = $tmp;
                        } else {
                            // remove fields if no label available
                            unset($tmpFieldList[$key]);
                        }
                    }
                    $singleLine['fieldNames'] = implode(',', $tmpFieldList);
                } else {
                    // Display diff
                    $singleLine['differences'] = $this->renderDiff($entry, $entry['tablename']);
                }
            }
            // put line together
            $lines[] = $singleLine;
        }
        $this->view->assign('history', $lines);
    }
}
