<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FilmTest extends TestCase
{ 

    /** @test */
    public function can_access_longest_opening_crawl()
    {
        $response = $this->json('GET', 'api/film/crawl/longest');
        
        $response
            ->assertStatus(200)
            ->assertJson([
                'title' => true,]
        );
    }

    /** @test */
    public function can_access_popular_character()
    {
        $response = $this->json('GET', 'api/film/character/top');
        
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'name', 'count']
            ]);
    }

    /** @test */
    public function can_access_species_by_appearance()
    {
        $response = $this->json('GET', 'api/film/species');
        
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'name', 'count']
            ]);
    }

    /** @test */
    public function can_access_pilots_by_planet()
    {
        $response = $this->json('GET', 'api/film/planet/pilots');
        
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['planet', 'pilots', 'count']
            ]);
    }

}
