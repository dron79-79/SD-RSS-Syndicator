<?php
/**
* @Copyright Copyright (C) 2010 sdaprel.ru
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined( '_JEXEC' ) or die( 'Restricted access' );

class sdrsssyndicatorViewAbout
{

	function __construct()
	{
		$text = 'Сведения о продукте';
		JToolBarHelper::title(   JText::_( 'Сведения о продукте').': <small><small>[ ' . $text.' ]</small></small>', 'systeminfo.png' );
		$this->about();
	}
	
	function about()
	{
		?>
			<div class="m">
							
			<p align="left">компонет SD RSS Syndicator, разработан студией веб дизайна Апрель</p>
			<p align="left">информацию по этому проекту смотрите на странице проекта <a href="http://www.sdaprel.ru/content/view/738/51/">SD RSS Syndicator</a></p>
			
			<p align="left">Copyright 2010, <a href="http://sdaprel.ru/" target="_blank">www.sdaprel.ru</a>.</p>
				<div class="clr"></div>
			</div>
		<?php
	}

}
?>
