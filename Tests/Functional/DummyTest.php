<?php

declare(strict_types=1);

namespace SUDHAUS7\FeDataHistory\Tests\Functional;

use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * A test for the functional run only. Can be removed after correct functional tests are sdded
 */
final class DummyTest extends FunctionalTestCase
{
    #[Test]
    public function dummy(): void
    {
        $this->assertTrue(true);
    }
}
