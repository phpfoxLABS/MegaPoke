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


class Megapoke_Component_Block_profilepoke extends Phpfox_Component
{
	
	public function process()
	{
		$aUser = $this->getParam('aUser');
		
	
		
		# Check User Privacy Settings
		if (!Phpfox::getService('user.privacy')->hasAccess($aUser['user_id'], 'megapoke.who_can_poke'))
		{
			return false;
		}		
		
		
		
		# Check for User Group Permission
		if (!Phpfox::getUserParam('megapoke.canmegapoke')){ return false; }	 
		
		# User can't Poke themself
		if ($aUser['user_id'] == Phpfox::getUserId()){ return false; } 
		
		# User must be logged in to Poke
		if (!Phpfox::getUserId()){	return false; } 
		
	
	
	
		#GET POKES
		$aPokes =phpfox::getLib('phpfox.database')->select('*')
		->from(Phpfox::getT('megapoke'))
		->where("poke_active = '0' ")
		->order('poke_text')
		->execute('getSlaveRows');	
	
	
		$this->template()->assign(array(
				'sHeader' => Phpfox::getPhrase('megapoke.profilepoketitle'),
				'aPokes' => $aPokes,
				'sSendText' => Phpfox::getPhrase('megapoke.poke_send', array('user_name' => $aUser['full_name'])),
				'pokeFormUrl' => Phpfox::getLib('url')->makeUrl('megapoke')
			)
		);
		
		
			return 'block';	
 
 
 
	}
	
	
}


?>