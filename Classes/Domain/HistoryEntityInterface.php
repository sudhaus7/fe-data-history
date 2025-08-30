<?php

declare(strict_types=1);

namespace SUDHAUS7\FeDataHistory\Domain;

/**
 * Interface HistoryEntityInterface
 *
 * For using the sys_history, add this interface to any Extbase model. The
 * EventListeners will take care of this interface to decide, whether a
 * history entry has to be written or not.
 */
interface HistoryEntityInterface {}
