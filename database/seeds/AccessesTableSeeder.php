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
            'global:proposal:list' => [
                Proposal::APPROVED,
            ],
            'global:proposal:view' => [
                Proposal::APPROVED,
            ],
            'global:project:list' => [
                Project::PUBLISHED,
            ],
            'global:project:view' => [
                Project::PUBLISHED,
            ],
        ];
        $customerStatuses = [
            'global:proposal:list' => [
                Proposal::APPROVED,
            ],
            'global:proposal:view' => [
                Proposal::APPROVED,
            ],
            'global:project:list' => [
                Project::PUBLISHED,
            ],
            'global:project:view' => [
                Project::PUBLISHED,
            ],
        ];
        $studentStatuses = [
            'global:proposal:list' => [
                Proposal::APPROVED,
            ],
            'global:proposal:view' => [
                Proposal::APPROVED,
            ],
            'global:project:list' => [
                Project::PUBLISHED,
            ],
            'global:project:view' => [
                Project::PUBLISHED,
            ],
            'project:publish' => [
                Project::COMPLETED,
            ],
        ];
        $teacherStatuses = [
            'global:proposal:view' => Proposal::STATUSES,
            'global:proposal:list' => Proposal::STATUSES,
            'global:project:view' => Project::STATUSES,
            'global:project:list' => Project::STATUSES,

        ];
        $administratorStatuses = [
            'global:proposal:list' => Proposal::STATUSES,
            'global:proposal:view' => Proposal::STATUSES,
            'global:project:view' => Project::STATUSES,
            'global:project:list' => Project::STATUSES,
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
