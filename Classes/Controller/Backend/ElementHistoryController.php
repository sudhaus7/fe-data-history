<?php

declare(strict_types=1);

namespace SUDHAUS7\FeDataHistory\Controller\Backend;

use Override;
use TYPO3\CMS\Backend\Attribute\AsController;
use TYPO3\CMS\Backend\Controller\ContentElement\ElementHistoryController as Typo3ElementHistoryController;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\DataHandling\History\RecordHistoryStore;

/**
 * Override of the TYPO3 Core controlleer to add possibility showing Frontend related changes with user
 * data.
 * @internal no public API.
 */
#[AsController]
class ElementHistoryController extends Typo3ElementHistoryController
{
    /**
     * Shows the full change log
     *
     * This method is cloned and adjusted to display the frontend user changes
     * with the respective username.
     * @see Typo3ElementHistoryController::displayHistory()
     */
    #[\Override]
    protected function displayHistory(array $historyEntries): void
    {
        if (empty($historyEntries)) {
            return;
        }
        $languageService = $this->getLanguageService();
        $lines = [];
        $beUserArray = BackendUtility::getUserNames('username,realName,usergroup,uid');

        $feUserArray = [];

        // Traverse changeLog array:
        foreach ($historyEntries as $entry) {
            // Build up single line
            $singleLine = [];

            // Get usernames
            if ($entry['usertype'] === 'FE') {
                if (array_key_exists($entry['userid'], $feUserArray)) {
                    $feUser = $feUserArray[$entry['userid']];
                } else {
                    $feUser = BackendUtility::getRecord('fe_users', $entry['userid'], 'uid,username');
                    if (is_null($feUser)) {
                        $feUser = [
                            'uid' => 0,
                            'username' => 'anonymous',
                        ];
                    }
                    $feUserArray[$feUser['uid']] = $feUser;
                }
                $singleLine['backendUserUid'] = $feUser['uid'];
                $singleLine['backendUserName'] = 'FE-User: ' . ($feUser['uid'] ? $feUser['username'] : '');
            } else {
                $singleLine['backendUserUid'] = $entry['userid'];
                $singleLine['backendUserName'] = $entry['userid'] ? $beUserArray[$entry['userid']]['username'] : '';
            }
            // Executed by switch user
            if (!empty($entry['originaluserid'])) {
                $singleLine['originalBackendUserName'] = $beUserArray[$entry['originaluserid']]['username'];
            }

            // Is a change in a workspace?
            $singleLine['isChangedInWorkspace'] = (int)$entry['workspace'] > 0;

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
                    /** @var string[] $tmpFieldList */
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
                    $singleLine['differences'] = $this->renderDiff($entry, $entry['tablename'], $entry['recuid']);
                }
            }
            // put line together
            $lines[] = $singleLine;
        }
        $this->view->assign('history', $lines);
    }
}
