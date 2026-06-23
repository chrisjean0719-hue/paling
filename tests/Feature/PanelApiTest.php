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
        $sample->assertJsonPath('pointMm', 0);
        $sample->assertJsonPath('postCount', 0);
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

        $pointed = $this->postJson('/api/panels', [...SamplePanel::garden(), 'pointMm' => 40]);
        $pointed->assertOk();
        $pointed->assertJsonPath('picketLengthMm', 900);
        $pointed->assertJsonPath('pointWoodMm', 640);
        $pointed->assertJsonPath('woodLengthMm', 17880);

        $posted = $this->postJson('/api/panels', [...SamplePanel::garden(), 'setMm' => 450]);
        $posted->assertOk();
        $posted->assertJsonPath('postCount', 2);
        $posted->assertJsonPath('postLengthMm', 1350);
        $posted->assertJsonPath('woodLengthMm', 19940);

        $bad = $this->postJson('/api/panels', [
            'name' => '',
            'widthMm' => 1420,
            'heightMm' => 900,
        ]);
        $bad->assertStatus(400);
        $bad->assertExactJson(['errors' => ['name: Panel needs a name']]);

        $elevation = $this->get('/api/sample/elevation');
        $elevation->assertOk();
        $elevation->assertHeader('content-type', 'image/svg+xml; charset=utf-8');
        $this->assertStringContainsString(
            'sample-garden-paling-elevation.svg',
            (string) $elevation->headers->get('content-disposition'),
        );
        $elevation->assertSee('viewBox="0 0 1420 916"', false);

        $badDrawing = $this->postJson('/api/panels/elevation', [
            'name' => '',
            'widthMm' => 1420,
            'heightMm' => 900,
        ]);
        $badDrawing->assertStatus(400);
        $badDrawing->assertExactJson(['errors' => ['name: Panel needs a name']]);

        $compared = $this->postJson('/api/panels/compare', [
            'left' => SamplePanel::garden(),
            'right' => ['name' => 'Short run', 'widthMm' => 880, 'heightMm' => 600],
        ]);
        $compared->assertOk();
        $compared->assertJsonPath('pickets', [16, 10]);
        $compared->assertJsonPath('woodLengthMm', [17240, 7760]);
        $compared->assertJsonPath('text', "Sample garden paling vs Short run\npickets: 16 vs 10\nwood: 17240 mm vs 7760 mm");

        $rejected = $this->postJson('/api/panels/compare', [
            'left' => ['name' => '', 'widthMm' => 1420, 'heightMm' => 900],
            'right' => SamplePanel::garden(),
        ]);
        $rejected->assertStatus(400);
        $rejected->assertExactJson(['errors' => ['left.name: Panel needs a name']]);
    }
}
