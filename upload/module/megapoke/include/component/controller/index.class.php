<?php
 /**
 * 
 * @copyright		phpfoxLABS.com
 * @author  		Jacob Adams
 * @package  		MEGAPOKE
 * @version 		v1.0.0 - 2011-04-14
 *
 * This work is licensed under a Creative Commons Attribution-ShareAlike 3.0 Unported License.
 * human-readable summary: http://creativecommons.org/licenses/by-sa/3.0/
 * full legal code: http://creativecommons.org/licenses/by-sa/3.0/legalcode
 *
 */

defined('PHPFOX') or exit('NO DICE!');


#error_reporting(E_ERROR | E_WARNING | E_PARSE);

class Megapoke_Component_Controller_Index extends Phpfox_Component
{
    public function process()
    {        
	
	
		
		if ($_POST['poke_user'] and $_POST['poke_userid'] and $_POST['poke_type_id'])
		{
			
			
			# Check User Privacy Settings
			if (!Phpfox::getService('user.privacy')->hasAccess($_POST['poke_userid'], 'megapoke.who_can_poke'))
			{
				$this->url()->send($_POST['poke_user'], null, Phpfox::getPhrase('megapoke.user_turned_off_megapoke'));
				exit;
			}		
		
			
			
			if (Phpfox::getUserId() == $_POST['poke_userid'])
			{
			
				$this->url()->send($_POST['poke_user'], null, Phpfox::getPhrase('megapoke.you_can_not_send_yourself_a_megapoke'));
				exit;
			}
			
			
			
			# Flood Control: 2 mins
			$time = time() - 120;
			
			$aFlood =phpfox::getLib('phpfox.database')->select('*')
			->from(Phpfox::getT('feed'))
			->where("type_id = 'megapoke' and user_id = '".Phpfox::getUserId()."' and  time_stamp > '".$time."' ")
			->execute('getSlaveRow');	
			if (isset($aFlood['feed_id']))
			{
				
				$this->url()->send($_POST['poke_user'], null, Phpfox::getPhrase('megapoke.too_many_megapokes'));
				exit;
				
			}
				
				
				
			# Get Poke Type
			$aPoke =phpfox::getLib('phpfox.database')->select('*')
			->from(Phpfox::getT('megapoke'))
			->where("poke_type_id = '".Phpfox::getLib('phpfox.database')->escape($_POST['poke_type_id'])."' ")
			->execute('getSlaveRow');	
			
			$d = array();
			$d['content'] = $aPoke['poke_text'];
			
			
			$oUrl = Phpfox::getLib('url');
			$oParseOutput = Phpfox::getLib('parse.output');	
			
			
			#Insert into Feed
			if (Phpfox::getUserParam('megapoke.showpokesinfeed'))
			{ 
				
				
				
				$d['type_id'] = 'megapoke';
				$d['user_id'] = Phpfox::getUserId();
				$d['item_user_id'] = Phpfox::getLib('phpfox.database')->escape($_POST['poke_userid']);
				$d['time_stamp'] = time();
				
	
				phpfox::getLib('phpfox.database')->insert(Phpfox::getT('feed'), $d);
		
		
						
				if(Phpfox::isModule('modsfox'))
				{
					
						$ryansFeedStatus = Phpfox::getPhrase('megapoke.ryans_feed_status', array(
						'viewer_link' = >$oUrl->makeUrl('')."". $_POST['poke_user'],
						'viewer_full_name' => Phpfox::getLib('parse.output')->clean($_POST['poke_user_full_name']),
						'content' => $aPoke['poke_text'])
						);	
								
						$sType = 'status';
						$js_user = Phpfox::getUserId();
						$js_mood = "";
						Phpfox::getService('modsfox.status')->add($sType,$ryansFeedStatus,$js_mood,$js_user);
						
				}
							
			
				
				
				
				
			}
			
			
			# Add Notification
			if (Phpfox::isModule('notification'))
			{
				Phpfox::getService('notification.process')->add('megapoke', $aPoke['poke_type_id'], $_POST['poke_userid'], Phpfox::getUserId());
			}
			
			
			
			# Get User Info
			$aUserFrom = Phpfox::getLib('phpfox.database')->select(str_replace("u.", "", Phpfox::getUserField()))
			 ->from(Phpfox::getT('user'))
			 ->where("user_id = '". Phpfox::getUserId()."'")
			 ->execute('getSlaveRow');
			 
			 $d['user_link'] =  $oUrl->makeUrl('')."". $aUserFrom['user_name'];
			 $d['owner_full_name'] = $aUserFrom['full_name'];
			 
	
			# Send Email 
			Phpfox::getLib('mail')->to($_POST['poke_userid'])
			->subject(array('megapoke.newpoke_subject', $d))
			->message(array('megapoke.newpoke_message', $d))
			->notification('megapoke.new_poke_email')
			->send();	
			
			
			
			# Redirect User
			$this->url()->send($_POST['poke_user'], null, Phpfox::getPhrase('megapoke.poke_sent'));
		
		
			
			exit;
			
			
			
			
			
			
		}
		
		
		
		
		exit;
		

    }
	
	
	
	
	
	
}

?> 