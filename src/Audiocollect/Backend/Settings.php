<?php
/*
 * Created on 28.01.2008
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

namespace Audiocollect\Backend;
use Audiocollect\Backend\Log;

class Settings{
	public static $TR_DOMAIN='/develop/punkt-k/tr';
	public static $CONNECTPARAMS="host=localhost port=5432 dbname=audiocollect user=audiocollect password=k2000p30";
	public static $LOGLEVEL = Log::INFO;
	public static $LOGPATH = "/tmp/Audiocollect.log";
}
?>
