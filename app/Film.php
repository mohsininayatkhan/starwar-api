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
}
