<div class="actions">
	以下の内容でお間違いなければ、「送信」をクリックしてください。<br>
	メールが送信されます。
</div>
<?php echo Form::open('contact/send'); ?>
<div class="clearfix">
	<?php echo Form::label('お名前※ ', 'name'); ?>　
	<div class="field">
		<?php echo $name; ?>
	</div>
</div>
<div>
	<?php echo Form::label('Emailアドレス※ ', 'email'); ?>
	<div class="field">
		<?php echo $email; ?>
	</div>
</div>
<div>
	<?php echo Form::label('お電話番号', 'tel'); ?>
	<div class="field">
		<?php echo str_replace('_', '-', $tel); ?>
	</div>
</div>
<div>
	<?php echo Form::label('お問い合わせ内容※ ', 'body'); ?>
	<div class="field">
		<?php echo nl2br($body); ?>
	</div>
</div>
<div class="actions">
	<?php echo Form::submit('submit', '送信', array('class' => 'btn primary')); ?>&nbsp;&nbsp;
	<?php echo Form::submit('back', '戻る'); ?>
	<?php echo Form::hidden(Config::get('security.csrf_token_key'), Security::fetch_token()); ?>
</div>
<?php echo Form::close(); ?>