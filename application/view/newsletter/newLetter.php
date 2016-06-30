<p class="flow-text">voeg hier een nieuwe nieuwsbrief toe voor de gebruikers om in te schrijven</p>
<form method="post" action="<?= config::get("URL"); ?>News/addLetter_action">
	<input type="text" name="name" required="true">
	<input type="hidden" name="csrf_token" value="<?= Csrf::makeToken(); ?>" />
	<button type="submit" value="verzenden">verzenden</button>
</form>