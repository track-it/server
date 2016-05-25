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
        $customer2 = factory(User::class)->create([
            'role_id' => Role::byName('customer')->first()->id,
        ]);
        $teacher2 = factory(User::class)->create([
            'role_id' => Role::byName('teacher')->first()->id,
        ]);

        // Proposals
        $proposal1 = factory(Proposal::class)->create([
            'title' => 'Supercalifragilisticexpialidocious title!',
            'category' => Proposal::PROJECT,
            'author_id' => $student->id,
            'status' => Proposal::APPROVED,
        ]);
        $proposal2 = factory(Proposal::class)->create([
            'title' => 'Awesome project',
            'category' => Proposal::PROJECT,
            'author_id' => $customer->id,
            'status' => Proposal::UNDER_REVIEW,
        ]);
        $proposal3 = factory(Proposal::class)->create([
            'title' => 'Idea for bachelor\'s thesis',
            'author_id' => $student->id,
            'category' => Proposal::BACHELOR,
            'status' => Proposal::NOT_APPROVED,
        ]);
        $proposal4 = factory(Proposal::class)->create([
            'title' => 'MAH Project #2',
            'category' => Proposal::PROJECT,
            'author_id' => $teacher->id,
            'status' => Proposal::APPROVED,
        ]);
        $proposal5 = factory(Proposal::class)->create([
            'title' => 'Prototype proposal',
            'category' => Proposal::PROJECT,
            'author_id' => $customer2->id,
            'status' => Proposal::ARCHIVED,
        ]);
        $proposal6 = factory(Proposal::class)->create([
            'title' => 'Security in Cisco routers',
            'category' => Proposal::MASTER,
            'author_id' => $student2->id,
            'status' => Proposal::NOT_REVIEWED,
        ]);
        $proposal7 = factory(Proposal::class)->create([
            'title' => 'Agile development in practice',
            'category' => Proposal::MASTER,
            'author_id' => $student3->id,
            'status' => Proposal::APPROVED,
        ]);

        // Projects
        $project1 = factory(Project::class)->create([
            'title' => 'Agile development in practice',
            'proposal_id' => $proposal7->id,
            'status' => Project::NOT_COMPLETED,
        ]);
        $project2 = factory(Project::class)->create([
            'title' => 'Prototype - Hybrid web app',
            'proposal_id' => $proposal5->id,
            'status' => Project::COMPLETED,
        ]);
        $project3 = factory(Project::class)->create([
            'title' => 'MAH Project #2',
            'proposal_id' => $proposal4->id,
            'status' => Project::PUBLISHED,
        ]);

        $project1->addParticipant('student', $student3);
        $project1->addParticipant('teacher', $teacher);

        $project2->addParticipant('student', $student2);
        $project2->addParticipant('student', $student3);
        $project2->addParticipant('teacher', $teacher2);
        $project2->addParticipant('stakeholder', $customer2);

        $project3->addParticipant('stakeholder', $teacher);
        $project3->addParticipant('teacher', $teacher2);
        $project3->addParticipant('student', $student4);
        $project3->addParticipant('student', $student3);

        // Comments
        $project1->comments()->save(factory(Comment::class)->create([
            'author_id' => $student3
        ]));

        $project1->comments()->save(factory(Comment::class)->create([
            'author_id' => $teacher
        ]));

        $project2->comments()->save(factory(Comment::class)->create([
            'author_id' => $student2
        ]));

        $project2->comments()->save(factory(Comment::class)->create([
            'author_id' => $teacher2
        ]));

        $project2->comments()->save(factory(Comment::class)->create([
            'author_id' => $customer2
        ]));

        $project3->comments()->save(factory(Comment::class)->create([
            'author_id' => $teacher
        ]));

        $project3->comments()->save(factory(Comment::class)->create([
            'author_id' => $student3
        ]));

        $project3->comments()->save(factory(Comment::class)->create([
            'author_id' => $student4
        ]));

        $proposal1->comments()->save(factory(Comment::class)->create([
            'author_id' => $teacher
        ]));

        $proposal1->comments()->save(factory(Comment::class)->create([
            'author_id' => $student
        ]));

        $proposal2->comments()->save(factory(Comment::class)->create([
            'author_id' => $teacher2
        ]));

        $proposal2->comments()->save(factory(Comment::class)->create([
            'author_id' => $customer
        ]));

        // Attachments
        $proposal1->attachments()->save(factory(Attachment::class)->create([
            'path'  => 'attachments/proposal_1/Proposal.pdf',
            'title' => 'Proposal.pdf',
            'mime_type'  => 'application/pdf',
        ]));

        $project1->attachments()->save(factory(Attachment::class)->create([
            'path'  => 'attachments/project_1/Kravspec.pdf',
            'title' => 'Kravspec.pdf',
            'mime_type'  => 'application/pdf',
        ]));

    }
}
