<?php
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Article;
use App\Models\User; // Import the User model

class ArticleTest extends TestCase
{
    // use RefreshDatabase for reset database
    use  WithFaker;

    /**
     * Authenticate a user for testing.
     *
     * @return \App\Models\User
     */
    protected function authenticateUser()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        return $user;
    }

    public function test_can_create_article()
    {
        $user = $this->authenticateUser();

        $data = [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
        ];

        $this->post('/api/articles', $data)
            ->assertStatus(201)
            ->assertJson($data);
    }

    public function test_can_read_article()
    {
        $user = $this->authenticateUser();

        $article = Article::factory()->create();

        $this->get("/api/articles/$article->id")
            ->assertStatus(200)
            ->assertJson([
                'title' => $article->title,
                'content' => $article->content,
            ]);
    }

    public function test_can_update_article()
    {
        $user = $this->authenticateUser();

        $article = Article::factory()->create();

        $data = [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
        ];

        $this->put("/api/articles/$article->id", $data)
            ->assertStatus(200)
            ->assertJson($data);
    }

    public function test_can_delete_article()
    {
        $user = $this->authenticateUser();

        $article = Article::factory()->create();

        $this->delete("/api/articles/$article->id")
            ->assertStatus(200);

        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }

    // public function test_can_list_articles()
    // {
    //     $user = $this->authenticateUser();

    //     $articles = Article::factory(3)->create();

    //     $this->get('/api/articles')
    //         ->assertStatus(200)
    //         ->assertJson($articles->toArray());
    // }
}
