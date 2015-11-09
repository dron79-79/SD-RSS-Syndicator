<?php

/**
* @Copyright Copyright (C) 2010 sdaprel.ru
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

class sdrsssyndicatorModelsdrsssyndicator extends JModel
{

	var $_data = null;
	var $_id = null;	
	var $_content = null;
	var $_key = null;
	
	function __construct()
	{
		parent::__construct();

		global $option;
		
		$id = JRequest::getInt('feed_id',  0, '', 'int');
		$this->setId((int)$id);
		//$this->_key = $feed->feed_key;
	}
	
	function setId($id)
	{
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}
	

	function _buildQuery()
	{
		$query = "SELECT * FROM `#__sdrsssyndicator_feeds` WHERE id = $this->_id";
		return $query;
	}

	function getData()
	{
		$db = JFactory::getDBO();
		// Load the data
		if (empty( $this->_data )) {
			$query = $this->_buildQuery();
			$db->setQuery( $query );
			$this->_data = $db->loadObject();			
		}
		if (!$this->_data || $this->_data->published == 0) {
			$this->_data = array();	
		}
		return $this->_data;
	}
	
	
	function getContent()
	{
		
		if (null === ($feed = $this->getData())) {
		    JError::raiseWarning( 'SOME_ERROR_CODE', JText::_( 'Error Loading Modules' ) . $db->getErrorMsg());
		    return false;
		}
		
		$seclist = $feed->msg_sectlist; 
		$FPItemsOnly = $feed->msg_FPItemsOnly; 
		$inclExclCatList = $feed->msg_includeCats; 
		$excatlist = $feed->msg_excatlist;
		$exitems = str_replace(" ", "", $feed->msg_exitems);
		$count = $feed->msg_count;

		$db = JFactory::getDBO();
		/*
		
		unused code
		*/
		//Xipat - VH (Feb 09 2009): Remove unuse code
		/*
		if ($feed->msg_sectcat != "") {
		$tmp_cats = explode(",", $feed->msg_sectcat);
		$cats = "";
		foreach ($tmp_cats as $tmp_cat) {
			if ($cats == "") {
				$cats = "\nAND (c.title = '$tmp_cat' ";
			} else {
				$cats .= "\nOR c.title = '$tmp_cat' ";
			}
		}
		$cats .= ") ";

		}
        */
        $date = JFactory::getDate();           
		//$now = date( "Y-m-d H:i:s",time()+$mainframe->getCfg('offset')*60*60 );
        $now = $date->toMySQL();         
			
		switch (strtolower( $feed->msg_orderby )) {
			case 'date':
				$orderby = "a.created";
				break;
			case 'rdate':
				$orderby = "a.created DESC";
				break;
			case 'mdate':
				$orderby = "a.modified";
				break;
			case 'mrdate':
				$orderby = "a.modified DESC";
				break;
			case 'catsect':
				$orderby = intval($FPItemsOnly)==1 ? "f.ordering, a.ordering ASC, a.catid, a.sectionid" : "a.ordering ASC, a.catid, a.sectionid";
				break;
			case 'artord':
				$orderby = "a.ordering";
				break;
			default:
				$orderby = "a.created";
				break;
		}  
		
		/* SELECT construction */
		$queryUncat = "";//Oct 25 2008: include uncategories
		$query 	=  "SELECT u.id as userid, c.id as catid, s.id as secid, a.id as id, a.*, a.introtext as itext, a.fulltext as mtext, u.name AS author, u.usertype, u.email as authorEmail, a.created_by_alias as authorAlias, a.created AS dsdate, c.title as catName, s.title as sectName,"
		//Oct 24 2008: include slug and catslug for work with JRoute
				. 'CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'
				. 'CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(":", c.id, c.alias) ELSE c.id END as catslug'
			;
		/* FROM */
		$query	.=  "\nFROM #__content AS a"
			;
		
		$query 	.= "\nLEFT JOIN #__users AS u ON u.id = a.created_by"
			.  "\nLEFT JOIN `#__categories` AS c on c.id = a.catid "
			.  "\nLEFT JOIN `#__sections` AS s on s.id = c.section "
			;
		/* WHERE construction  */
		$query	.= "\n WHERE a.state='1'";
		/* JOIN construction */
		if (intval($FPItemsOnly)==1) {
			// frontpage Items only
			$query  .= "\n AND a.id IN (SELECT content_id FROM #__content_frontpage)";
		} elseif (intval($FPItemsOnly)==2) {
      // all articles except frontpage ones 
      $query  .= "\n AND a.id NOT IN (SELECT content_id FROM #__content_frontpage)";
    }
		
		if ($exitems != "") {
			$query	.= "\n AND a.id NOT IN (" . $exitems . ")";
		}
		if ($seclist!=="") {
			if($seclist == "0")// Xipat - VH: Query uncategorised
			{
			   	$query	.= "\n AND a.sectionid = 0";
				$queryUncat= " OR a.sectionid = 0 ";
			}
			else {
				$query	.= "\n AND s.id IN (" . $seclist . ")";
			}
		}	
		else {
			$queryUncat= " OR a.sectionid = 0 ";
		}
		
		if ($excatlist!=="") {
			if ($inclExclCatList){
				$query	.= "\n AND c.id IN (" . $excatlist . ")";
			} else {
				$query	.= "\n AND c.id NOT IN (" . $excatlist . ")";
			}
		}
		//$this->_key = "yandex";
		$this->_key = $feed->feed_key;
		
		if (($this->_key == '')or($this->_key == null)){ //
			$prkey='';
		}
		else {
		$prkey = "{".$this->_key."}";
		$prkey = "\n". 'AND (a.introtext LIKE "%'.$prkey.'%" OR a.fulltext LIKE "%'.$prkey.'%")';
		}
		//$prkey = "\n". 'AND (a.introtext LIKE "%{yandex}%" OR a.fulltext LIKE "%{yandex}%")';
		//key="";
	    $nullDate    = $db->getNullDate();
		$query	.= "\n AND a.access <= 0"	// item only public access check
			.  "\n AND (c.access <= 0 $queryUncat) "	// category only public access check
			.  "\n AND (s.access <= 0 $queryUncat)"	// section only public access check
			.  "\n" . 'AND (a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).')'
			.  "\n" . 'AND ( a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).')'
			.  $prkey
			;
		/* ORDER BY, LIMIT ...  construction */
		$query	.= "\nORDER BY $orderby"
			.  ($count ? (" LIMIT " . $count) : "")
			;

   // die ($query);

		if (empty( $this->_content )) {			
			$db->setQuery( $query );
			$this->_content = $db->loadObjectList();
				$shURL=$feed->shURL;


                        if($feed->shURL) {
                            $i=0;
                            foreach ($this->_content as $row) {
                               // $row->catid;//категория материала
                               // $row->id;//id материала
                                
                               $this->_content[$i]->sh=$this->getSH($row->id, $row->catid);
                                //$this->_content[$i]->sh='jjj';
                                 $i++;
                            }

                        }
		}
		if (!$this->_content) {
			$this->_content = array();
				
		}
        //die($query);
		return $this->_content;
		
	}
	 
	 function getSH($id=null,$catid=null) {

            $sh=null;
            $query2 	=  "SELECT pageid FROM #__sh404sef_pageids WHERE "
                        .' #__sh404sef_pageids.newurl LIKE "%option=com_content%" AND #__sh404sef_pageids.newurl LIKE "%catid='.(int)$catid.'%" AND #__sh404sef_pageids.newurl LIKE "%id='.(int)$id.'%" AND (  #__sh404sef_pageids.newurl NOT LIKE "%format=pdf%")';

           $this->_db->setQuery( $query2,0,1 );
            $sh = $this->_db->loadResult();

            //print_r($sh);
            if($sh){return $sh;}
            else{return '';}


        }
		
	function getMenuItemArray(){
		$type = 'content_blog_section';
		$database = JFactory::getDBO();
		$itemids = NULL;
	
		$database->setQuery("SELECT id, componentid "
							. "\n FROM #__menu "
							. "\n WHERE type = '$type'"
							. "\n AND published = 1");
		$rows = $database->loadObjectList();
		foreach ($rows as $row) {
			$itemids[$row->componentid] = $row->id;
		}
		return $itemids;
	}
	//cod Dron
	function getTitleSections($idsect)
	{
		$database = JFactory::getDBO();
		
			$database->setQuery("SELECT title"
					. "\n FROM #__sections"
					. "\n WHERE id = '$idsect'");
			$sections = $database->loadResult();
		
		return $sections;
	}
	
	function getTitleCategories($adcat)
	{
		$database = JFactory::getDBO();
		
			$database->setQuery("SELECT title"
					. "\n FROM #__categories"
					. "\n WHERE id = '$adcat'");
			$categories = $database->loadResult();
		
		return $categories;
	}
	//end cod Dron
	function getAllKey()
	{
		$database = JFactory::getDBO();
		
			$database->setQuery("SELECT feed_key"
					. "\n FROM #__sdrsssyndicator_feeds");
			$allkey = $database->loadResult();
		
		return $allkey;
	}
	//end cod Dron
	
	
}
?>