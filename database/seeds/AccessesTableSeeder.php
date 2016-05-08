<?php

use Illuminate\Database\Seeder;

use Trackit\Models\Role;
use Trackit\Models\Access;
use Trackit\Models\Proposal;

class AccessesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $student = Role::byName('student')->first();
        $studentProposalStatuses = [
            Proposal::APPROVED,
        ];

        $teacher = Role::byName('teacher')->first();
        $teacherProposalStatuses = Proposal::STATUSES;

        foreach ($studentProposalStatuses as $status) {
            Access::create([
                'role_id' => $student->id,
                'resource' => 'proposal',
                'status' => $status,
            ]);
        }

        foreach ($teacherProposalStatuses as $status) {
            Access::create([
                'role_id' => $teacher->id,
                'resource' => 'proposal',
                'status' => $status,
            ]);
        }
    }
}
