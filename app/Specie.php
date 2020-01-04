<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Specie extends Eloquent
{    
	protected $connection = 'mongodb';
    protected $collection = 'species'; 

    public static function getSpecieByIds($ids) 
    {
    	
    	$result = [];

    	$people = [];    	
    	
    	foreach ($ids as $id) {
    		$data = ['people_id' => $id];
    		array_push($people, $data);
    	}

    	$operator = 
    	[
			[
				'$lookup' => [
					'from' => "people",
					'localField' => "people",
					'foreignField' => "id",
					'as' => "species_people"
				]
			],
			[
				'$unwind' => '$species_people'
			],
			[
				'$group' => [
					'_id' => '$species_people._id', 
					'people_id' => ['$first' => '$species_people.id'], 
					'name' => ['$first' => '$species_people.name'], 
					'specie' => ['$first' => '$name']
				]
			],
			[
				'$match' => [
					'$or' => $people
				]
			]
		];

		$cursor = Specie::raw()->aggregate($operator);
		
		foreach ($cursor as $document) {
			array_push($result,(iterator_to_array($document)));
		}
		return $result;
    } 
}

