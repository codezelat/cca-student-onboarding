<?php

namespace Database\Seeders;

use App\Models\CCARegistration;
use App\Models\Program;
use App\Models\ProgramIntakeWindow;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seedPrograms = [
            ['code' => 'CCA-FS25', 'name' => 'Full Stack Developer Career Accelerator', 'year_label' => '2025', 'duration_label' => '6 Months', 'is_active' => true],
            ['code' => 'CCA-FE25', 'name' => 'Frontend Developer Career Accelerator', 'year_label' => '2025', 'duration_label' => '6 Months', 'is_active' => false],
            ['code' => 'CCA-BE25', 'name' => 'Backend Developer Career Accelerator', 'year_label' => '2025', 'duration_label' => '6 Months', 'is_active' => false],
            ['code' => 'CCA-MA25', 'name' => 'Mobile App Developer Career Accelerator', 'year_label' => '2025', 'duration_label' => '6 Months', 'is_active' => false],
            ['code' => 'CCA-SE25', 'name' => 'Software Engineer Career Accelerator', 'year_label' => '2025', 'duration_label' => '6 Months', 'is_active' => true],
            ['code' => 'CCA-DA25', 'name' => 'Data Analyst Career Accelerator', 'year_label' => '2025', 'duration_label' => '6 Months', 'is_active' => true],
            ['code' => 'CCA-DS25', 'name' => 'Data Scientist Career Accelerator', 'year_label' => '2025', 'duration_label' => '6 Months', 'is_active' => false],
            ['code' => 'CCA-DE25', 'name' => 'Data Engineer Career Accelerator', 'year_label' => '2025', 'duration_label' => '6 Months', 'is_active' => true],
            ['code' => 'CCA-AI25', 'name' => 'AI ML Engineer Career Accelerator', 'year_label' => '2025', 'duration_label' => '6 Months', 'is_active' => true],
            ['code' => 'CCA-UX25', 'name' => 'UI UX Designer Career Accelerator', 'year_label' => '2025', 'duration_label' => '6 Months', 'is_active' => true],
            ['code' => 'CCA-GD25', 'name' => 'Graphic Designer Career Accelerator', 'year_label' => '2025', 'duration_label' => '6 Months', 'is_active' => true],
            ['code' => 'CCA-DM25', 'name' => 'Digital Marketing Specialist Career Accelerator', 'year_label' => '2025', 'duration_label' => '6 Months', 'is_active' => true],
            ['code' => 'CCA-SEO25', 'name' => 'SEO AEO Specialist Career Accelerator', 'year_label' => '2025', 'duration_label' => '6 Months', 'is_active' => false],
            ['code' => 'CCA-DO25', 'name' => 'DevOps Engineer Career Accelerator', 'year_label' => '2025', 'duration_label' => '6 Months', 'is_active' => true],
            ['code' => 'CCA-QA25', 'name' => 'QA Engineer Manual Automation Career Accelerator', 'year_label' => '2025', 'duration_label' => '6 Months', 'is_active' => true],
            ['code' => 'CCA-PM25', 'name' => 'Project Manager Career Accelerator', 'year_label' => '2025', 'duration_label' => '6 Months', 'is_active' => true],
            ['code' => 'CCA-BA25', 'name' => 'Business Analyst Career Accelerator', 'year_label' => '2025', 'duration_label' => '6 Months', 'is_active' => true],
            ['code' => 'CCA-CS25', 'name' => 'Cyber Security Engineer Career Accelerator', 'year_label' => '2025', 'duration_label' => '6 Months', 'is_active' => true],
            ['code' => 'CCB-FS26', 'name' => 'Full Stack Developer Career Bootcamp', 'year_label' => '2026', 'duration_label' => '6 Months', 'is_active' => true],
            ['code' => 'CCB-SE26', 'name' => 'Software Engineer Career Bootcamp', 'year_label' => '2026', 'duration_label' => '6 Months', 'is_active' => true],
            ['code' => 'CCB-DA26', 'name' => 'Data Analyst Career Bootcamp', 'year_label' => '2026', 'duration_label' => '6 Months', 'is_active' => true],
            ['code' => 'CCB-DE26', 'name' => 'Data Engineer Career Bootcamp', 'year_label' => '2026', 'duration_label' => '6 Months', 'is_active' => true],
            ['code' => 'CCB-AI26', 'name' => 'AI ML Engineer Career Bootcamp', 'year_label' => '2026', 'duration_label' => '6 Months', 'is_active' => true],
            ['code' => 'CCB-UX26', 'name' => 'UI UX Designer Career Bootcamp', 'year_label' => '2026', 'duration_label' => '6 Months', 'is_active' => true],
            ['code' => 'CCB-GD26', 'name' => 'Graphic Designer Career Bootcamp', 'year_label' => '2026', 'duration_label' => '6 Months', 'is_active' => true],
            ['code' => 'CCB-DM26', 'name' => 'Digital Marketing Specialist Career Bootcamp', 'year_label' => '2026', 'duration_label' => '6 Months', 'is_active' => true],
            ['code' => 'CCA-SEO26', 'name' => 'SEO AEO Specialist Career Bootcamp', 'year_label' => '2026', 'duration_label' => '6 Months', 'is_active' => true],
            ['code' => 'CCB-DO26', 'name' => 'DevOps Engineer Career Bootcamp', 'year_label' => '2026', 'duration_label' => '6 Months', 'is_active' => true],
            ['code' => 'CCB-QA26', 'name' => 'QA Engineer Manual Automation Career Bootcamp', 'year_label' => '2026', 'duration_label' => '6 Months', 'is_active' => true],
            ['code' => 'CCB-PM26', 'name' => 'Project Manager Career Bootcamp', 'year_label' => '2026', 'duration_label' => '6 Months', 'is_active' => true],
            ['code' => 'CCB-BA26', 'name' => 'Business Analyst Career Bootcamp', 'year_label' => '2026', 'duration_label' => '6 Months', 'is_active' => true],
            ['code' => 'CCB-CS26', 'name' => 'Cyber Security Engineer Career Bootcamp', 'year_label' => '2026', 'duration_label' => '6 Months', 'is_active' => true],
        ];

        foreach ($seedPrograms as $index => $program) {
            $programModel = Program::updateOrCreate(
                ['code' => $program['code']],
                [
                    'name' => $program['name'],
                    'year_label' => $program['year_label'],
                    'duration_label' => $program['duration_label'],
                    'is_active' => $program['is_active'],
                    'display_order' => $index + 1,
                    'currency' => 'LKR',
                ]
            );

            if ($programModel->is_active) {
                ProgramIntakeWindow::firstOrCreate(
                    [
                        'program_id' => $programModel->id,
                        'window_name' => 'Default Active Intake',
                    ],
                    [
                        'opens_at' => now()->subYears(5),
                        'closes_at' => now()->addYears(5),
                        'price_override' => null,
                        'is_active' => true,
                    ]
                );
            }
        }

        // Ensure legacy program IDs already used in registrations still exist.
        $existingCodes = CCARegistration::withTrashed()
            ->select('program_id', 'program_name', 'program_year', 'program_duration')
            ->whereNotNull('program_id')
            ->distinct()
            ->get();

        foreach ($existingCodes as $row) {
            Program::firstOrCreate(
                ['code' => (string) $row->program_id],
                [
                    'name' => (string) ($row->program_name ?: $row->program_id),
                    'year_label' => (string) ($row->program_year ?: 'N/A'),
                    'duration_label' => (string) ($row->program_duration ?: 'N/A'),
                    'is_active' => false,
                    'display_order' => 9999,
                    'currency' => 'LKR',
                ]
            );
        }
    }
}
