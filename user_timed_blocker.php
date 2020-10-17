<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.user_timed_blocker
 *
 * @copyright
 * @license     GNU/Public
 */

defined('_JEXEC') or die;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\String\PunycodeHelper;
use Joomla\Utilities\ArrayHelper;
/**
 * User_timed_blocker plugin class.
 *
 * @since  2.5
 * @link https://docs.joomla.org/Plugin/Events/System
 */
class PlgSystemUser_timed_blocker extends JPlugin{
	protected $autoloadLanguage = true;
	 function onContentPrepareForm($form, $data)
	{
		  
  $app = JFactory::getApplication();
if (JFactory::getApplication()->isClient('administrator')){
	$option = $app->input->get('option');
	$theadmincomponent = "com_users";
	JForm::addFormPath(JPATH_PLUGINS . '/system/user_timed_blocker/');

		if ($option == $theadmincomponent)
        {
		
          $form->loadFile('renewuser', false);
        }
}
  
	}
	  /**
	 * After Render.
	 *
	 * @return  void
	 *
	 * @since   3.4
	 */
	
	public function onAfterRender(){
		
		
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		//registerDate
		//activation
		//params
		//groups
		//{"activate":0,"admin_style":"","admin_language":"","language":"","editor":"","timezone":""}

		$date1 = new DateTime(date('Y-m-d H:i:s'));
		$date2 = new DateTime($user->registerDate);
		$userparams = json_decode($user->params);
		if (($userparams->nova_data != '0000-00-00 00:00:00') and ($userparams->nova_data){
		$date3 = new DateTime($novaData);
		$interval = $date1->diff($date3);
		$novoPrazo = $userparams->novo_prazo;
		}
		else{
			$interval = $date1->diff($date2);
		}
		
		// echo "Diferença " . $interval->y . " anos, " . $interval->m." meses, ".$interval->d." dias "; 
		
		// // shows the total amount of days (not divided into years, months and days like above)
		// echo "Diferença " . (($interval->y*12) + $interval->m) . " meses ";
		// var_dump($interval);
		$meses = (($interval->y*12) + $interval->m);
		$dias = $interval->days;
		$alunos = $this->params->get('alunos');
		$alunosGroups = $this->params->get('grupos_do_aluno');
		$assignedGroup = $this->params->get('assigned_group');
		$ok = 'false';
		$finalDate = $this->params->get('final_date');
		if ($novoPrazo){
		$finalDate = $novoPrazo;
		}
		if ($meses >= $finalDate and (!empty(array_intersect($alunosGroups, $user->groups)))){
			$ok = 'true';
		}
		if ($ok == 'true'){
			// Get a db connection.
			$db = JFactory::getDbo();
			// Create a new query object.
			$query = $db->getQuery(true);
			
			$query = ("
			UPDATE `#__users` SET `block`='1' WHERE  `id`=".$user->id.";
			");	
		
			
			$db->setQuery($query);

			try
			{
				$db->execute();
			}
			catch (RuntimeException $e)
			{
				$e->getMessage();
			}
			
	
			// Get a db connection.
			$db2 = JFactory::getDbo();
			// Create a new query object.
			$query2 = $db2->getQuery(true);
			
			$query2 = ("
			DELETE FROM `bd2zw_user_usergroup_map` WHERE `user_id`=".$user->id.";
			");	
		
			
			$db2->setQuery($query2);

			try
			{
				$db2->execute();
			}
			catch (RuntimeException $e2)
			{
				$e2->getMessage();
			}


			// Get a db connection.
			$db3 = JFactory::getDbo();
			// Create a new query object.
			$query3 = $db3->getQuery(true);
			
			$query3 = ("

			INSERT INTO `bd2zw_user_usergroup_map` (`user_id`, `group_id`) VALUES (".$user->id.", ".$assignedGroup.");
			");	
		
			
			$db3->setQuery($query3);

			try
			{
				$db3->execute();
			}
			catch (RuntimeException $e3)
			{
				$e3	->getMessage();
			}
			
		}

	}
	
	public function onUserAfterLogin(){
		
	
	}
  /**
	 * After initialise.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public function onAfterInitialise(){
	
  }
  /**
	 * After route.
	 *
	 * @return  void
	 *
	 * @since   3.4
	 */
	public function onAfterRoute(){

  }
  /**
	 * After Dispatch.
	 *
	 * @return  void
	 *
	 * @since   3.4
	 */
	public function onAfterDispatch(){

  }
  /**
	 * Before Render.
	 *
	 * @return  void
	 *
	 * @since   3.4
	 */
	public function onBeforeRender(){

  }

  /**
	 * Before Compile Head.
	 *
	 * @return  void
	 *
	 * @since   3.4
	 */
	public function onBeforeCompileHead(){

  }
  /**
	 * Search.
	 *
	 * @return  void
	 *
	 * @since   3.4
	 */
	/**
	 * Search
	 * @param  string $searchword   The target search string
	 * @param  string $searchphrase A string matching option (exact|any|all).
	 * @param  string $ordering     A string ordering option (newest|oldest|popular|alpha|category)
	 * @param  array $areas        An array if restricted to areas, null if search all.
	 * @return array              Array of stdClass objects with members as described above.
	 */
	public function onSearch($searchword,$searchphrase,$ordering,$areas){
    return [];
  }

  /**
	 * Determine areas searchable by this plugin.
	 *
	 * @return  array  An array of search areas.
	 *
	 * @since   1.6
	 */
	public function onContentSearchAreas(){
		return [];
	}

}
