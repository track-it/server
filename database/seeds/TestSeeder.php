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
        // Logins
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
        $student = factory(User::class)->create([
            'username' => 'student',
            'password' => 'password',
            'role_id' => Role::byName('student')->first()->id,
        ]);

        // Generated roles
        $student2 = factory(User::class)->create([
            'role_id' => Role::byName('student')->first()->id,
        ]);
        $student3 = factory(User::class)->create([
            'role_id' => Role::byName('student')->first()->id,
        ]);
        $student4 = factory(User::class)->create([
            'role_id' => Role::byName('student')->first()->id,
        ]);

        // Proposals
        $proposal1 = factory(Proposal::class)->create([
            'author_id' => $customer->id,
            'status' => Proposal::NOT_REVIEWED,
        ]);
        $proposal2 = factory(Proposal::class)->create([
            'author_id' => $customer->id,
            'status' => Proposal::UNDER_REVIEW,
        ]);
        $proposal3 = factory(Proposal::class)->create([
            'author_id' => $student->id,
            'status' => Proposal::NOT_APPROVED,
        ]);
        $proposal4 = factory(Proposal::class)->create([
            'author_id' => $student->id,
            'status' => Proposal::APPROVED,
        ]);
        $proposal5 = factory(Proposal::class)->create([
            'author_id' => $student->id,
            'status' => Proposal::ARCHIVED,
        ]);
        $proposal6 = factory(Proposal::class)->create([
            'author_id' => $student2->id,
            'status' => Proposal::APPROVED,
        ]);
        $proposal7 = factory(Proposal::class)->create([
            'author_id' => $student3->id,
            'status' => Proposal::APPROVED,
        ]);

        // Projects
        $project1 = factory(Project::class)->create([
            'status' => Project::NOT_COMPLETED,
        ]);
        $project2 = factory(Project::class)->create([
            'status' => Project::COMPLETED,
        ]);
        $project3 = factory(Project::class)->create([
            'status' => Project::PUBLISHED,
        ]);

        $project1->proposal()->associate($proposal1)->save();

        $project1->addProjectUser('student', $student);
        $project1->addProjectUser('student', $student2);
        $project1->addProjectUser('student', $student3);
        $project1->addProjectUser('teacher', $teacher);
        $project2->addProjectUser('teacher', $teacher);
        $project1->addProjectUser('stakeholder', $customer);

        // Comments
        $comment1 = factory(Comment::class)->create();
        $comment2 = factory(Comment::class)->create();
        $comment3 = factory(Comment::class)->create();
        $comment4 = factory(Comment::class)->create();

        $comment1->author()->associate($student)->save();
        $comment2->author()->associate($teacher)->save();
        $comment3->author()->associate($customer)->save();
        $comment4->author()->associate($admin)->save();

        $proposal1->comments()->save($comment1);
        $proposal1->comments()->save($comment2);
        $proposal1->comments()->save($comment3);
        $proposal1->comments()->save($comment4);

        $proposal2->comments()->save($comment1);
        $proposal2->comments()->save($comment2);
        $proposal2->comments()->save($comment3);
        $proposal2->comments()->save($comment4);

        // Attachments
        $attachment1 = factory(Attachment::class)->create();
        $attachment2 = factory(Attachment::class)->create();
        $attachment3 = factory(Attachment::class)->create();
        $attachment4 = factory(Attachment::class)->create();
        $attachment5 = factory(Attachment::class)->create();
        $attachment6 = factory(Attachment::class)->create();

        $proposal1->attachments()->save($attachment1);
        $proposal1->attachments()->save($attachment2);
        $proposal1->attachments()->save($attachment3);

        $project1->attachments()->save($attachment4);
        $project1->attachments()->save($attachment5);
        $project1->attachments()->save($attachment6);
    }
}
