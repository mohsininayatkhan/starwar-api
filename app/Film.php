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

}
