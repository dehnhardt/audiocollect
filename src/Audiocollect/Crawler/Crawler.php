<?php
namespace  Audiocollect\Crawler;


//$loader = require_once 'lib/micrometa/vendor/autoload.php';
require_once __DIR__."/../global_functions.php";
require_once 'lib/micrometa/src/Jkphl/Micrometa.php';

//$loader->addPsr4("Audiocollect\\", __DIR__ . "/..");

use Jkphl\Micrometa;
use Audiocollect\Backend\DB\Connection;

class Crawler
{
    private $db;
    
    public function __construct(){
        $this->db = Connection::getInstance();
    }
    
    public function getUrlFromDatabase( $id){
        $res = $this->db->getResultLine("select url from crawl_urls where urlid = $id");
        print_r( $res  );
        return $res['url'];
    }

    public function crawl ($url)
    {
        $micrometaParser = new Micrometa($url);
        $micrometaObjectData = $micrometaParser->toObject();
        
        $type = $micrometaObjectData->items[0]->types[0];
        $object = substr($type, strrpos($type, '/')+1);
        $handler = "handle$object";
        print( $micrometaObjectData->items[0]->types[0] . "\n");
        print $handler . "\n";
        if( method_exists($this, $handler)){
            $this->$handler($micrometaObjectData);
        }else{
            logToFile("No handler for object");
            return false;
        }
    }
    
    function handleSoftwareApplication( $object ){
        print "Software Application \n";        
        return true;
    }
}

$crawler = new Crawler();
$url = $crawler->getUrlFromDatabase(2);
$crawler->crawl($url);
