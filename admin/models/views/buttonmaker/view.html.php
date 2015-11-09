<?php

/**
* @Copyright Copyright (C) 2010 sdaprel.ru
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class sdrsssyndicatorViewButtonMaker extends JView
{
	function display($tpl = null)
	{
		$text = 'Button maker';
		JToolBarHelper::title(   JText::_( 'sdrsssyndicator_feeds').': <small><small>[ ' . $text.' ]</small></small>', 'mediamanager.png' );
				
		parent::display($tpl);
	}
}
?>