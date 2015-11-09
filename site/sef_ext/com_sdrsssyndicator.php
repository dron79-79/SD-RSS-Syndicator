<?php

/**
* @Copyright Copyright (C) 2010 sdaprel.ru
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

/**
 * sh404SEF support for com_sdrsssyndicator component.
 * Author : Daniel Chapman 
 * contact : support@sdaprel.ru
 * 
 * {shSourceVersionTag: Version x - 2007-09-20}
 * 
 *     
 */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

// ------------------  standard plugin initialize function - don't change ---------------------------
global $sh_LANG;
$sefConfig = & shRouter::shGetConfig();  
$shLangName = '';
$shLangIso = '';
$title = array();
$shItemidString = '';
$dosef = shInitializePlugin( $lang, $shLangName, $shLangIso, $option);
if ($dosef == false) return;
// ------------------  standard plugin initialize function - don't change ---------------------------

// ------------------  load language file - adjust as needed ----------------------------------------
//$shLangIso = shLoadPluginLanguage( 'com_sdrsssyndicator', $shLangIso, '_SEF_SAMPLE_TEXT_STRING');
// ------------------  load language file - adjust as needed ----------------------------------------

// remove common URL from GET vars list, so that they don't show up as query string in the URL
shRemoveFromGETVarsList('option');
shRemoveFromGETVarsList('lang');
shRemoveFromGETVarsList('format');
if (!empty($Itemid))
  shRemoveFromGETVarsList('Itemid');



    $title[] = 'feed';
    
    if (!empty($feed_id)) {
        $title[] = $feed_id;
        shRemoveFromGETVarsList('feed_id'); 
    }          
    
	  shRemoveFromGETVarsList('task');                           // also remove task, as it is not needed
	                                                             // because we can revert the SEF URL without
	                                                             // it

  
// ------------------  standard plugin finalize function - don't change ---------------------------  
if ($dosef){
   $string = shFinalizePlugin( $string, $title, $shAppendString, $shItemidString, 
      (isset($limit) ? @$limit : null), (isset($limitstart) ? @$limitstart : null), 
      (isset($shLangName) ? @$shLangName : null));
}      
// ------------------  standard plugin finalize function - don't change ---------------------------
  
?>
