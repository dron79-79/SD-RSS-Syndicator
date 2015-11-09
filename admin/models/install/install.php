<?php

/**
* @Copyright Copyright (C) 2010 sdaprel.ru
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined( '_JEXEC' ) or die( 'Restricted access' );

function com_install()
{
	
//$database	= & JFactory::getDBO();

//xipat - vh Feb 09 2009: Change sql query, remove columns: feed_catsInTitle, msg_sectcat; add column: msg_exitems
//xipat - vh Oct 28 2008
//$database->setQuery("ALTER IGNORE TABLE `#__sdrsssyndicator_feeds`  DROP COLUMN `feed_catsInTitle`;");
//$database->query();
//$database->setQuery("ALTER IGNORE TABLE `#__sdrsssyndicator_feeds` CHANGE COLUMN `msg_sectcat` `msg_exitems` varchar(250) default NULL;");
//$database->query();	

    
	//Do updates of table structure if we need to
	//This is mainly for people doing an upgrade
	//$database->setQuery("SHOW FULL COLUMNS FROM #__sdrsssyndicator_feeds where field = 'msg_contentPlugins'");	
	//$results = $database->loadObjectList();
	
	//if (!count($results))
	//{
	//	$database->setQuery("	ALTER TABLE `#__sdrsssyndicator_feeds`
	//					ADD COLUMN `msg_contentPlugins` tinyint(1) default NULL;");
	//	$database->query();
	//}//if (!count($results))  

	//$database->setQuery("SHOW FULL COLUMNS FROM #__sdrsssyndicator_feeds where field = 'msg_includeCats'");	
	//$results = $database->loadObjectList();
	
	//if (!count($results))
	//{
	//	$database->setQuery("	ALTER TABLE `#__sdrsssyndicator_feeds`
	//					ADD COLUMN `msg_includeCats` tinyint(1) default NULL;");
	//	$database->query();
	//}//if (!count($results))   

?>

SD RSS Syndicator успешно установлен.

<p>Для более подробной информации о Sd RSS Syndicator посетите сайт <a href="http://www.sdaprel.ru">Разработчика</a>.</p>

<?php } 
