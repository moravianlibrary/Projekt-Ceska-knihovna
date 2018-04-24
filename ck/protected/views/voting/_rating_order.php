<tr>
	<td><?echo $index+1?></td>
	<td><?echo $data->publisher->name?></td>
	<td><?echo $data->author?></td>
	<td><?echo $data->title?></td>
	<td><?echo $data->points?></td>
	<td><?echo $data->issue_year?></td>
	<td><?echo $data->council_comment?></td>
	<td align="right"><?echo currf($data->retail_price)?></td>
	<td align="right"><?echo currf($data->project_price)?></td>
	<td class="voting"><?echo $data->votes_yes?></td>
	<td class="voting"><?echo $data->votes_no?></td>
	<td class="voting"><?echo $data->votes_withheld?></td>
</tr>
