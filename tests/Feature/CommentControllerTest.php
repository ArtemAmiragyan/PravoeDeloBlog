<?php

namespace Tests\Feature;

use App\Models\Comment;
use Illuminate\Support\Str;
use Tests\TestCase;
use Throwable;

class CommentControllerTest extends TestCase
{
    /**
     * @test
     * @throws Throwable
     */
    public function it_creates_comment()
    {
        $this->signIn();

        $comment = Comment::factory()->make();

        $createdComment = $this->postJson('/api/comments/', $comment->toArray())
            ->assertCreated()
            ->decodeResponseJson();

        $this->assertEquals($comment->content, $createdComment['content']);

        $this->assertDatabaseHas('comments', ['content' => $comment->content]);
    }

    /**
     * @test
     * @dataProvider getInvalidDataProvider
     */
    public function it_gets_validation_errors_on_trying_create_invalid_comment(array $invalidData, string $errorField)
    {
        $this->signIn();

        $comment = Comment::factory()->make($invalidData);

        $this->postJson('/api/comments/', $comment->toArray())
            ->assertJsonValidationErrorFor($errorField);

        $this->assertDatabaseMissing('comments', $comment->toArray());
    }

    private function getInvalidDataProvider(): array
    {
        return [
            'required article' => [['article_id' => null], 'article_id'],
            'required content' => [['content' => null], 'content'],
            'invalid article_id' => [['article_id' => 0], 'article_id'],
            'invalid content' => [['content' => true], 'content'],
            'invalid content length' => [['content' => Str::random(256)], 'content'],
        ];
    }
}
