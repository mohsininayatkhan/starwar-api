<?php
namespace App\Repositories;

use App\Film;
//use App\Specie;

class FilmRepository implements FilmRepositoryInterface
{
	public function getLongestOpeningCrawl()
	{
		return Film::getLongestOpeningCrawl();
	}

	public function getPopularCharacter($top)
	{
		return [];
	}

	public function getSpeciesByAppearance()
	{
		return [];
	}

	public function getPilotsByPlanet()
	{
		return [];
	}
}