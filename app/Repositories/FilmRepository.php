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
		return Film::getPopularCharacter($top);
	}

	public function getSpeciesByAppearance()
	{
		return Film::getSpeciesByAppearance();
	}

	public function getPilotsByPlanet()
	{
		return [];
	}
}