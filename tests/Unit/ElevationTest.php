<?php

namespace Tests\Unit;

use App\Paling\Elevation;
use App\Paling\PanelPlanner;
use App\Paling\SamplePanel;
use PHPUnit\Framework\TestCase;

class ElevationTest extends TestCase
{
    public function test_the_sample_elevation_starts_the_first_picket_at_the_left(): void
    {
        $svg = Elevation::svg(PanelPlanner::plan(SamplePanel::garden()));
        $this->assertStringStartsWith('<?xml version="1.0" encoding="UTF-8"?>', $svg);
        $this->assertStringContainsString('<title>Sample garden paling</title>', $svg);
        $this->assertStringContainsString('viewBox="0 0 1420 916"', $svg);
        $this->assertStringContainsString('x="0" y="16" width="70" height="900" fill="#c4a574"', $svg);
        $this->assertStringContainsString('x="90" y="16" width="70" height="900" fill="#c4a574"', $svg);
        $this->assertSame(16, substr_count($svg, 'fill="#c4a574"'));
        $this->assertStringContainsString('x="0" y="296" width="1420" height="40" fill="#8d6b4a"', $svg);
        $this->assertStringContainsString('x="0" y="596" width="1420" height="40" fill="#8d6b4a"', $svg);
        $this->assertSame('sample-garden-paling-elevation.svg', Elevation::fileName('Sample garden paling'));
    }

    public function test_a_point_a_board_and_posts_each_take_their_place(): void
    {
        $pointed = Elevation::svg(PanelPlanner::plan([...SamplePanel::garden(), 'pointMm' => 40]));
        $this->assertStringContainsString('viewBox="0 0 1420 956"', $pointed);
        $this->assertStringContainsString('points="0,56 70,56 35,16"', $pointed);
        $this->assertStringContainsString('x="0" y="56" width="70" height="900" fill="#c4a574"', $pointed);

        $boarded = Elevation::svg(PanelPlanner::plan([...SamplePanel::garden(), 'gravelMm' => 150]));
        $this->assertStringContainsString('x="0" y="16" width="70" height="750" fill="#c4a574"', $boarded);
        $this->assertStringContainsString('x="0" y="766" width="1420" height="150" fill="#8a8175"', $boarded);

        $posted = Elevation::svg(PanelPlanner::plan([...SamplePanel::garden(), 'setMm' => 450]));
        $this->assertStringContainsString('viewBox="0 0 1580 1366"', $posted);
        $this->assertStringContainsString('x="0" y="16" width="80" height="1350" fill="#6e5344"', $posted);
        $this->assertStringContainsString('x="1500" y="16" width="80" height="1350" fill="#6e5344"', $posted);
        $this->assertStringContainsString('x="80" y="16" width="70" height="900" fill="#c4a574"', $posted);
    }
}
