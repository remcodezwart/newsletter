<?php

class NewsModel
{
	public static function getAllNewsLetters()
	{
		$database = DatabaseFactory::getFactory()->getConnection();

		$query = $database->prepare("SELECT users.user_name,letters.name,letters.users_id,letters.id,newsletter.id,newsletter.user_id,newsletter.news_id FROM newsletter INNER JOIN letters ON letters.id=newsletter.id INNER JOIN users ON newsletter.user_id=users.user_id WHERE letters.active=:active");//gets all the newsletters from the database
		$query->execute(array(':active' => "1"));//executes the query
		$result = $query->fetchAll();//get all the results for the user :)
		array_walk_recursive($result, 'Filter::XSSFilter');//filers for xss
		return $result;
	}
	public static function AddRemoveNewsletterFromUser()
	{

		if (!isset($_POST['id']) || !isset($_POST['value']) ) {
			return false;
			exit();
		}
		$database = DatabaseFactory::getFactory()->getConnection();

		$userId = session::get('user_id');
		$id = $_POST['id'];

		if ($id === null || $id == "") {//cheks if we have an id if not return false
			return false;
			exit();
		}
		if ($userId == "" || $userId === null) { //cheks if we have a user id if not return false
			return false;
			exit();
		}
		if ($_POST['value'] === null || $_POST['value'] == "") {
			return false;
			exit();
		}
		$value = $_POST['value'];

		$query = $database->prepare("SELECT * FROM usersubscribedtonewsletter WHERE user_id=:id AND letters_id=:letterId ");//
		$query->execute(array(':id' => $userId,':letterId' =>$id));//executes the query
		$result = $query->fetchAll();//get all the results for the user :)
		if (!isset($result[0]->active)) {
			self::makeLetter($userId,$id);
		} else {
			if ($value == "on") {
				self::updateLetter("1",$id,$userId);
			} else {
				self::updateLetter("0",$id,$userId);
			}
		}

	}
	protected static function makeLetter($userid,$id)
	{
		$database = DatabaseFactory::getFactory()->getConnection();

		$query = $database->prepare("INSERT INTO usersubscribedtonewsletter (user_id,letters_id) 										VALUES (:id,:letterId)");//
		$query->execute(array(':id' => $userid,':letterId' =>$id));//executes the query

		$databse = null;
		return true;
	}
	protected static function updateLetter($active,$id,$userId)
	{
		$database = DatabaseFactory::getFactory()->getConnection();

		$query = $database->prepare("UPDATE usersubscribedtonewsletter SET active=:active WHERE user_id=:userId AND letters_id=:letterId");//
		$query->execute(array(':active' => $active ,':userId' => $userId,':letterId' =>$id));//executes the query

		$database = null;
		return true;

	}
	public static function addNewLetter()
	{
		if (!isset($_POST['name'])) {
			return false;
			exit();
		} else {
			$value = filter_var($_POST['name'], FILTER_SANITIZE_STRING);///filters the user input to prevent html from entering the database
		}
		if (!isset($value || $value == "" || $value === null)) {
			return false;
			exit();
		}
		$user_id = Session::get('user_id');













		return true;
	}
}