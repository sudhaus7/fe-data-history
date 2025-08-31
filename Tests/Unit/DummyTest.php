<?php

declare(strict_types=1);

namespace SUDHAUS7\FeDataHistory\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * A dummy test for testing the pipeline runs only.
 * Can be removed after correct UNitTests are added.
 */
final class DummyTest extends UnitTestCase
{
    #[Test]
    public function dummy(): void
    {
        $this->assertTrue(true);
    }
}
