<?php

use Illuminate\Database\Seeder;

use Trackit\Models\User;
use Trackit\Models\Proposal;
use Trackit\Models\Project;
use Trackit\Models\Attachment;
use Trackit\Models\Team;
use Trackit\Models\Role;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = factory(User::class)->create([
            'username' => 'admin',
            'password' => 'password',
            'role_id' => Role::byName('administrator')->first()->id,
        ]);
        $teacher = factory(User::class)->create([
            'username' => 'teacher',
            'password' => 'password',
            'role_id' => Role::byName('teacher')->first()->id,
        ]);
        $student1 = factory(User::class)->create([
            'role_id' => Role::byName('student')->first()->id,
        ]);
        $student2 = factory(User::class)->create([
            'role_id' => Role::byName('student')->first()->id,
        ]);
        $student3 = factory(User::class)->create([
            'role_id' => Role::byName('student')->first()->id,
        ]);
        $customer = factory(User::class)->create([
            'role_id' => Role::byName('customer')->first()->id,
        ]);

        $attachment1 = factory(Attachment::class)->create();
        $attachment2 = factory(Attachment::class)->create();
        $attachment3 = factory(Attachment::class)->create();

        $proposal1 = factory(Proposal::class)->create([
            'author_id' => $customer->id,
        ]);
        $proposal2 = factory(Proposal::class)->create();
        $proposal3 = factory(Proposal::class)->create();

        $project1 = factory(Project::class)->create();
        $project2 = factory(Project::class)->create();
        $project3 = factory(Project::class)->create();

        $proposal1->attachments()->save($attachment1);
        $proposal1->attachments()->save($attachment2);
        $proposal1->attachments()->save($attachment3);

        $team = factory(Team::class)->create();
        $student1->joinTeam($team);
        $student2->joinTeam($team);
        $student3->joinTeam($team);

        $proposal1->

        $project1->proposal()->associate($proposal1);
        $project1->team()->associate($team);
        $project1->addProjectUser('teacher', $teacher);
        $project1->addProjectUser('steakholder', $customer);
    }
}
