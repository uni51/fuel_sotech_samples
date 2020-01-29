<?php echo Form::open(); ?>
<fieldset>
	<div class="clearfix">
		<?php echo Form::label('ユーザ名', 'username'); ?>　
		<?php echo Form::input('username'); ?>
	</div>
	<div class="clearfix">
		<?php echo Form::label('パスワード', 'password'); ?>　
		<?php echo Form::password('password'); ?>
	</div>
	<div class="actions">
		<?php echo Form::submit('submit', 'ログイン'); ?>
	</div>
</fieldset>
<?php echo Form::close(); ?>