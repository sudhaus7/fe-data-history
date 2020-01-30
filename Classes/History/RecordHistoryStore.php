<?php
declare(strict_types=1);
/**
 * Created by: markus
 * Created at: 30.01.20 15:09
 */

namespace SUDHAUS7\FeDataHistory\History;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class RecordHistoryStore
 * @package SUDHAUS7\FeDataHistory\History
 */
class RecordHistoryStore
{
    public const ACTION_ADD = 1;
    public const ACTION_MODIFY = 2;
    public const ACTION_MOVE = 3;
    public const ACTION_DELETE = 4;
    public const ACTION_UNDELETE = 5;

    public const USER_BACKEND = 'BE';
    public const USER_FRONTEND = 'FE';
    public const USER_ANONYMOUS = '';

    /**
     * @var int|null
     */
    protected $userId;

    /**
     * @var string
     */
    protected $userType;

    /**
     * @var int|null
     */
    protected $originalUserId;

    /**
     * @var int|null
     */
    protected $tstamp;

    /**
     * @var int
     */
    protected $workspaceId;

    /**
     * @param string $userType
     * @param int|null $userId
     * @param int $originalUserId
     * @param int $tstamp
     * @param int $workspaceId
     */
    public function __construct(string $userType = self::USER_FRONTEND, int $userId = null, int $originalUserId = null, int $tstamp = null, int $workspaceId = 0)
    {
        $this->userType = $userType;
        $this->userId = $userId;
        $this->originalUserId = $GLOBALS['BE_USER']->user['uid'] ?? $originalUserId ?? 0;
        $this->tstamp = $tstamp ?: $GLOBALS['EXEC_TIME'];
        $this->workspaceId = $workspaceId;
    }

    /**
     * @param string $table
     * @param int $uid
     * @param array $payload
     * @return string
     */
    public function addRecord(string $table, int $uid, array $payload): string
    {
        $data = [
            'actiontype' => self::ACTION_ADD,
            'usertype' => $this->userType,
            'userid' => $this->userId,
            'originaluserid' => $this->originalUserId,
            'tablename' => $table,
            'recuid' => $uid,
            'tstamp' => $this->tstamp,
            'history_data' => json_encode($payload),
            'workspace' => $this->workspaceId,
        ];
        $this->getDatabaseConnection()->insert('sys_history', $data);
        return $this->getDatabaseConnection()->lastInsertId('sys_history');
    }

    /**
     * @param string $table
     * @param int $uid
     * @param array $payload
     * @return string
     */
    public function modifyRecord(string $table, int $uid, array $payload): string
    {
        $data = [
            'actiontype' => self::ACTION_MODIFY,
            'usertype' => $this->userType,
            'userid' => $this->userId,
            'originaluserid' => $this->originalUserId,
            'tablename' => $table,
            'recuid' => $uid,
            'tstamp' => $this->tstamp,
            'history_data' => json_encode($payload),
            'workspace' => $this->workspaceId,
        ];
        $this->getDatabaseConnection()->insert('sys_history', $data);
        return $this->getDatabaseConnection()->lastInsertId('sys_history');
    }

    /**
     * @param string $table
     * @param int $uid
     * @return string
     */
    public function deleteRecord(string $table, int $uid): string
    {
        $data = [
            'actiontype' => self::ACTION_DELETE,
            'usertype' => $this->userType,
            'userid' => $this->userId,
            'originaluserid' => $this->originalUserId,
            'tablename' => $table,
            'recuid' => $uid,
            'tstamp' => $this->tstamp,
            'workspace' => $this->workspaceId,
        ];
        $this->getDatabaseConnection()->insert('sys_history', $data);
        return $this->getDatabaseConnection()->lastInsertId('sys_history');
    }

    /**
     * @param $oldRecord
     * @param $newRecord
     * @return array
     */
    public function createPayload($oldRecord, $newRecord)
    {
        return [];
    }

    /**
     * @return Connection
     */
    protected function getDatabaseConnection(): Connection
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('sys_history');
    }
}
