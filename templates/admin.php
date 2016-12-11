<?php
script('kopano', 'admin');
?>

<form id="user-kopano" class="section" action="#" method="post">
	<h2 class="inlineblock"><?php echo $l->t('Kopano Integration');?></h2>
	<div id="user-kopano-save-indicator" class="msg success inlineblock" style="display: none;">Saved</div>

        <fieldset class="personalblock">
			<table>
				<tr>
					<td><label for="kopano_server"><?php echo $l->t('Server');?></label></td>
					<td><input type="text" id="kopano_server" name="kopano_server" value="<?php echo $_['kopano_server']; ?>">&nbsp;<em><?php echo $l->t('file:// or http(s):// socket to kopano');?></em></td>
				</tr>
				<!-- username and password for getting the userlist -->
				<tr>
					<td><label for="kopano_user_name"><?php echo $l->t('User');?></label></td>
					<td><input type="text" id="kopano_user_name" name="kopano_user_name" value="<?php echo $_['kopano_user_name']; ?>">&nbsp;<em><?php echo $l->t('Required for getting the list of available users. Any valid user should be ok here.');?></em></td>
				</tr>
				<tr>
					<td><label for="kopano_user_pass"><?php echo $l->t('Password');?></label></td>
					<td><input type="password" id="kopano_user_pass" name="kopano_user_pass" value="<?php echo $_['kopano_user_pass']; ?>"></td>
				</tr>
				<tr>
					<td><label for="kopano_webapp_url"><?php echo $l->t('Kopano WebApp Url');?></label></td>
					<td><input type="text" id="kopano_webapp_url" name="kopano_webapp_url" value="<?php echo $_['kopano_webapp_url']; ?>">&nbsp;<em><?php echo $l->t('The URL where your Kopano WebApp is hosted');?></em></td>
				</tr>
			</table>
        </fieldset>
</form>
