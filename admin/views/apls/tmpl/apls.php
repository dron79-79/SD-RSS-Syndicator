<?php defined('_JEXEC') or die('Restricted access');
/**
* @Copyright Copyright (C) 2010 sdaprel.ru
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

?>


<form action="index.php" method="post" name="adminForm" id="adminForm" class="adminForm">
    <table border="0" cellpadding="3" cellspacing="0" align="left">
        <tr>
            <td>Название апликации:</td>
            <td><?php echo($this->apls->name); ?></td>
        </tr>

        <tr>
            <td>выбор источника вводного текста:</td>
            <td><input type="text" size="50" maxlength="150" name="name_a" value="<?php //echo($this->name); ?>" /></td>
        </tr>
         <tr>
            <td>выбор источника полного текста:</td>
            <td><input type="text" size="50" maxlength="150" name="name_a" value="<?php //echo($this->name); ?>" /></td>
        </tr>
        <tr>
            <td>выбор источника изображений:</td>
            <td><input type="text" size="50" maxlength="150" name="name_a" value="<?php //echo($this->name); ?>" /></td>
        </tr>
    </table>
    <input type="hidden" name="id_a" value="<?php echo $this->id;?>" />
    <input type="hidden" name="option" value="com_sdrsssyndicator" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="controller" value="apls" />
    <?php echo JHTML::_( 'form.token' ); ?>
</form>

