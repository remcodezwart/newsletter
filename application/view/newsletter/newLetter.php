<?php $this->renderFeedbackMessages(); ?>

<p class="flow-text">voeg hier een nieuwe nieuwsbrief toe voor de gebruikers om in te schrijven</p>
<form method="post" action="<?= config::get("URL"); ?>News/addLetter_action">
	<input type="text" name="name" required="true">
	<input type="hidden" name="csrf_token" value="<?= Csrf::makeToken(); ?>" />
	<button type="submit" value="verzenden">verzenden</button>
</form>
<?php 
	foreach ($this->currentLetters as $singleLetter) {
?>
<form method="post" action="<?= config::get("URL"); ?>News/EditNewsletter_action">
	<input type="text" name="name" value="<?=$singleLetter->name ?>" required="true">
	<input type="hidden" name="csrf_token" value="<?= Csrf::makeToken(); ?>" />
	<input type="checkbox" name="delete" id="<?=$singleLetter->id?>">
	<label for="<?=$singleLetter->id?>" >niews brief verwijderen</label> 
	<input type="hidden" name="id" value="<?=$singleLetter->id?>">
	<button type="submit" value="verzenden">verzenden</button>
</form>
<?php 
	}
?>