<!DOCTYPE html>
<html>
	<meta charset="utf-8">
	<head>
		<title><?php echo $title ?></title>
		<style>table,td{border:solid 1px black;}table{border-collapse:collapse;}
		</style>
	</head>
	<body>
		<?php echo $title ?>
		<table>
			<?php foreach ($users as $user): ?>
				<tr>
					<td><?php echo $user->name ?></td>
					<td><?php echo $user->email ?></td>
					<td><?php echo $sex_string($user->sex) ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</body>
</html>