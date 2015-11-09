<?php

/**
* @Copyright Copyright (C) 2010 sdaprel.ru
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

jimport('joomla.application.component.controller');

class sdrsssyndicatorControllerApls extends JController
{

    protected $_link = null;
	public function __construct()
	{
		parent::__construct();
		$this->_link = 'index.php?option=com_sdrsssyndicator&task=apls';
		$this->registerTask( 'add'  , 	'edit' );
		$this->registerTask( 'apply'  , 	'save' );

	}

	public function cancel()
	{
		//$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( $this->_link );
	}

	public function edit()
	{

		//JRequest::setVar( 'view', 'apls' );
		//JRequest::setVar( 'layout', 'apls'  );
		//JRequest::setVar('hidemainmenu', 1);
                $document = JFactory::getDocument();
                //echo 'fggggg';
		$viewType = $document->getType();
		$view = $this->getView('apls',$viewType,'sdrsssyndicatorView');

		$model = $this->getModel('apls');
                 //print_r($view);die();
		if(!JError::isError($model))
		{
			$view->setModel($model,true);
		}
		$view->setLayout('apls');

		$view->display();

		//parent::display();
	}

	public function save()
	{
		$model = $this->getModel('apls');

		if ($model->save($post)) {
			$msg = JText::_( 'Feed Saved!' );
		} else {
			$msg = JText::_( 'Error Saving feed' );
		}
		if($this->_task == 'apply')
		{
			$id = JRequest::getVar( 'id', '', 'post', 'string' );
			//die(JRequest::getVar('id' ));
			$this->_link = "index.php?option=com_sdrsssyndicator&task=edit&cid[]=$id&controller=apls";
		}
		$this->setRedirect( $this->_link, $msg );
	}

	public function publish()
	{

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to publish' ) );
		}

		$model = $this->getModel('apls');
		if(!$model->publish($cid, 1)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect($this->_link);
	}

	public function unpublish()
	{

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to unpublish' ) );
		}

		$model = $this->getModel('apls');
		if(!$model->publish($cid, 0)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect($this->_link);
	}


	//public function apls()
	//{


	//}


}
?>
