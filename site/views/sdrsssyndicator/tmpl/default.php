<?php 
	defined('_JEXEC') or die('Restricted access'); 
	//define("TIME_ZONE","+06:00");
	include_once(JPATH_COMPONENT.DS.'views'.DS.'sdrsssyndicator'.DS.'tmpl'.DS.'feedcreator.class.php');

	global $mainframe;
	$configs =& JFactory::getConfig();
	$tzoffset = $configs->getValue('config.offset');
	//$tzoffset = 6;
	$tzoffset2 = sprintf("%01.2f",$tzoffset);
	$feedid = $this->id;
	$docache = intval($this->cache)>0?1:0;
	//add_stats($lists); /*<< MAD 2007/09/28 */ //Oct 24 2008
	

	//if type is Summaries then get numwords from db
	$numWords = $this->numWords > 0  ? $this->numWords: 10000; // numWord == 0 represents ALL

	/*if type is RSS then use admin defined default
 	if (($this->type=="RSS") || ($this->type=="RSSSUMM"))
	{
		//$this->type = "RSS".$row->defaultType;
		$this->type = "RSS2.0";// TO-DO
	}
	*/

	//make a feed id based filename
	$filename = JPATH_COMPONENT.DS."feed".DS."feed".$feedid.".xml";
	$rss = new UniversalFeedCreator();

	//Use cache if docache is set to 1
	if (intval($docache)==1) {
	    $rss->useCached($this->type,$filename,$this->cache); // use cached version if age<1 hour. May not return!
	}
	
	$rss->title 				= htmlspecialchars($this->title, ENT_QUOTES);
	$rss->description			= $this->description;
	$rss->link 				= JURI::root();
	
	$u = JFactory::getURI();
	$rss->syndicationURL 			= $u->toString();
	$rss->descriptionHtmlSyndicated 	= true;
    $rss->tzoneg=$tzoffset2;
	$image 					= new FeedImage();
	$image->title 				= $mainframe->getCfg('sitename');
	$image->url 				= $this->imgUrl;
	$image->link 				= JURI::root();
	$image->description			= $mainframe->getCfg('sitename');
	$image->descriptionHtmlSyndicated	= true;

	if ( $this->imgUrl!="") { $rss->image = $image; }
	//Xipat - VH (Feb 09 2009): Remove unuse code
	/*
	if (intval($this->catsInTitle)) {
		$rss->title .= " (".htmlspecialchars($this->cat).")";
	}
	*/
	$rows = $this->content;
	
	//used to trigger content plugins below
	JPluginHelper::importPlugin( 'content' );
  $dispatcher = JDispatcher::getInstance();   
//  print_r($rows);die();
	// Include menu itemid's in URLs by forming $itemids lookup array
	//$itemids = makeMenuItemArray('content_blog_section');
	$itemids = $this->menuitemarray;	
	foreach ($rows as $row) {
		$item 		 = new FeedItem();
		$item->title = htmlspecialchars($row->title);
		$itemid		 = $itemids[$row->sectionid];	
		
		$category	= & $this->get( 'Category' );
		   		
		$item->category = $category->title;
		$item->section  = $category->sectiontitle;

		// be sure itemid has some content!
		/*>>> AGE 20071012 */
		if ($itemid == "") $itemid = $mainframe->getItemid( $row->id, 0, 0 );		
		/*<<< AGE 20071012 */
		if ($itemid == "") {$itemid = 99999999;}
		
		//$item->link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid),false,2);
		if ($row->sh){
                 $item->link = JURI::root().   $row->sh;
                } else {
		$item->link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid),false,2);
                }
		
		
    $item->guid = $item->link;
    
    //Jroute produces htmlspecialchar modified urls. 
    //We need to decode them because the feedclass also specialchars them, giving us things like &amp;amp;
    //TODO - test special characters
    //$itemurl = htmlspecialchars_decode ($itemurl);
    

    /* >> DAN 2009/12/14 */
    /* fulltext options:
     * 0 -> Do nothing
     * 1 -> Read more link
     * 2 -> Add to intro text
     * 3 -> Use only full text
     */
    $AddReadMoreLink = false;   
		
    /*     Testing Case statement below. If it works, remove this code- D
    $words = $row->itext;
		if ($this->fulltext == 2) {
			$words .= $row->mtext;
		} */
		
		switch ($this->fulltext) {
			case 0:
			case 1:
				$words = $row->introtext;
				break;
			case 2:
				$words = $row->introtext.$row->fulltext;
				break;
			case 3:
				$words = $row->fulltext;
				break;
			case 4:
				$words = $row->introtext;
				$words2 = $row->fulltext;
				break;
			case 5:
				$words = $row->introtext;
				$words2 = $row->introtext.$row->fulltext;
				break;
		}          		
     
		// Check if $words is larger then the $numWords
		// Add some extra words because characters are count as words (20% extra)
		
		
				
		if($this->fulltext == 0)
		{
			$AddReadMoreLink = false;
			
		}
		
		if (($this->fulltext == 1 or $AddReadMoreLink)and($this->fulltext != 4) and ($this->fulltext != 5)) {
			if (strlen(trim($row->mtext)) > 0 or $AddReadMoreLink){
			    //Dron
				$words .= "\n<p><a href=\"" . $item->link . "\">" . JText::sprintf('Read more...') . "</a></p>"; 
			}
		}
    	if (($this->fulltext == 4) or ($this->fulltext == 5)) {
			$words2=addAbsoluteURL($words2);
			$imgtab=first_img_src($words2);
			//$extimg = strtolower(substr(strrchr($imgtab, '.'), 1));
			//$item->additionalElements = $imgtab;
			//$item->enclosure->type = $extimg;
			//print_r($imgtab);
			//$enclosure = new EnclosureItem();
			//print_r($words2);
			preg_match('/.*({youtube}).*/is',$words2,$you);
			if (sizeof($you)>0) {
				$enclosure = new EnclosureItem();
				$enclosure->url =$item->link;
				$enclosure->type='video/x-flv';
				$item->enclosure[] = $enclosure;
			}
			if ($imgtab != false )
			{
			$i=0;
			
			foreach ($imgtab as $imagese) {
			$enclosure = new EnclosureItem();
			$enclosure->url = $imagese;
			$extimage = strtolower(substr(strrchr($imagese, '.'), 1));
			if ($extimage=="jpg") $extimage="jpeg";
			$enclosure->type = "image/"."$extimage";
			//$enclosure->length = sprintf("%u", filesize($imagese));
			//$enclosure->length = fsize($imagese);
			$enclosure->length = '';
			$item->enclosure[] = $enclosure;
			$i=$i+1;
					
			}
			}
			$links = first_link ($words2);
			if ($links != false) {
			$i=0;
			for ($i = 0; $i < count($links[0]); $i++) {
			$Yandexrelateds = new YandexrelatedItem();
			$Yandexrelateds->url = $links[0][$i];
			$Yandexrelateds->text = $links[1][$i];
			$item->yandexrelated[$i] = $Yandexrelateds;
			}
			}
			
		}		
		//if ((!intval($this->renderHTML))or ($this->fulltext == 4) or ($this->fulltext == 5)){
		if (!intval($this->renderHTML)){
		  //Remove HTML tags if told not to render them
		  $words = noHTML ($words); 
		  $words2 = noHTML ($words2); 
		} else {     		  		  
			//Remove images if told not to render them	
      //Images will also get remove with HTML tags above	  	
		  if ((!intval($this->renderImages))or ($this->fulltext == 4) or ($this->fulltext == 5)) {
		    $words = delImagesFromHTML($words);
			$words2 = delImagesFromHTML($words2);
		  } 
		}

		/* Convert relative urls to absolute */
		$words = addAbsoluteURL($words);
		$textwords = str_replace("\n", " ", $words);
		$wordst = explode(" ", $textwords);
		$counttext = 0;
		foreach($wordst as $wordt)
		{
		if(strlen($wordt) > 3) $counttext++;
		}
		
		//$section = sdrsssyndicatorModelsdrsssyndicator::getSections($row->catid);
		if ($counttext > $numWords) 
		{			
		    $AddReadMoreLink = true;   		    
		    $words = word_limiter($words, $numWords);
			$words = $words ;
		}
		switch ($this->categoryItem) {
						
			case 1:
				$item->category = $row->sectName;
				break;
			case 2:
				$item->category = $row->catName;
				break;
			case 0:
			default:
				$item->category = '';
				break;
			
		}          		
		
		//$item->category				= $row->catName;
		//$item->section				= $row->sectName;
		 //$words = preg_replace('/.+{*[^\}]}.+/','',$words);
		$words = preg_replace('/.+({youtube}[^{}]+{\/youtube}).+/is','',$words);
        $words2 = preg_replace('/{youtube}[^{}]+{\/youtube}/is','',$words2);
		//{gallery}2014/20140211-yam0{/gallery}
		$words = preg_replace('/.+({gallery}[^{}]+{\/gallery}).+/is','',$words);
        $words2 = preg_replace('/{gallery}[^{}]+{\/gallery}/is','',$words2);

		
		$item->description 			= $words;
		$item->descriptionfull		= $words2;
		$item->descriptionHtmlSyndicated	= true;		

		//Many, many failed attempts to get the date right.
		//Kept here for a while in case issues arise again - Dec 2009
		//After some issues with the date not coming out correctly I am trying the exact code from Com_content
		//$itemDate = JFactory::getDate(JHTML::_('date', $row->dsdate, JText::_('DATE_FORMAT_LC2')));		
		//$itemDate = JFactory::getDate(JHTML::_('date', $row->dsdate), 0);
		//$itemDate = JFactory::getDate($row->dsdate, 0);		
		//$item->date 				= $itemDate->toRFC822() ;
		
		//$item->date = strftime("%a, %d %b %Y %H:%M:%S",strtotime($row->dsdate))." +0300";
		//$item->date = date($row->dsdate, 'D, d M Y g:i:s')." GMT\n";
		
		$item->date = date("r",strtotime($row->dsdate));
		$item->zonatime=$tzoffset;
		//unset($item->date->tzoffset);
		$item->source 				= JURI::root();		
		
		if ($this->renderAuthorFormat){
			$author = trim($row->authorAlias);
			
			if (empty($author)) $author = $row->author;
			
			$item->author 	= $author;
			$item->authorEmail	= $row->authorEmail;
		}
		if ($this->yandexgenre) {
			$item->yandexgenre = $this->yandexgenre;
		
		}
		//If needed, trigger content plugins on the row content.                     		
	  //TODO - expand this to allow for individual paramters for the plugin instances
	  $dispatcher->trigger( 'onPreparesdRSSFeedRow', array( &$item ) );  			
		
		
		$rss->addItem($item);		
	}
	
	//If needed, trigger content plugins on the feed as a whole.
	//TODO - expand this to allow for individual paramters for the plugin instances
	$dispatcher->trigger( 'onPreparesdRSSFeed', array( &$rss ) ); 			
    ob_end_clean();
	ob_start();   
 	//If we are using the cache and the time out is greater than 0, then generate and use a file.
 	//Otherwise generate the feed on the fly
	if (intval($docache)==1 && $this->cache > 0) 
	{
		$rss->saveFeed($this->type,$filename,true);
	} else {
		$rss->outputFeed($this->type);
	}                    

function noHTML($words) {
    $words = preg_replace("'<script[^>]*>.*?</script>'si","",$words);
	$words = preg_replace('/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/is','\2 (\1)', $words);
	$words = preg_replace('/<!--.+?-->/','',$words);
	//$words = preg_replace('/{.+?}/','',$words);
	$words = strip_tags($words);
	$words = preg_replace("/'/",'&apos;',$words);
	$words = preg_replace('/&nbsp;/',' ',$words);
	//$words = preg_replace('/&amp;/','&',$words);
	//$words = preg_replace('/&quot;/','"',$words);

	return $words;
}
function nospecsimvol($words) {
    $words = preg_replace('/&/','&amp;',$words);
	$words = preg_replace('/</','&lt;',$words);
	$words = preg_replace('/>/','&gt;',$words);
	$words = preg_replace("/'/",'&apos;',$words);
	$words = preg_replace('/"/','&quot;',$words);
	

	return $words;
}

function addAbsoluteURL($html) {
	$root_url = JURI::root();
	$html = preg_replace('@href="(?!http://)(?!https://)(?!mailto:)([^"]+)"@i', "href=\"{$root_url}\${1}\"", $html);
	$html = preg_replace('@src="(?!http://)(?!https://)([^"]+)"@i', "src=\"{$root_url}\${1}\"", $html);

	return $html;
}

/*
** Delete all the images from the url
*/
function delImagesFromHTML($html, $instances = -1) {
  //$html = preg_replace('/<img\\s.*>/i','', $html, $instances);
$html =   strip_tags($html,'<p><a>');
//$html = preg_replace('/(<img[^<>]+>)/Usi', '', $html, 1);
  return $html;
}

/* >> MAD 2007/10/09
 * Added function word_limiter
 */
function word_limiter($string, $limit = 100) {
	$words = array();
	$string = eregi_replace(" +", " ", $string);
	$array = explode(" ", $string);
	//$limit = (count($array) <= $numwords) ? count($array) : $numwords;
	for($k=0;$k < $limit;$k++)
	{
		if(($limit>0 && $limit == $k)||!isset($array[$k]))
			break;
		if (eregi("[0-9A-Za-zÀ-ÖØ-öø-ÿ]", $array[$k]))
			$words[$k] = $array[$k];
	}
	$txt = implode(" ", $words);
	return $txt;
}

function first_img_src($html) {
        if (stripos($html, '<img') !== false) {
            $imgsrc_regex = '#<\s*img[^>]*src[\s]*=[\s]*(["\'])(.*?)\1#im';
            preg_match_all($imgsrc_regex, $html, $matches);
            unset($imgsrc_regex);
            unset($html);
            if (is_array($matches) && !empty($matches)) {
                return $matches[2];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
function first_link ($html) {
	 if (stripos($html, '<a') !== false) {
	//preg_replace("~<a[^>]+href\s*=\s*[\x27\x22]?[^\x20\x27\x22\x3E]+[\x27\x22]?[^>]*>(.+?)</a>~is", '$1', $html_text);
	//$imgsrc_regex = '#<\s*a [^\>]*href\s*=\s*(["\'])(.*?)\1#a';
	//
	// "/<a.+?href=[\'\"](.+?)[\'\"]/si"
	//$imgsrc_regex = '/<a.+?href=[\'\"](.+?)[\'\"]/si';
			//$imgsrc_regex = '#<\s*a [^\>]*href\s*=\s*(["\'])(.+?)\1#im';
            //preg_match_all($imgsrc_regex, $html, $matches);
			
			//$matchessum[0]=$matches[2];
			$imgsrc_regex = '/<a\s+.*?href="(?!mailto:)([^"]+)"[^>]*>([^<]+)<\/a>/is';
			preg_match_all($imgsrc_regex, $html, $matches2);
			//print_r($matches2);
			$matchessum[1]=$matches2[2];
			$matchessum[0]=$matches2[1];
            unset($imgsrc_regex);
            unset($html);
            if (is_array($matchessum[0]) && !empty($matchessum[0])) {
                return $matchessum;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
function fsize($path)
{
$fp = fopen($path,"r");
$inf = stream_get_meta_data($fp);
fclose($fp);
foreach($inf["wrapper_data"] as $v)
if (stristr($v,"content-length"))
{
$v = explode(":",$v);
return trim($v[1]);
}
}