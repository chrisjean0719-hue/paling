<?php

namespace Tests\Unit;

use App\Paling\Layout;
use PHPUnit\Framework\TestCase;

class LayoutTest extends TestCase
{
    public function test_the_sample_width_holds_sixteen_pickets(): void
    {
        $this->assertSame(16, Layout::picketCount(1420, 70, 20));
        $this->assertSame(17240, Layout::woodLengthMm(1420, 900, 16, 2));
    }

    public function test_a_width_that_does_not_divide_has_no_count(): void
    {
        $this->assertNull(Layout::picketCount(1400, 70, 20));
    }
}
