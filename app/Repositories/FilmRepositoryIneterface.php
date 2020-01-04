<?php
namespace App\Repositories;

interface FilmRepositoryInterface
{
	public function getLongestOpeningCrawl();

	public function getPopularCharacter($top);

	public function getSpeciesByAppearance();

	public function getPilotsByPlanet();
}