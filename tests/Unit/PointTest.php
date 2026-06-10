<?php

namespace Tests\Unit;

use App\Paling\Layout;
use App\Paling\PanelException;
use App\Paling\PanelFormatter;
use App\Paling\PanelPlanner;
use App\Paling\SamplePanel;
use PHPUnit\Framework\TestCase;

class PointTest extends TestCase
{
    public function test_a_40_mm_point_adds_wood_on_every_picket(): void
    {
        $this->assertSame(640, Layout::pointWoodMm(16, 40));
        $plan = PanelPlanner::plan([...SamplePanel::garden(), 'pointMm' => 40]);
        $this->assertSame(16, $plan['pickets']);
        $this->assertSame(900, $plan['picketLengthMm']);
        $this->assertSame(640, $plan['pointWoodMm']);
        $this->assertSame(17880, $plan['woodLengthMm']);
        $this->assertStringContainsString('point: 40 mm, 640 mm extra', PanelFormatter::format($plan));
    }

    public function test_a_point_is_extra_wood_above_a_gravel_board(): void
    {
        $plan = PanelPlanner::plan([...SamplePanel::garden(), 'gravelMm' => 150, 'pointMm' => 40]);
        $this->assertSame(750, $plan['picketLengthMm']);
        $this->assertSame(640, $plan['pointWoodMm']);
        $this->assertSame(16900, $plan['woodLengthMm']);
    }

    public function test_a_point_of_10_mm_is_rejected(): void
    {
        try {
            PanelPlanner::plan([...SamplePanel::garden(), 'pointMm' => 10]);
            $this->fail('A short point should be rejected.');
        } catch (PanelException $exception) {
            $this->assertSame(['pointMm: pointMm must be an integer from 20 to 80'], $exception->errors);
        }
    }
}
