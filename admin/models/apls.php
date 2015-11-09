<?php

/**
* @Copyright Copyright (C) 2010 sdaprel.ru
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die( 'Restricted access' );

//jimport( 'joomla.application.component.model' );
//jimport('joomla.application.component.modellist');
jimport('joomla.application.component.modelitem');

//class sdrsssyndicatorModelFeed extends JModel
class sdrsssyndicatorModelApls extends JModelItem
{
	protected $text_prefix = 'COM_SDRSSSYNDICATOR';
	protected $data = null;
        protected $_db=null;
        protected $id_a=null; //id апликации
        protected $id_feed=null; //id канала
        protected $appZOO = null; //обект с функционалом ZOO
        protected $apls=null;

	public function __construct()
	{


		//global $option;
		//$mainframe = JFactory::getApplication();
                //$app = JFactory::getApplication();
		// Get the pagination request variables
		//$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		//$limitstart	= $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );
                //$authorId = $app->getUserStateFromRequest($this->context.'.filter.author_id', 'filter_author_id');
		//$this->setState('filter.author_id', $authorId);
              //  $limit=10;
 //$limitstart	=0;
 //$limit		= $app->getUserStateFromRequest( 'global.list.limit', 'limit', 10, 'int' );
	//	$limitstart	= $app->getUserStateFromRequest( 'global.list.limitstart', 'limitstart', 0, 'int' );

		// In case limit has been changed, adjust limitstart accordingly
	//	$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

	//	$this->setState('limit', $limit);
	//	$this->setState('limitstart', $limitstart);

		//edit feed
            //echo'ghh';die();
            $this->_db = JFactory::getDBO();
		$array = JRequest::getVar('cid',  0, '', 'array');
                $id_feed = JRequest::getVar('id',  0, '', 'int');
		$this->setId_a((int)$array[0]);
                $this->setId_feed($id_feed);
                require_once(JPATH_ADMINISTRATOR.'/components/com_zoo/config.php');
                $path = dirname(__FILE__);
                $this->appZOO= App::getInstance('zoo');
                $this->appZOO->path->register($path, 'com_sdrsssyndicator');
                parent::__construct();
	}

	private function setId_a($id)
	{
		// Set id and wipe data
		$this->id_a		= $id;
		//$this->_sdata	= null;
	}
        private function setId_feed($id)
	{
		// Set id and wipe data
		$this->id_feed		= $id;
		//$this->_sdata	= null;
	}

	//function publish($cid = array(), $publish = 1)
	//{
		//$user 	= JFactory::getUser();

	//	if (count( $cid ))
	//	{
	//		JArrayHelper::toInteger($cid);
	//		$cids = implode( ',', $cid );

	//		$query = "UPDATE `#__sdrsssyndicator_feeds` SET published='$publish'"
	//. "\nWHERE id IN ($cids)";
	//		$this->_db->setQuery( $query );
	//		if (!$this->_db->query()) {
	//			$this->setError($this->_db->getErrorMsg());
	//			return false;
	//		}
	//	}

	//	return true;
	//}

	public function getApls()
	{
		// Load the data
            //echo'ghh';die();
		if (empty( $this->apls )) {
			$query = "SELECT `id`,`name`"
					. "\n FROM `#__zoo_application`  WHERE id =". $this->id_a;
			$this->_db->setQuery( $query );
			$this->apls = $this->_db->loadObject();
		}
		if (!$this->apls) {
			$this->apls = new stdClass();
			$this->apls->id = 0;
		}
		return $this->apls;
	}
        public function getParams_a() {
           // $zoo = App::getInstance('zoo');
           //     $zoo->path->register($path, 'com_sdrsssyndicator');
                $application = $this->appZOO->table->application->get($this->id_a);// получили объект апликейшен ид1

           //     //$current_id = (int) $zoo->request->getInt('category_id', 1);
                //$url = $zoo->route->category(1);
           //     $item = $zoo->table->item->get(6); //получили объект item с ид 1

           //     $url = $zoo->route->item($item,false); // получаем кононический урл без слеша перед индекс
           //     $url_s = $zoo->route->item($item, true);// получаем кононический урл с слешем перед индекс
          //      $url_r = JRoute::_($zoo->route->item($item, true));// получаем SEF урл с слешем перед индекс
                //print_r($url_r);
          //      $alias = $zoo->string->sluggify($item->alias);//получаем слуг материала.
          //      $current_id = $item->getPrimaryCategoryId(); //получили родительскую категорию
          //      $params_i = $item->getParams('site'); //получаем параметры материала
          //      $params_a = $application->getParams('site');//получаем параметры пликейшенс
          //      $category=$zoo->table->category->get($current_id); //получили объект категории с ид 1
          //      $application=$zoo->table->application->get($category->application_id);
                //print_r($category->application_id);
             //$element = $item->getElement($element_identifier); работает но странно
             //$element_c=$item->elements[$element_identifier];// выборка значения елемента но нужно знать идетификатор.
          //   $autor=$item->getAuthor();
          //      $params_с=$category->getParams('site');//получаем параметры категории
          //      $grup_a=$application->application_group;//группа приложения
          //      $templ=$application->getTemplate();//объект шаблона
          //      $templ_name=$application->getTemplate()->name;//catalog
          //      $templ_res=$application->getTemplate()->resource; // applications:jbuniversal/templates/catalog/
          //      $templ_path=$application->getPath();// путь до папки с шаблоном C:\my_projects\joomla.local\www\media\zoo\applications\jbuniversal
                $rendererPath = $application->getPath() . DS . 'templates' . DS . $application->params->get('template') . DS . 'renderer';  // C:\my_projects\joomla.local\www\media\zoo\applications\jbuniversal\templates\catalog\renderer
                $layout='item';
                $layoutPath = $rendererPath . DS . $layout;
                print_r($layoutPath);
                //$text=$item->renderPosition('text', array('style' => 'block'));
               // print_r($text);
         //      $type= $item->type;//тип материала
          //     print_r($zoo->path->path('joomla:elements/zooapplication.php'));
         //     print_r($type);
         //     print_r($path);
              //foreach ($application->getTypes() as $type) {
                  //print_r($type);//die();
		//		$type->getApplication()->getCategoryTree();
                                //print_r($type);
                           //     $i=1;
                          //      $data=null;
                          //      print_r($type->getElements());
                //                foreach ($type->getElements() as $identifier => $element) {
				//print_r($item->elements[$identifier]);
                          //          $name = $element->config->get('name') ? $element->config->get('name') : $element->getElementType();
                          //      switch ($element->getElementType()) {
			//		case 'text':
			//		case 'textarea':
			//		case 'link':
			//		case 'email':
			//		case 'date':
			//			$data[$i][$name] = array();
			//			foreach ($item->elements[$identifier] as $self) {
			//				$data[$i][$name][] = $self['value'];
			//			}
			//			break;
			//		case 'country':
			//			$data[$i][$name] = $item->elements[$identifier]['country'];
			//			break;
			//		case 'gallery':
			//			$data[$i][$name] = $item->elements[$identifier]['value'];
			//			break;
			//		case 'image':
			//		case 'download':
			//			$data[$i][$name] = $item->elements[$identifier]['file'];
			//			break;
			//		case 'googlemaps':
			//			$data[$i][$name] = $item->elements[$identifier]['location'];
			//			break;
			//	}
//

			//	} //$i++; print_r($data);
			//}
        }

        //function getSections()
	//{
	//	if (empty( $this->_sections ))
	//	{
	//		$query = "SELECT id, title"
	//				. "\n FROM #__sections"
	//				. "\n WHERE published = 1"
	//				. "\n AND scope = 'content'"
	//				. "\n ORDER BY ordering"
	//				;
	//		$this->_sections = $this->_getList( $query );
	//	}
	//	return $this->_sections;
	//}

	//function save()
	//{
	//	$id = JRequest::getVar('id', '0', 'post', 'int');
	//	$a_msg_sectlist = JRequest::getVar('msg_sectlist', array(), 'post', 'array');
	//	$a_msg_excatlist = JRequest::getVar('msg_excatlist', array(), 'post', 'array');
        //        $a_msg_zoocatlist = JRequest::getVar('msg_zoocatlist', array(), 'post', 'array');
        //        $a_msg_zooapplist = JRequest::getVar('msg_zooapplist', array(), 'post', 'array');

	//	$msg_sectlist  = implode(',', $a_msg_sectlist);
	//	$msg_excatlist  = implode(',', $a_msg_excatlist);
        //        $msg_zoocatlist = implode(',', $a_msg_zoocatlist);
         //       $msg_zooapplist = implode(',', $a_msg_zooapplist);

	//	$feed_name = JRequest::getVar('feed_name', '', 'post', 'string');
        //$feed_name = $this->_db->Quote($this->_db->getEscaped($feed_name), false);

	//	$feed_description = JRequest::getVar('feed_description', '', 'post', 'string');
        //$feed_description = $this->_db->Quote($this->_db->getEscaped($feed_description), false);

	///	$feed_type = JRequest::getVar('feed_type', '', 'post', 'string');
	//	$feed_key = JRequest::getVar('feed_key', '', 'post', 'string');
		//$feed_key = $this->_db->Quote($this->_db->getEscaped($feed_key), false); // ????

	//	$yandex_genre = JRequest::getVar('yandex_genre', '', 'post', 'string');

	//	$feed_cache = JRequest::getVar('feed_cache', '', 'post', 'string');

	//	$feed_imgUrl = JRequest::getVar('feed_imgUrl', '', 'post', 'string');
        //$feed_imgUrl = $this->_db->Quote($this->_db->getEscaped($feed_imgUrl), false);

	//	$feed_button = JRequest::getVar('feed_button', '', 'post', 'string');
        //$feed_button = $this->_db->Quote($this->_db->getEscaped($feed_button), false);

	//	$feed_renderAuthorFormat = JRequest::getVar('feed_renderAuthorFormat', '', 'post', 'string');
	//	$feed_renderHTML   = JRequest::getVar('feed_renderHTML', '0', 'post', 'int');
	//	$feed_renderImages = JRequest::getVar('feed_renderImages', '0', 'post', 'int');
	//	$feed_categoryItem = JRequest::getVar('feed_categoryItem', '0', 'post', 'int');
	//	$msg_count = JRequest::getVar('msg_count', '', 'post', 'string');
	//	$msg_orderby=JRequest::getVar('msg_orderby', '', 'post', 'string');
	//	$msg_numWords = JRequest::getVar('msg_numWords', '0', 'post', 'int');
	//	$msg_FPItemsOnly = JRequest::getVar('msg_FPItemsOnly', '0', 'post', 'int');
	//	$msg_fulltext = JRequest::getVar('msg_fulltext', '0', 'post', 'int');
	//	$published = JRequest::getVar('published', '0', 'post', 'int');
		//VH Oct 27 2008
	//	$msg_exitems = JRequest::getVar('msg_exitems', '', 'post', 'string');

	//	$msg_contentPlugins = JRequest::getVar('msg_contentPlugins', '0', 'post', 'int');
	//	$msg_includeCats = JRequest::getVar('msg_includeCats', '0', 'post', 'int');

	//	$isNew = ($id<1);
	//	if($isNew)
	//		$query = "INSERT INTO #__sdrsssyndicator_feeds (`feed_name`,`feed_description`, `feed_type`, `feed_cache` ,`feed_imgUrl`,
	//				  `feed_button`, `feed_renderAuthorFormat`,  `feed_renderHTML`, `feed_renderImages` , `feed_categoryItem`, `msg_count` , `msg_orderby`,
	//				  `msg_numWords` , `msg_FPItemsOnly`, `msg_sectlist` , `msg_excatlist` , `msg_ZOOcatlist`, `msg_ZOOapp`, `msg_includeCats`, `msg_fulltext` , `yandex_genre` , `msg_exitems` ,
	//				  `msg_contentPlugins`, `published`, `feed_key`)
	//					VALUES
	//					(
	//						$feed_name,
	//						$feed_description,
	//						'$feed_type',
	//						'$feed_cache',
	//						$feed_imgUrl,
	//						$feed_button,
	//						'$feed_renderAuthorFormat',
	//						'$feed_renderHTML',
	//						'$feed_renderImages',
	//						'$feed_categoryItem',
	//						'$msg_count',
	//						'$msg_orderby',
	//						'$msg_numWords',
	//						'$msg_FPItemsOnly',
	//						'$msg_sectlist',
	//						'$msg_excatlist',
         //                                               '$msg_zoocatlist',
         ///                                               '$msg_zooapplist',
	//						'$msg_includeCats',
	//						'$msg_fulltext',
	//						'$yandex_genre',
	//						'$msg_exitems',
	//						'$msg_contentPlugins',
	//						'$published',
	//						'$feed_key'
	//					)
	//			";
	//		else
	//			$query = "UPDATE #__sdrsssyndicator_feeds SET
	//						`feed_name` = $feed_name,
	//						`feed_description` = $feed_description,
	//						`feed_type` = '$feed_type',
	//						`feed_cache` = '$feed_cache',
	//						`feed_imgUrl` = $feed_imgUrl,
	//						`feed_button` = $feed_button,
	//						`feed_renderAuthorFormat` = '$feed_renderAuthorFormat',
	//						`feed_renderHTML` = '$feed_renderHTML',
	//						`feed_renderImages` = '$feed_renderImages',
	//						`feed_categoryItem` = '$feed_categoryItem',
	//						`msg_count` = '$msg_count',
	//						`msg_orderby` = '$msg_orderby',
	//						`msg_numWords` = '$msg_numWords',
	//						`msg_FPItemsOnly` = '$msg_FPItemsOnly',
	//						`msg_sectlist` = '$msg_sectlist',
	//						`msg_excatlist` = '$msg_excatlist',
          //                                              `msg_zoocatlist` = '$msg_zoocatlist',
         //                                               `msg_ZOOapp`='$msg_zooapplist',
	//						`msg_includeCats` = '$msg_includeCats',
	//						`msg_fulltext` = '$msg_fulltext',
	//						`yandex_genre` = '$yandex_genre',
	//						`msg_exitems` = '$msg_exitems',
	//						`msg_contentPlugins` = '$msg_contentPlugins',
	//						`published` = '$published',
	//						`feed_key` = '$feed_key'
	//					WHERE id = $id
	//			";
	//	$this->_db->setQuery($query);
	//	$this->_data = $this->_db->query();
	//	if($this->_data)
	//		return true;
	//	else
	//		return false;
	//}

	//function getDefaultData()
	//{
	//	$config = $this->getInstance('config','sdrsssyndicatorModel');
	//	return $config->getData();
	//}


}