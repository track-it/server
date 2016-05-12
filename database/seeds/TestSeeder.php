<?php

use Illuminate\Database\Seeder;

use Trackit\Models\User;
use Trackit\Models\Proposal;
use Trackit\Models\Project;
use Trackit\Models\Attachment;
use Trackit\Models\Team;
use Trackit\Models\Role;
use Trackit\Models\Comment;

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
        $customer = factory(User::class)->create([
            'username' => 'customer',
            'password' => 'password',
            'role_id' => Role::byName('customer')->first()->id,
        ]);
        $student1 = factory(User::class)->create([
            'username' => 'student',
            'password' => 'password',
            'role_id' => Role::byName('student')->first()->id,
        ]);
        $student2 = factory(User::class)->create([
            'role_id' => Role::byName('student')->first()->id,
        ]);
        $student3 = factory(User::class)->create([
            'role_id' => Role::byName('student')->first()->id,
        ]);

        $attachment1 = factory(Attachment::class)->create();
        $attachment2 = factory(Attachment::class)->create();
        $attachment3 = factory(Attachment::class)->create();
        $attachment4 = factory(Attachment::class)->create();
        $attachment5 = factory(Attachment::class)->create();
        $attachment6 = factory(Attachment::class)->create();

        $proposal1 = factory(Proposal::class)->create([
            'author_id' => $customer->id,
            'status' => Proposal::NOT_REVIEWED,
        ]);
        $proposal2 = factory(Proposal::class)->create([
            'status' => Proposal::UNDER_REVIEW,
        ]);
        $proposal3 = factory(Proposal::class)->create([
            'status' => Proposal::NOT_APPROVED,
        ]);
        $proposal4 = factory(Proposal::class)->create([
            'status' => Proposal::APPROVED,
        ]);
        $proposal5 = factory(Proposal::class)->create([
            'status' => Proposal::ARCHIVED,
        ]);

        $project1 = factory(Project::class)->create();
        $project2 = factory(Project::class)->create();
        $project3 = factory(Project::class)->create();

        $proposal1->attachments()->save($attachment1);
        $proposal1->attachments()->save($attachment2);
        $proposal1->attachments()->save($attachment3);

        $comment1 = factory(Comment::class)->create();
        $comment2 = factory(Comment::class)->create();
        $comment3 = factory(Comment::class)->create();
        $comment4 = factory(Comment::class)->create();

        $comment1->author()->associate($student1)->save();
        $comment2->author()->associate($teacher)->save();
        $comment3->author()->associate($customer)->save();
        $comment4->author()->associate($admin)->save();

        $team = factory(Team::class)->create();
        $student1->joinTeam($team);
        $student2->joinTeam($team);
        $student3->joinTeam($team);

        $proposal1->comments()->save($comment1);
        $proposal1->comments()->save($comment2);
        $proposal1->comments()->save($comment3);

        $proposal2->author()->associate($teacher)->save();
        $proposal3->author()->associate($teacher)->save();

        $project1->proposal()->associate($proposal1)->save();
        $project1->team()->associate($team)->save();
        $project1->addParticipant('student', $student1);
        $project1->addParticipant('student', $student2);
        $project1->addParticipant('student', $student3);
        $project1->addParticipant('teacher', $teacher);
        $project2->addParticipant('teacher', $teacher);
        $project1->addParticipant('stakeholder', $customer);

        $project1->attachments()->save($attachment4);
        $project1->attachments()->save($attachment5);
        $project1->attachments()->save($attachment6);
    }
}
