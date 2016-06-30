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
				<label onclick="submit(<?=$newsValue->id ?>)" for="<?=$newsValue->id ?>"></label><!--if the users chekes the chekbox it will be noted in the database :) -->
			</td>
		</tr>
		<?php
			}
		?>
	</tbody>
</table>
<div id="status" style="padding-bottom:180px;padding-right:75px;opacity: 0;left:85%;top:-45px;position:relative;height:25px;width:25px;display:inline-block;background-color:blue;">

</div>
<script type="text/javascript">
function submit(id)
{
	var cheked = document.getElementById(id).checked; //gets the value of the chekbox
	if (!cheked == true) {//since the value of the chekbox already got changed before javascript cheked if it was chekend were cheking for when is it is not true 
		var value = "on";
	} else {
		var value = "off";
	}
	document.getElementById("status").style.backgroundColor  = "blue";
	document.getElementById("status").style.opacity  = "1";
	$.ajax({
    type: "POST",
    url:"<?php echo Config::get('URL'); ?>News/setNewsLetter",
    data:{value:value,id: id,csrf_token: '<?= Csrf::makeToken();?>'},//sending a token to prevent CSRF  
    success: function() 
    {
		document.getElementById("status").style.backgroundColor = "green";//if succes the color becomes green
		document.getElementById("status").innerHTML = "u bent succesvol voor deze nieuwbrief aangemeld /afgemeld";//tells the user the request has succeeded
		setTimeout(function() {
			document.getElementById("status").innerHTML = "";//resetes the div to be hidden and empty
			document.getElementById("status").style.opacity = "0";
		}, 30000);
    },
    fail:function()
    {
    	document.getElementById("status").style.backgroundColor  = "red";// if it fails :( the color becomes red
    	document.getElementById("status").innerHTML  = "er is iets mis gegaan helaas :(" ;//resetes the div to be hidden and empty
    }
	});
}
</script>