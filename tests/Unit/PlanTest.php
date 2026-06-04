<?php

namespace Tests\Unit;

use App\Paling\PanelException;
use App\Paling\PanelFormatter;
use App\Paling\PanelPlanner;
use App\Paling\SamplePanel;
use PHPUnit\Framework\TestCase;

class PlanTest extends TestCase
{
    public function test_the_sample_garden_paling(): void
    {
        $plan = PanelPlanner::plan(SamplePanel::garden());
        $this->assertSame(16, $plan['pickets']);
        $this->assertSame(17240, $plan['woodLengthMm']);
        $text = PanelFormatter::format($plan);
        $this->assertStringStartsWith('Sample garden paling', $text);
        $this->assertStringContainsString('pickets: 16 at 70 mm, gap 20 mm', $text);
        $this->assertStringContainsString('wood: 17240 mm', $text);
    }

    public function test_missing_picket_gap_and_rails_keep_the_defaults(): void
    {
        $plan = PanelPlanner::plan([
            'name' => ' Short run ',
            'widthMm' => 880,
            'heightMm' => 600,
        ]);
        $this->assertSame('Short run', $plan['name']);
        $this->assertSame(70, $plan['picketMm']);
        $this->assertSame(20, $plan['gapMm']);
        $this->assertSame(2, $plan['rails']);
        $this->assertSame(10, $plan['pickets']);
        $this->assertSame(7760, $plan['woodLengthMm']);
    }

    public function test_a_nameless_panel_and_an_uneven_width_are_rejected(): void
    {
        try {
            PanelPlanner::plan([...SamplePanel::garden(), 'name' => '  ']);
            $this->fail('A blank name should be rejected.');
        } catch (PanelException $exception) {
            $this->assertSame(['name: Panel needs a name'], $exception->errors);
        }

        try {
            PanelPlanner::plan([...SamplePanel::garden(), 'widthMm' => 1400]);
            $this->fail('An uneven width should be rejected.');
        } catch (PanelException $exception) {
            $this->assertSame(
                ['widthMm: the pickets do not divide the panel into whole millimetres'],
                $exception->errors,
            );
        }
    }
}
