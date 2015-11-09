<?php

/**
* @Copyright Copyright (C) 2010 sdaprel.ru
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class sdrsssyndicatorViewApls extends JView
{

        protected $apls=null; //объект апликации
        protected $paramas_a=null; //обект со списком параметров апликации


    public function display($tpl = null)
	{
		$this->apls = $this->get('Apls');
		$this->paramas_a = $this->get('Params_a');
                //$model = $this->getModel();
               // print_r($model);die();

       // $this->apls = $this->get('Apls');
//print_r($this->apls);die();
//echo 'fffffffff';die();
		JToolBarHelper::title(   JText::_( 'Настройка апликации').': <small><small>[ ' . $this->apls->name.' ]</small></small>', 'addedit.png' );
		JToolBarHelper::apply();
		JToolBarHelper::save();
                JToolBarHelper::cancel( 'cancel', 'close' );
		$lists = array();

                //rss type list
		// new cod Dashko Andrey
		//$rssType[] = JHTML::_('select.option', 'YANDEX','RSS 2.0 vs Яндекс');
		//$rssType[] = JHTML::_('select.option', 'RAMBLER','RSS 2.0 vs Рамблер');
		// end cod
		//$rssType[] = JHTML::_('select.option', '2.0','RSS 2.0');
		//$rssType[] = JHTML::_('select.option', '1.0','RSS 1.0');
		//$rssType[] = JHTML::_('select.option', '0.91','RSS 0.91');
		//$rssType[] = JHTML::_('select.option', 'ATOM','ATOM');
		//$rssType[] = JHTML::_('select.option', 'OPML','OPML');
		//$rssType[] = JHTML::_('select.option', 'MBOX','MBOX');
		//$rssType[] = JHTML::_('select.option', 'HTML','HTML');
		//$rssType[] = JHTML::_('select.option', 'JS','JS');

		//$lists['rssTypeList'] = JHTML::_('select.genericlist', $rssType, 'feed_type', 'class="inputbox"', 'value', 'text', $isNew ? $default->defaultType : $feed->feed_type, 'feed_type');


		$this->assignRef('lists', $lists);

                parent::display($tpl);
	}
}
?>