<?php

namespace Tests\Feature;

use App\Paling\SamplePanel;
use Tests\TestCase;

class PanelApiTest extends TestCase
{
    public function test_health_and_the_sample_panel(): void
    {
        $this->getJson('/api/health')->assertOk()->assertExactJson(['status' => 'ok']);

        $sample = $this->getJson('/api/sample');
        $sample->assertOk();
        $sample->assertJsonPath('name', 'Sample garden paling');
        $sample->assertJsonPath('pickets', 16);
        $sample->assertJsonPath('gravelMm', 0);
        $sample->assertJsonPath('woodLengthMm', 17240);
    }

    public function test_a_posted_panel_matches_the_sample_and_a_bad_panel_is_a_400(): void
    {
        $ok = $this->postJson('/api/panels', SamplePanel::garden());
        $ok->assertOk();
        $ok->assertJsonPath('pickets', 16);
        $ok->assertJsonPath('woodLengthMm', 17240);

        $boarded = $this->postJson('/api/panels', [...SamplePanel::garden(), 'gravelMm' => 150]);
        $boarded->assertOk();
        $boarded->assertJsonPath('picketLengthMm', 750);
        $boarded->assertJsonPath('boardLengthMm', 1420);
        $boarded->assertJsonPath('woodLengthMm', 16260);

        $bad = $this->postJson('/api/panels', [
            'name' => '',
            'widthMm' => 1420,
            'heightMm' => 900,
        ]);
        $bad->assertStatus(400);
        $bad->assertExactJson(['errors' => ['name: Panel needs a name']]);
    }
}
