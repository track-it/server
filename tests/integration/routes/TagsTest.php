<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Trackit\Models\Tag;
use Trackit\Models\Proposal;
use Trackit\Models\Project;
use Trackit\Models\Role;

class TagsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_should_add_an_existing_tag_to_a_taggable()
    {
        $proposal = factory(Proposal::class)->create(['author_id' => $this->getUser()->id]);
        $tag = factory(Tag::class)->create();
        $data = [
            'tags' => [$tag->name],
        ];

        $header = $this->createAuthHeader();
        $response = $this->json('POST', 'proposals/'.$proposal->id.'/tags', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());
        $tags = [];
        foreach ($jsonObject->data as $item) {
            $tags[] = $item->name;
        }

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue(in_array($tag->name, $tags));
    }

    /** @test */
    public function it_should_add_a_new_tag_to_a_proposal()
    {
        $proposal = factory(Proposal::class)->create(['author_id' => $this->getUser()->id]);

        $data = [
            'tags' => ['tagtagtagtag'],
        ];

        $header = $this->createAuthHeader();
        $response = $this->json('POST', 'proposals/'.$proposal->id.'/tags', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());
        $tags = [];
        foreach ($jsonObject->data as $item) {
            $tags[] = $item->name;
        }

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue(in_array($data['tags'][0], $tags));
    }

    /** @test */
    public function it_should_add_a_new_tag_to_a_project()
    {
        $project = factory(Project::class)->create();
        $project->addProjectUser('teacher', $this->getUser());

        $data = [
            'tags' => ['tagtagtagtag'],
        ];

        $header = $this->createAuthHeader();
        $response = $this->json('POST', 'projects/'.$project->id.'/tags', $data, $header)->response;
        $jsonObject = json_decode($response->getContent());
        $tags = [];
        foreach ($jsonObject->data as $item) {
            $tags[] = $item->name;
        }

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue(in_array($data['tags'][0], $tags));
    }

    /** @test */
    public function it_should_return_an_existing_tag()
    {
        $tag = factory(Tag::class)->create();

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'tags/'.$tag->id, [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($tag->id, $jsonObject->data->id);
    }

    /** @test */
    public function it_should_update_an_existing_tag()
    {
        $user = $this->getUser();
        $user->role()->associate(Role::byName('administrator')->first())->save();
        $tag = factory(Tag::class)->create();
        $data = [
            'name' => factory(Tag::class)->create()->name,
        ];

        $header = $this->createAuthHeader();
        $response = $this->json('PUT', 'tags/'.$tag->id, $data, $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($data['name'], $jsonObject->data->name);
    }

    /** @test */
    public function it_should_delete_an_existing_tag()
    {
        $tag = factory(Tag::class)->create();

        $header = $this->createAuthHeader();
        $response = $this->json('DELETE', 'tags/'.$tag->id, [], $header)->response;

        $this->assertEquals(204, $response->getStatusCode());
    }

    /** @test */
    public function it_should_return_all_tags_for_a_proposal()
    {
        $proposal = factory(Proposal::class)->create();
        $tag = factory(Tag::class)->create();
        $tag2 = factory(Tag::class)->create();
        $proposal->tags()->save($tag);
        $proposal->tags()->save($tag2);

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'proposals/'.$proposal->id.'/tags/', [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(2, count($jsonObject->data));
    }

    /** @test */
    public function it_should_return_all_tags_for_a_project()
    {
        $project = factory(Project::class)->create();
        $tag = factory(Tag::class)->create();
        $tag2 = factory(Tag::class)->create();
        $project->tags()->save($tag);
        $project->tags()->save($tag2);

        $header = $this->createAuthHeader();
        $response = $this->json('GET', 'projects/'.$project->id.'/tags/', [], $header)->response;
        $jsonObject = json_decode($response->getContent());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(2, count($jsonObject->data));
    }
}
