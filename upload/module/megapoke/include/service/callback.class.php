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

class Megapoke_Service_Callback extends Phpfox_Service 
{
	
	public function __construct()
	{	
		$this->_sTable = Phpfox::getT('megapoke');
	}
	
		
	public function getProfileSettings()
	{
		return array(
			'megapoke.who_can_poke' => array(
				'phrase' => Phpfox::getPhrase('megapoke.settings_who_can_poke'),
				'default' => '0'				
			)
		);
	}
		
		
		
	public function getNotificationSettings()
	{
		return array('megapoke.new_poke_email' => array(
				'phrase' => Phpfox::getPhrase('megapoke.new_poke_notification_email'),
				'default' => 1
			)
		);		
	}
	
	
	
		
	public function getNotificationFeed($aRow)
	{	
	
	
		$oUrl = Phpfox::getLib('url');
		$oParseOutput = Phpfox::getLib('parse.output');	
	
		$aPoke =phpfox::getLib('phpfox.database')->select('*')
		->from(Phpfox::getT('megapoke'))
		->where("poke_type_id = '".$aRow['item_id']."' ")
		->execute('getSlaveRow');
		
		$aUserFrom = Phpfox::getLib('phpfox.database')->select(str_replace("u.", "", Phpfox::getUserField()))
		 ->from(Phpfox::getT('user'))
		 ->where("user_id = '". Phpfox::getUserId()."'")
		 ->execute('getSlaveRow');
		 
		 
		
		$aRow['link'] = $oUrl->makeUrl('feed.user', array('id' => $aRow['user_id']));
		$aRow['message'] = Phpfox::getPhrase('megapoke.notification', array(
				'user_link' => $oUrl->makeUrl('feed.user', array('id' => $aRow['user_id'])),
				'owner_full_name' => Phpfox::getLib('parse.output')->clean($aRow['full_name']),
				'content' => $aPoke['poke_text']
			)
		);	
	
	
	
		
		return $aRow;
	
	}	
		
		
	public function getNewsFeed($aRow)
	{	
	
	
		$oUrl = Phpfox::getLib('url');
		$oParseOutput = Phpfox::getLib('parse.output');	
	
	
		$aRow['text'] = Phpfox::getPhrase('megapoke.ijustsentapoke', array(
				'user_link' => $oUrl->makeUrl('feed.user', array('id' => $aRow['user_id'])),
				'owner_full_name' => Phpfox::getLib('parse.output')->clean($aRow['owner_full_name']),
				'viewer_link' => $oUrl->makeUrl('feed.user', array('id' => $aRow['viewer_user_id'])),
				'viewer_full_name' => Phpfox::getLib('parse.output')->clean($aRow['viewer_full_name']),
				'content' => $aRow['content']
			)
		);	
	
		$aRow['enable_like'] = true;
		
		return $aRow;
	
	}
	
	
}

?>