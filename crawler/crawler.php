<?php
namespace  Audiocollect\Crawler;

require_once __DIR__  . '/../lib/micrometa/src/Jkphl/Micrometa.php';
require_once __DIR__ . '/../lib/micrometa/vendor/autoload.php';


use Jkphl\Micrometa;

class Crawler
{

    public function crawl ($url)
    {
        $micrometaParser = new Micrometa($url);
        $micrometaObjectData = $micrometaParser->toObject();
        
        print_r($micrometaObjectData);
    }
}

$url = 'https://www.dehnhardt.org';

$crawler = new Crawler();
$crawler->crawl($url);
