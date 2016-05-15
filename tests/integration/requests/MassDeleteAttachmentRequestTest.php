<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Trackit\Models\Role;
use Trackit\Models\Attachment;
use Trackit\Models\Proposal;

class MassDeleteAttachmentRequestTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_delete_all_attachments_if_user_has_permissions_for_them()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('customer')->first())->save();
        $proposal = factory(Proposal::class)->create(['author_id' => $user->id]);
        $attachment = factory(Attachment::class)->create();
        $proposal->attachments()->save($attachment);
        $proposal->save();

        $proposal2 = factory(Proposal::class)->create(['author_id' => $user->id]);
        $attachment2 = factory(Attachment::class)->create();
        $proposal2->attachments()->save($attachment2);
        $proposal2->save();

        $data = [
            'attachment_ids' => [$attachment->id, $attachment2->id]
        ];

        $header = $this->createAuthHeader();
        $response = $this->json('DELETE', 'attachments/', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(204, $response->getStatusCode());
    }

    /** @test */
    public function it_should_return_error_if_user_does_not_have_permission_to_delete_all_attachments()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('customer')->first())->save();
        $proposal = factory(Proposal::class)->create(['author_id' => $user->id]);
        $attachment = factory(Attachment::class)->create();
        $proposal->attachments()->save($attachment);
        $proposal->save();

        $proposal2 = factory(Proposal::class)->create();
        $attachment2 = factory(Attachment::class)->create();
        $proposal2->attachments()->save($attachment2);
        $proposal2->save();

        $data = [
            'attachment_ids' => [$attachment->id, $attachment2->id]
        ];

        $header = $this->createAuthHeader();
        $response = $this->json('DELETE', 'attachments/', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(403, $response->getStatusCode());
    }
}
