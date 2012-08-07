<form id="zarafa" action="#" method="post">
        <fieldset class="personalblock">
			<legend><strong><?php echo $l->t('Zarafa Integration');?></strong></legend>
			<table>
				<tr>
					<td><label for="zarafa_server"><?php echo $l->t('Server');?></label></td>
					<td><input type="text" id="zarafa_server" name="zarafa_server" value="<?php echo $_['zarafa_server']; ?>">&nbsp;<em><?php echo $l->t('file:// or http(s):// socket to zarafa');?></em></td>
				</tr>
				<!-- username and password for getting the userlist -->
				<tr>
					<td><label for="zarafa_user_name"><?php echo $l->t('User');?></label></td>
					<td><input type="text" id="zarafa_user_name" name="zarafa_user_name" value="<?php echo $_['zarafa_user_name']; ?>">&nbsp;<em><?php echo $l->t('Required for getting the list of available users. Any valid user should be ok here.');?></em></td>
				</tr>
				<tr>
					<td><label for="zarafa_user_pass"><?php echo $l->t('Password');?></label></td>
					<td><input type="password" id="zarafa_user_pass" name="zarafa_user_pass" value="<?php echo $_['zarafa_user_pass']; ?>"></td>
				</tr>
				<tr>
					<td><label for="zarafa_webapp_url"><?php echo $l->t('Zarafa WebApp Url');?></label></td>
					<td><input type="text" id="zarafa_webapp_url" name="zarafa_webapp_url" value="<?php echo $_['zarafa_webapp_url']; ?>">&nbsp;<em><?php echo $l->t('The URL where your Zarafa WebApp is hosted');?></em></td>
				</tr>
			</table>
			<input type="submit" value="Save" />
        </fieldset>
</form>
