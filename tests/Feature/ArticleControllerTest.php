<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;
use Throwable;

class ArticleControllerTest extends TestCase
{
    /**
     * @test
     * @dataProvider getCountsDataProvider
     * @throws Throwable
     */
    public function it_gets_filtered_paginated_articles_resource_collection(
        int $actualCount,
        int $expectedCount,
        int $pagesCount,
        ?array $overrideData = null,
        ?array $filters = null,
        ?int $oppositeCount = null,
        ?array $oppositeData = null,
    ): void {
        $this->signIn();

        Article::factory($actualCount)->create($overrideData);

        if ($oppositeCount) {
            Article::factory($oppositeCount)->create($oppositeData);
        }

        $response = $this->json('GET', '/api/articles', $filters ?? [])
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'title',
                        'category',
                        'author_name',
                        'comments_count',
                        'can' => [
                            'update',
                            'delete',
                        ],
                    ],
                ],
                'meta',
                'links',
            ])
            ->assertJsonCount($expectedCount, 'data')
            ->decodeResponseJson();

        $this->assertEquals($response['meta']['last_page'], $pagesCount);
    }

    /** @test */
    public function it_checks_unauthorized_user_can_not_get_articles()
    {
        $this->getJson('/api/articles')
            ->assertUnauthorized();
    }

    /** @test */
    public function it_gets_article_resource()
    {
        $this->signIn();

        $article = Article::factory()->create();

        $articleResourceData = [
            'id' => $article->id,
            'title' => $article->title,
            'category' => $article->category,
            'author_name' => $article->author->name,
            'comments' => [],
            'date' => $article->created_at->toFormattedDateString(),
        ];

        $this->getJson("/api/articles/{$article->id}")
            ->assertOk()
            ->assertJson($articleResourceData);
    }

    /** @test */
    public function it_checks_unauthorized_user_can_not_get_article()
    {
        $article = Article::factory()->create();

        $this->getJson("/api/articles/{$article->id}")
            ->assertUnauthorized();
    }

    /** @test */
    public function it_deletes_article()
    {
        $this->signIn();

        /** @var Article $article */
        $article = Article::factory()->create();

        /** @var Comment $comment */
        $comment = Comment::factory()->create();

        $article->author()
            ->associate($this->loggedUser)
            ->save();

        $comment->article()
            ->associate($article)
            ->save();

        $this->deleteJson("/api/articles/{$article->id}")
            ->assertOk();

        $this->assertSoftDeleted($comment);
        $this->assertSoftDeleted($article);
    }

    /** @test */
    public function it_checks_non_article_owner_can_not_delete_article()
    {
        $this->signIn();

        /** @var Article $article */
        $article = Article::factory()->create();

        $article->author()->associate(User::factory()->create())
            ->save();

        $this->deleteJson("/api/articles/{$article->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('articles', ['id' => $article->id]);
    }

    /** @test */
    public function it_checks_unauthorized_user_can_not_delete_article()
    {
        $article = Article::factory()->create();

        $this->deleteJson("/api/articles/{$article->id}")
            ->assertUnauthorized();
    }


    /**
     * @test
     * @throws Throwable
     */
    public function it_creates_article()
    {
        $this->signIn();

        $article = Article::factory()->make();

        $createdArticle = $this->postJson('/api/articles/', $article->toArray())
            ->assertCreated()
            ->decodeResponseJson();

        $this->assertEquals($article->title, $createdArticle['title']);

        $this->assertDatabaseHas('articles', ['title' => $article->title]);
    }

    /**
     * @test
     * @dataProvider getInvalidDataProvider
     */
    public function it_gets_validation_errors_on_trying_create_invalid_article(array $invalidData, string $errorField)
    {
        $this->signIn();

        $article = Article::factory()->make($invalidData);

        $this->postJson('/api/articles/', $article->toArray())
            ->assertJsonValidationErrorFor($errorField);

        $this->assertDatabaseMissing('articles', $article->toArray());
    }

    /**
     * @test
     * @throws Throwable
     */
    public function it_updates_article()
    {
        $this->signIn();

        $article = Article::factory()->create();
        $article->author()
            ->associate($this->loggedUser)
            ->save();
        $updateData = Article::factory()->make();

        $createdArticle = $this->putJson("/api/articles/{$article->id}", $updateData->toArray())
            ->assertOk()
            ->decodeResponseJson();

        $this->assertEquals($updateData->title, $createdArticle['title']);

        $this->assertDatabaseHas('articles', ['title' => $updateData->title]);
    }

    /**
     * @test
     * @throws Throwable
     */
    public function it_checks_non_article_owner_can_not_update_article()
    {
        $this->signIn();

        $article = Article::factory()->create();
        $article->author()
            ->associate(User::factory()->create())
            ->save();
        $updateData = Article::factory()->make();

        $this->putJson("/api/articles/{$article->id}", $updateData->toArray())
            ->assertForbidden();

        $this->assertDatabaseMissing('articles', ['title' => $updateData->title]);
    }


    /**
     * @test
     * @dataProvider getInvalidDataProvider
     */
    public function it_gets_validation_errors_on_trying_update_article_with_invalid_data(
        array $invalidData,
        string $errorField
    ): void {
        $this->signIn();

        $article = Article::factory()->create();
        $updateData = Article::factory()->make($invalidData);

        $this->putJson("/api/articles/{$article->id}", $updateData->toArray())
            ->assertJsonValidationErrorFor($errorField);

        $this->assertDatabaseMissing('articles', $invalidData);
    }

    /**
     * @test
     * @throws Throwable
     */
    public function it_checks_unauthorized_user_can_not_update_article()
    {
        $article = Article::factory()->create();
        $updateData = Article::factory()->make();

        $this->putJson("/api/articles/{$article->id}", $updateData->toArray())
            ->assertUnauthorized();
    }

    /** @test */
    public function it_checks_unauthorized_user_can_not_create_article()
    {
        $article = Article::factory()->create();

        $this->deleteJson("/api/articles/{$article->id}")
            ->assertUnauthorized();
    }

    /** @test */
    public function it_gets_unique_article_categories()
    {
        $this->signIn();

        Article::factory(3)->create(['category' => 'some']);

        $this->getJson('/api/articles/categories')
            ->assertOk()
            ->assertJson([
                'some',
            ]);
    }

    private function getInvalidDataProvider(): array
    {
        return [
            'required title' => [['title' => null], 'title'],
            'required category' => [['category' => null], 'category'],
            'required content' => [['content' => null], 'content'],
            'invalid title' => [['title' => true], 'title'],
            'invalid category' => [['category' => true], 'category'],
            'invalid content' => [['content' => true], 'content'],
            'invalid title length' => [['title' => Str::random(256)], 'title'],
            'invalid category length' => [['category' => Str::random(101)], 'category'],
            'invalid content length' => [['content' => Str::random(20001)], 'content'],
        ];
    }

    private function getCountsDataProvider(): array
    {
        return [
            '5 records' => [5, 5, 1],
            '15 records' => [15, 15, 1],
            '16 records' => [16, 15, 2],
            '30 records' => [30, 15, 2],
            'category filter no coincidence' => [
                2,
                0,
                1,
                ['category' => 'category_example'],
                ['categories' => ['test']],
                1,
                ['category' => 'opposite_category'],
            ],
            'category filter' => [
                2,
                2,
                1,
                ['category' => 'category_example'],
                ['categories' => ['category_example']],
                1,
                ['category' => 'opposite_category'],
            ],
            'query filter no coincidence' => [
                2,
                0,
                1,
                ['title' => 'title_example'],
                ['query' => 'test'],
                1,
                ['title' => 'opposite_title'],
            ],
            'query filter' => [
                2,
                2,
                1,
                ['title' => 'title_example'],
                ['query' => 'title_ex'],
                1,
                ['title' => 'opposite_title'],
            ],
            'date from no coincidence' => [
                2,
                0,
                1,
                ['created_at' => '2020-12-10'],
                ['date_from' => '2020-12-11'],
                1,
                ['created_at' => '2020-12-09'],
            ],
            'date from filter' => [
                2,
                2,
                1,
                ['created_at' => '2020-12-10'],
                ['date_from' => '2020-12-10'],
                1,
                ['created_at' => '2020-12-09'],
            ],
            'date to no coincidence' => [
                2,
                0,
                1,
                ['created_at' => '2020-12-10'],
                ['date_to' => '2020-12-08'],
                1,
                ['created_at' => '2020-12-09'],
            ],
            'date to filter' => [
                2,
                2,
                1,
                ['created_at' => '2020-12-10'],
                ['date_to' => '2020-12-10'],
                1,
                ['created_at' => '2020-12-11'],
            ],
            'multiple filters: date' => [
                2,
                3,
                1,
                ['created_at' => '2020-12-10'],
                ['date_to' => '2020-12-10', 'date_from' => '2020-12-09'],
                1,
                ['created_at' => '2020-12-09'],
            ],
            'multiple filters: category, query' => [
                2,
                2,
                1,
                ['category' => 'example_category', 'title' => 'Example'],
                ['categories' => ['example_category'], 'query' => 'Ex'],
                1,
                ['category' => 'opposite', 'title' => 'Example'],
            ],
        ];
    }
}
