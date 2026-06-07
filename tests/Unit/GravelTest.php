<?php

namespace Tests\Unit;

use App\Paling\PanelException;
use App\Paling\PanelFormatter;
use App\Paling\PanelPlanner;
use App\Paling\SamplePanel;
use PHPUnit\Framework\TestCase;

class GravelTest extends TestCase
{
    public function test_a_150_mm_board_shortens_the_pickets_and_adds_one_stick(): void
    {
        $plan = PanelPlanner::plan([...SamplePanel::garden(), 'gravelMm' => 150]);
        $this->assertSame(16, $plan['pickets']);
        $this->assertSame(750, $plan['picketLengthMm']);
        $this->assertSame(1420, $plan['boardLengthMm']);
        $this->assertSame(16260, $plan['woodLengthMm']);
        $this->assertStringContainsString('gravel: 150 mm, board 1420 mm', PanelFormatter::format($plan));
    }

    public function test_a_short_board_and_a_board_that_uses_up_the_pickets_are_rejected(): void
    {
        try {
            PanelPlanner::plan([...SamplePanel::garden(), 'gravelMm' => 50]);
            $this->fail('A short gravel board should be rejected.');
        } catch (PanelException $exception) {
            $this->assertSame(['gravelMm: gravelMm must be an integer from 80 to 300'], $exception->errors);
        }

        try {
            PanelPlanner::plan([...SamplePanel::garden(), 'heightMm' => 400, 'gravelMm' => 250]);
            $this->fail('A gravel board that leaves short pickets should be rejected.');
        } catch (PanelException $exception) {
            $this->assertSame(['gravelMm: the gravel board leaves the pickets under 200 mm'], $exception->errors);
        }
    }
}
