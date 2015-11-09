<?php defined('_JEXEC') or die('Restricted access');
 /**
 * sdaprel.ru sd RSS
 * 
 * @version		$Id: router.php 220 2010-02-05 14:08:29Z stian $
 * @package		sdrsssyndicator_feeds
 * @copyright	Copyright (C) 2007-2010 sdaprel.ru. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://sdaprel.ru
 */


function sdrsssyndicatorBuildRoute( &$query )
{
       $segments = array();
       if(isset($query['feed_id']))
       {
                $segments[] = $query['feed_id'];
                unset( $query['feed_id'] );
       }
       if(isset($query['format']))
       {
                $segments[] = $query['format'];
                unset( $query['format'] );
       };
       return $segments;
}

function sdrsssyndicatorParseRoute( $segments )
{
       $vars = array();

       $vars['feed_id'] = $segments[0];
       $vars['format'] = $segments[1];

       return $vars;
}
