<tr>
	<td><?echo $data['username']?></td>
	<td><?echo $data['publisher']?></td>
	<td><?echo $data['author']?></td>
	<td><?echo $data['title']?></td>
	<td>
	<?
	switch ($data['points']) {
		case '-' :
			echo 'chybí';
			break;
		case null :
			echo 'nehlasoval';
			break;
		case 'N' :
			echo 'nehlasoval';
			break;
		case '0' :
			echo '0 bodů';
			break;
		default :
			echo $data['points'] . ' bodů';
	}
	?></td>
</tr>
