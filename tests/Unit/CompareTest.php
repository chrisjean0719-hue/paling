<?php

namespace Tests\Unit;

use App\Paling\Compare;
use App\Paling\PanelPlanner;
use App\Paling\SamplePanel;
use PHPUnit\Framework\TestCase;

class CompareTest extends TestCase
{
    public function test_the_sample_has_more_pickets_and_more_wood_than_a_short_run(): void
    {
        $left = PanelPlanner::plan(SamplePanel::garden());
        $right = PanelPlanner::plan([
            'name' => 'Short run',
            'widthMm' => 880,
            'heightMm' => 600,
        ]);
        $diff = Compare::panels($left, $right);
        $this->assertSame(['pickets' => [16, 10], 'woodLengthMm' => [17240, 7760]], $diff);
        $this->assertSame(
            "Sample garden paling vs Short run\npickets: 16 vs 10\nwood: 17240 mm vs 7760 mm",
            Compare::format($left, $right, $diff),
        );
    }

    public function test_posts_are_included_in_the_wood_being_compared(): void
    {
        $left = PanelPlanner::plan(SamplePanel::garden());
        $right = PanelPlanner::plan([...SamplePanel::garden(), 'name' => 'Posted paling', 'setMm' => 450]);
        $diff = Compare::panels($left, $right);
        $this->assertSame([16, 16], $diff['pickets']);
        $this->assertSame([17240, 19940], $diff['woodLengthMm']);
    }
}
