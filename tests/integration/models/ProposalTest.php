<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Trackit\Models\Proposal;
use Trackit\Models\Attachment;
use Trackit\Models\Course;
use Trackit\Models\User;
use Trackit\Models\Role;

class ProposalTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_has_a_status()
    {
        $proposal = factory(Proposal::class)->create();

        $hasStatus = in_array($proposal->status, Proposal::STATUSES);

        $this->assertTrue($hasStatus);
    }

    /** @test */
    public function it_has_a_category()
    {
        $proposal = factory(Proposal::class)->create();

        $hasCategory = in_array($proposal->category, Proposal::CATEGORIES);

        $this->assertTrue($hasCategory);
    }

    /** @test */
    public function it_has_an_author()
    {
        $proposal = factory(Proposal::class)->create();

        $hasAuthor = !! $proposal->author();

        $this->assertTrue($hasAuthor);
    }

    /** @test */
    public function it_can_have_an_attachment()
    {
        $attachment = factory(Attachment::class)->create();

        $proposal = factory(Proposal::class)->create();

        $proposal->attachments()->save($attachment);

        $this->assertEquals(1, $proposal->attachments->count());
    }

    /** @test */
    public function it_belongs_to_a_course()
    {
        $proposal = factory(Proposal::class)->create();
        $course = factory(Course::class)->create();

        $proposal->course()->associate($course);
        $hasCourse = !! $proposal->course();

        $this->assertTrue($hasCourse);
    }

    /** @test */
    public function it_should_allow_edits_from_author()
    {
        $user = factory(User::class)->create();
        $proposal = factory(Proposal::class)->create(['author_id' => $user->id]);

        $this->assertTrue($proposal->allowsActionfrom('proposal:edit', $user));
    }

    /** @test */
    public function it_should_disallow_edits_from_non_author()
    {
        $user = factory(User::class)->create();
        $user->role()->associate(Role::byName('student')->first())->save();
        $proposal = factory(Proposal::class)->create();

        $this->assertFalse($proposal->allowsActionfrom('proposal:edit', $user));
    }

    /** @test */
    public function it_should_allow_edits_from_user_with_proper_permissions()
    {
        $user = factory(User::class)->create();
        $user->role()->associate(Role::byName('administrator')->first())->save();
        $proposal = factory(Proposal::class)->create();

        $this->assertTrue($proposal->allowsActionfrom('proposal:edit', $user));
    }

    /** @test */
    public function it_should_disallow_edits_from_user_without_proper_permissions()
    {
        $user = factory(User::class)->create();
        $user->role()->associate(Role::byName('student')->first())->save();
        $proposal = factory(Proposal::class)->create();

        $this->assertFalse($proposal->allowsActionfrom('proposal:edit', $user));
    }
}
