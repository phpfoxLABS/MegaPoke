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

	<form method="post" action="{url link='admincp.megapoke'}cmd_add">
		<div class="table_header">
			phpfoxLABS MegaPoke Types
		</div>

		
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>Add Poke Type:</td>
            <td><input name="poke_text" type="text" size="50" value=""  />  <input type="submit" value="Add" class="button" /></td>
          </tr>
        
        {if !count($aPokes)} {else}
        {foreach from=$aPokes item=v} 
        
            <tr>
                <td></td>
                <td>{$v.poke_text} | <a href="{url link='admincp.megapoke'}cmd_delete/id_{$v.poke_type_id}">Delete</a></td>
            </tr>
              
          
        {/foreach}
        {/if}
        </table>

	</form>
