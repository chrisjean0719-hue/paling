<?php

namespace Tests\Unit;

use App\Paling\Layout;
use App\Paling\PanelException;
use App\Paling\PanelFormatter;
use App\Paling\PanelPlanner;
use App\Paling\SamplePanel;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    public function test_a_450_mm_set_places_two_posts_below_the_panel(): void
    {
        $this->assertSame(1350, Layout::postLengthMm(900, 450));
        $this->assertSame(2700, Layout::postWoodMm(1350));
        $plan = PanelPlanner::plan([...SamplePanel::garden(), 'setMm' => 450]);
        $this->assertSame(2, $plan['postCount']);
        $this->assertSame(1350, $plan['postLengthMm']);
        $this->assertSame(16, $plan['pickets']);
        $this->assertSame(19940, $plan['woodLengthMm']);
        $this->assertStringContainsString('posts: 2 at 1350 mm, set 450 mm', PanelFormatter::format($plan));
    }

    public function test_points_do_not_lengthen_the_posts(): void
    {
        $plan = PanelPlanner::plan([...SamplePanel::garden(), 'setMm' => 450, 'pointMm' => 40]);
        $this->assertSame(1350, $plan['postLengthMm']);
        $this->assertSame(20580, $plan['woodLengthMm']);
    }

    public function test_a_set_of_200_mm_is_rejected(): void
    {
        try {
            PanelPlanner::plan([...SamplePanel::garden(), 'setMm' => 200]);
            $this->fail('A short set should be rejected.');
        } catch (PanelException $exception) {
            $this->assertSame(['setMm: setMm must be an integer from 300 to 900'], $exception->errors);
        }
    }
}
