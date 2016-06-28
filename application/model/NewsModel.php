<?php

class NewsModel
{
	public static function getAllNewsLetters()
	{
		$database = DatabaseFactory::getFactory()->getConnection();

		$query = $database->prepare("SELECT users.user_name,letters.name,letters.users_id,letters.id,newsletter.id,newsletter.user_id,newsletter.news_id FROM newsletter INNER JOIN letters ON letters.id=newsletter.id INNER JOIN users ON newsletter.user_id=users.user_id");//gets all the newsletters from the database
		$query->execute();//executes the query
		$result = $query->fetchAll();//get all the resutl voor the user :)
		array_walk_recursive($result, 'Filter::XSSFilter');//filers for xss
		return $result;
	}
}