<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\FilmRepositoryInterface;

class FilmController extends Controller
{    
	protected $film;

	public function __construct(FilmRepositoryInterface $film)
    {
        $this->film = $film;
    }    

    public function getLongestOpeningCrawl()
    {
        return $this->film->getLongestOpeningCrawl();
    }

    public function getPopularCharacter($top=1)
    {
    	return $this->film->getPopularCharacter($top);
    }

    public function getSpeciesByAppearance()
    {
        return $this->film->getSpeciesByAppearance();
    }

    public function getPilotsByPlanet()
    {
        return $this->film->getPilotsByPlanet();        
    }
}
