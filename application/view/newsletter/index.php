<table class="highlight responsive-table bordered centered">
	<thead>
		<tr>
			<th>naam van het nieuwsblad</th>
            <th>eigenaar</th>
            <th>inschrijven/afmelden</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			foreach ($this->news as $newsValue) {
		?>
		<tr>
			<td><?=$newsValue->name ?></td>
			<td><?=$newsValue->user_name ?></td><!--echo out all possible maginzine from the database in a table -->
			<td>
				<input type="checkbox" id="<?=$newsValue->id ?>" >
				<label onclick="" for="<?=$newsValue->id ?>"></label><!--if the users chekes the chekbox it will be noted in the database :) -->
			</td>
		</tr>
		<?php
			}
		?>
	</tbody>
</table>
<div id="status" style="height:25px;width:25px;display:inline-block;background-color:blue;">

</div>
<script type="text/javascript">
function submit()
{
	$.ajax({
    type: "POST",
    url:"<?php echo Config::get('URL'); ?>",
    data:{id: "bla",csrf_token: '<?= Csrf::makeToken();?>'},//sending a token to prevent xss 
    success: function() 
    {
		document.getElementById("status").css.backgroundColor = "green";//if succes the color becomes green
    }
    fail:function()
    {
    	document.getElementById("status").css.backgroundColor = "red";// if it fails :( the color becomes red
    }
}
</script>