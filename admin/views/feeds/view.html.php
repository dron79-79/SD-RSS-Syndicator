<?php

/**
* @Copyright Copyright (C) 2010 sdaprel.ru
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class sdrsssyndicatorViewFeeds extends JView
{
	function display($tpl = null)
	{
		$text = 'Feed manager';
		JToolBarHelper::title(   JText::_( 'sdrsssyndicator_feeds').': <small><small>[ ' . $text.' ]</small></small>', 'addedit.png' );
		
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::deleteList();
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
		
		$items		= & $this->get( 'Data');
		$total		= & $this->get( 'Total');
		$pagination = & $this->get( 'Pagination' );
		
		$this->assignRef('items',		$items);
		$this->assignRef('pagination',	$pagination);
		
		parent::display($tpl);
	}
}
?>