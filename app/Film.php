<?php

namespace App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Film extends Eloquent 
{
    protected $connection = 'mongodb';
    protected $collection = 'films';   

    public static function getLongestOpeningCrawl()
    {
    	$pipeline = [
			['$project' => 
				[
					'_id' => 0,
					'opening_crawl' => 1,
					'title' => 1,
					'str_length' => ['$strLenCP' => '$opening_crawl']
				]
			],
			['$sort' => ['str_length' => -1]],
			['$limit' => 1],	
		];

		$cursor = Film::raw()->aggregate($pipeline);
		$document = current($cursor->toArray());
		return $document === false ? null : iterator_to_array($document);
    }

    public static function getPopularCharacter($top=1) 
    {
    	$top = (int) $top;

    	$result = [];

    	$operator = 
    	[
			[
				'$lookup' => [
					'from' => "people",
					'localField' => "characters",
					'foreignField' => "id",
					'as' => "film_people"
				]
			],
			[
				'$unwind' => '$film_people'
			],
			[
				'$group' => [
					'_id' => '$film_people.id', 
					'id' => ['$first' => '$film_people.id'], 
					'name' => ['$first' => '$film_people.name'], 
					'count' => ['$sum' => 1]
				]
			],
			['$sort' => ['count' => -1]],
			['$project' => ['_id' => 0]
			],
			['$limit' => $top],
		];

		$cursor = Film::raw()->aggregate($operator);
		
		foreach ($cursor as $document) {
			array_push($result,(iterator_to_array($document)));
		}
		return $result;		
    }

    public static function getSpeciesByAppearance()
    {
    	$result = [];

    	$operator = 
    	[
			[
				'$lookup' => [
					'from' => "species",
					'localField' => "species",
					'foreignField' => "id",
					'as' => "film_species"
				]
			],
			[
				'$unwind' => '$film_species'
			],
			[
				'$group' => [
					'_id' => '$film_species.id', 
					'id' => ['$first' => '$film_species.id'], 
					'name' => ['$first' => '$film_species.name'], 
					'count' => ['$sum' => 1]
				]
			],
			['$project' => ['_id' => 0]],
			['$sort' => ['count' => -1]]			
		];

		$cursor = Film::raw()->aggregate($operator);
		
		foreach ($cursor as $document) {
			array_push($result,(iterator_to_array($document)));
		}
		return $result;	
    }

    public static function getPilotsByPlanet()
    {
    	$result = [];    	

    	$operator = 
    	[
			[
				'$lookup' => [
					'from' => "vehicles",
					'localField' => "vehicles",
					'foreignField' => "id",
					'as' => "films_vehicles"
				]
			],
			[
				'$unwind' => '$films_vehicles'
			],
			[
				'$lookup' => [
					'from' => "people",
					'localField' => "films_vehicles.pilots",
					'foreignField' => "id",
					'as' => "films_vehicles_pilots"
				]
			],
			[
				'$unwind' => '$films_vehicles_pilots'
			],
			[
				'$lookup' => [
					'from' => "planets",
					'localField' => "films_vehicles_pilots.homeworld",
					'foreignField' => "id",
					'as' => "films_vehicles_pilots_planets"
				]
			],
			[
				'$unwind' => '$films_vehicles_pilots_planets'
			],
			[
				'$group' => [
					'_id' => '$films_vehicles_pilots_planets.id',
					'planet' =>  ['$first' => '$films_vehicles_pilots_planets.name'], 
					'pilots' => ['$first' => '$films_vehicles.pilots'], 
					'count' => ['$sum' => 1]
				]
			],
			['$project' => ['_id' => 0]],
			['$sort' => ['count' => -1]]			
		];

		$cursor = Film::raw()->aggregate($operator);
		
		
		foreach ($cursor as $document) {
			$res = iterator_to_array($document);
			$pilots = $res['pilots'];
			$ids = iterator_to_array($pilots);
			$res['pilots_detail'] = [];

			if(!empty($ids) && count($ids) && is_array($ids)) {
				$res['pilots_detail'] = Self::getPilotsDetail($ids);				
			}
			//unset($res['pilots']);
			array_push($result, $res);
		}
		return $result;	
    }

    public static function getPilotsDetail($ids) {
    	return Specie::getSpecieByIds($ids);
    }

}
