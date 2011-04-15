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

?>
{if !count($aPokes)}

{else}
<form action="{$pokeFormUrl}" name="pokeme" method="post">
<input type="hidden" name="poke_user" value="{$aUser.user_name}" />
<input type="hidden" name="poke_userid" value="{$aUser.user_id}" />
<table border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td>
    	{$sSendText}</td>
    <td>
    <select onchange="this.form.submit();" name="poke_type_id">
    <option>----</option>
    {foreach from=$aPokes item=v} 
   		 <option value="{$v.poke_type_id}">{$v.poke_text}</option>
    {/foreach}
    </select>
    
    
    </td>
  </tr>
</table>
</form>
{/if}