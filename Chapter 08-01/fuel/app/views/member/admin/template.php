<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php echo $title; ?></title>
		<?php echo Asset::css('bootstrap.css'); ?>
		<style>
			body { margin: 50px; }
		</style>
	</head>
	<body>
		<div class="topbar">
			<div class="fill">
				<div class="container">
					<h3><a href="">FuelPHP入門ブログ</a></h3>
					<ul class="nav secondary-nav">
						<li class="menu">
							<?php if (Auth::check()): ?>
								<?php echo Html::anchor('member/logout', 'ログアウト'); ?>
							<?php endif; ?>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="span16">
					<h1><?php echo $title; ?></h1>
						<p><a class="btn primary" href="<?php echo Uri::create('member');?>">会員トップページへ</a></p>
					<hr>
				</div>
				<div class="span16">
<?php echo $content; ?>
				</div>
			</div>
			<footer>
				<p>
					<a href="http://fuelphp.com">FuelPHP</a> is released under
					the MIT
					license.<br>
					<small>Version: <?php echo e(Fuel::VERSION); ?></small>
				</p>
			</footer>
		</div>
	</body>
</html>