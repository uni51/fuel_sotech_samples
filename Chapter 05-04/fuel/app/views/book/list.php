<table class="table-striped" style="width:100%">
<?foreach($books as $book):?>
<tr><td><?=$book->title?></td></tr>
<?endforeach;?>
</table>
<?=$pagination?>
