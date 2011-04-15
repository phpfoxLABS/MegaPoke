<?php
 /**
 * 
 * @copyright		phpfoxLABS.com
 * @author  		Jacob Adams
 * @package  		MEGAPOKE
 * @version 		v1.0.0 - 2011-04-14
 *
 */
defined('PHPFOX') or exit('NO DICE!');


class Megapoke_Component_Controller_Admincp_Index extends Phpfox_Component
{

	public function process()
	{
		
		$rs = $this->request()->getRequests();
		
		if ($rs['cmd'] == "delete")
		{
			
			Phpfox::getLib('database')->update(Phpfox::getT('megapoke'), array('poke_active' => '1'), " poke_type_id = '".Phpfox::getLib('phpfox.database')->escape($rs['id'])."'");
			
			
		}
		
		
		if ($rs['cmd'] == "add")
		{
			
			
			$d = array();
			$d['poke_text'] = Phpfox::getLib('phpfox.database')->escape($_POST['poke_text']);
			phpfox::getLib('phpfox.database')->insert(Phpfox::getT('megapoke'), $d);
			
		}
		
		
		
		
		
		$aPokes =phpfox::getLib('phpfox.database')->select('*')
		->from(Phpfox::getT('megapoke'))
		->where("poke_active = '0' ")
		->order("poke_text ")
		->execute('getSlaveRows');	
		
		$this->template()->assign("aPokes", $aPokes);
		
		
		
		
	}
	
	
}

?>