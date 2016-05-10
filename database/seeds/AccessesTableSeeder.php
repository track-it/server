<?php

use Illuminate\Database\Seeder;

use Trackit\Models\Role;
use Trackit\Models\Access;
use Trackit\Models\Proposal;
use Trackit\Models\Project;

class AccessesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $guest = Role::byName('guest')->first();
        $customer = Role::byName('customer')->first();
        $student = Role::byName('student')->first();
        $teacher = Role::byName('teacher')->first();
        $administrator = Role::byName('administrator')->first();

        $guestStatuses = [
            'proposal:list' => [
                Proposal::APPROVED,
            ],
            'proposal:view' => [
                Proposal::APPROVED,
            ],
            'project:list' => [
                Project::COMPLETED,
            ],
            'project:view' => [
                Project::COMPLETED,
            ],
        ];
        $customerStatuses = [
            'proposal:list' => [
                Proposal::APPROVED,
            ],
            'proposal:view' => [
                Proposal::APPROVED,
            ],
            'project:list' => [
                Project::COMPLETED,
            ],
            'project:view' => [
                Project::COMPLETED,
            ],
        ];
        $studentStatuses = [
            'proposal:list' => [
                Proposal::APPROVED,
            ],
            'proposal:view' => [
                Proposal::APPROVED,
            ],
            'project:list' => [
                Project::COMPLETED,
            ],
            'project:view' => [
                Project::COMPLETED,
            ],
        ];
        $teacherStatuses = [
            'proposal:view' => Proposal::STATUSES,
            'proposal:list' => Proposal::STATUSES,
            'project:view' => Project::STATUSES,
            'project:list' => Project::STATUSES,

        ];
        $administratorStatuses = [
            'proposal:list' => Proposal::STATUSES,
            'proposal:view' => Project::STATUSES,
            'project:view' => Project::STATUSES,
            'project:list' => Project::STATUSES,
        ];

        foreach ($guestStatuses as $permission => $statuses) {
            foreach ($statuses as $status) {
                Access::create([
                    'role_id' => $guest->id,
                    'permission' => $permission,
                    'status' => $status,
                ]);
            }
        }

        foreach ($customerStatuses as $permission => $statuses) {
            foreach ($statuses as $status) {
                Access::create([
                    'role_id' => $customer->id,
                    'permission' => $permission,
                    'status' => $status,
                ]);
            }
        }

        foreach ($studentStatuses as $permission => $statuses) {
            foreach ($statuses as $status) {
                Access::create([
                    'role_id' => $student->id,
                    'permission' => $permission,
                    'status' => $status,
                ]);
            }
        }

        foreach ($teacherStatuses as $permission => $statuses) {
            foreach ($statuses as $status) {
                Access::create([
                    'role_id' => $teacher->id,
                    'permission' => $permission,
                    'status' => $status,
                ]);
            }
        }

        foreach ($administratorStatuses as $permission => $statuses) {
            foreach ($statuses as $status) {
                Access::create([
                    'role_id' => $administrator->id,
                    'permission' => $permission,
                    'status' => $status,
                ]);
            }
        }
    }
}
