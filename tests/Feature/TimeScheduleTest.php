<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\ProjectWeek;
use App\Models\TimeSchedulePlan;
use App\Models\User;
use App\Models\WeeklyReport;
use App\Models\WorkItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimeScheduleTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Project $project;
    protected WorkItem $workItem;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->project = Project::create(['name' => 'Proyek Uji Coba Time Schedule']);
        
        $this->workItem = WorkItem::create([
            'project_id' => $this->project->id,
            'type' => 'main',
            'name' => 'Pekerjaan Persiapan',
            'volume' => 1,
            'unit' => 'ls',
            'unit_price' => 10000000,
            'total_price' => 10000000,
        ]);
    }

    public function test_can_save_manual_weeks_and_auto_syncs_weekly_reports(): void
    {
        $payload = [
            'weeks' => [
                [
                    'week_number' => 1,
                    'start_date' => '2026-07-23',
                    'end_date' => '2026-07-26',
                    'notes' => 'Periode 4 hari',
                ],
                [
                    'week_number' => 2,
                    'start_date' => '2026-07-27',
                    'end_date' => '2026-08-02',
                    'notes' => 'Periode 7 hari',
                ],
            ],
        ];

        $response = $this->actingAs($this->admin)
            ->postJson(route('projects.weeks.save', $this->project), $payload);

        $response->assertOk()
            ->assertJson(['success' => true]);

        // Assert ProjectWeek records exist
        $this->assertDatabaseHas('project_weeks', [
            'project_id' => $this->project->id,
            'week_number' => 1,
            'start_date' => '2026-07-23 00:00:00',
        ]);
        $this->assertDatabaseHas('project_weeks', [
            'project_id' => $this->project->id,
            'week_number' => 2,
            'start_date' => '2026-07-27 00:00:00',
        ]);

        // Assert WeeklyReport draft was auto-created safely!
        $this->assertDatabaseHas('weekly_reports', [
            'project_id' => $this->project->id,
            'week_number' => 1,
            'start_date' => '2026-07-23 00:00:00',
            'end_date' => '2026-07-26 00:00:00',
        ]);
        $this->assertDatabaseHas('weekly_reports', [
            'project_id' => $this->project->id,
            'week_number' => 2,
            'start_date' => '2026-07-27 00:00:00',
            'end_date' => '2026-08-02 00:00:00',
        ]);
    }

    public function test_can_save_time_schedule_plans(): void
    {
        $week = ProjectWeek::create([
            'project_id' => $this->project->id,
            'week_number' => 1,
            'start_date' => '2026-07-23',
            'end_date' => '2026-07-26',
        ]);

        $payload = [
            'plans' => [
                [
                    'work_item_id' => $this->workItem->id,
                    'project_week_id' => $week->id,
                    'plan_value' => 2.50,
                ],
            ],
        ];

        $response = $this->actingAs($this->admin)
            ->postJson(route('projects.time-schedule.plans.save', $this->project), $payload);

        $response->assertOk()
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('time_schedule_plans', [
            'project_id' => $this->project->id,
            'work_item_id' => $this->workItem->id,
            'project_week_id' => $week->id,
            'plan_value' => 2.50,
        ]);
    }

    public function test_dashboard_renders_time_schedule_tab(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('weekly-reports.project-dashboard', ['project' => $this->project->id, 'tab' => 'time_schedule']));

        $response->assertOk()
            ->assertSee('Time Schedule & Kurva S', false)
            ->assertSee('Atur Periode Minggu');
    }

    public function test_can_export_time_schedule_pdf(): void
    {
        ProjectWeek::create([
            'project_id' => $this->project->id,
            'week_number' => 1,
            'start_date' => '2026-07-23',
            'end_date' => '2026-07-26',
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('projects.time-schedule.pdf', $this->project));

        $response->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }
}
