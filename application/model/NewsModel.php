<?php

class NewsModel
{
	public static function getAllNewsLetters()
	{
		$database = DatabaseFactory::getFactory()->getConnection();

		$query = $database->prepare("SELECT users.user_name,letters.name,letters.users_id,letters.id,newsletter.id,newsletter.user_id,newsletter.news_id FROM newsletter INNER JOIN letters ON newsletter.id=letters.id INNER JOIN users ON newsletter.user_id=users.user_id WHERE newsletter.active=:active");//gets all the newsletters from the database
		$query->execute(array(':active' => "1"));//executes the query
		$result = $query->fetchAll();//get all the results for the user :)
		array_walk_recursive($result, 'Filter::XSSFilter');//filers for xss
		return $result;
	}
	public static function getAllNewsLettersOfCurrentUser()
	{
		$database = DatabaseFactory::getFactory()->getConnection();//gets the database connection 

		$userId = session::get('user_id');//gets the user id from the session

		$query = $database->prepare("SELECT * FROM letters WHERE users_id=:user_id AND active=:active");//gets all the newsletters from the database 
		$query->execute(array(':active' => "1",':user_id' => $userId));//executes the query gives back the result of the current user
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
		$query->execute(array(':id' => $userid,':letterId' => $id));//executes the query

		$database = null; //were done set the connection to the database to null
		return true;
	}
	protected static function updateLetter($active,$id,$userId)
	{
		$database = DatabaseFactory::getFactory()->getConnection(); //gets the database connection 

		$query = $database->prepare("UPDATE usersubscribedtonewsletter SET active=:active WHERE user_id=:userId AND letters_id=:letterId");//
		$query->execute(array(':active' => $active ,':userId' => $userId,':letterId' =>$id));//executes the query

		$database = null; //were done set the connection to the database to null
		return true;

	}
	public static function addNewLetter()
	{
		if (!isset($_POST['name'])) { //do we have a name? if not exit
			Session::add('feedback_negative', Text::get('FAILED_CREATION_EMPTY_NAME'));
			return false;
			exit();
		} 
		$value = filter_var($_POST['name'], FILTER_SANITIZE_STRING);///filters the user input to prevent html from entering the database
		
		if ($value == "" || $value === null) { //cheks if after filtering if there is somthing in value of not stop the code
			Session::add('feedback_negative', Text::get('FAILED_CREATION_EMPTY_NAME'));
			return false;
			exit();
		}
		$user_id = Session::get('user_id'); //gets the user id from the session 

		$database = DatabaseFactory::getFactory()->getConnection();//gets the database connection 
		//executes an insert query 
		$query = $database->prepare("INSERT INTO letters (name,users_id) 
									VALUES (:name,:user_id) ");//
		$query->execute(array(':name' => $value ,':user_id' => $user_id));

		$count = $query->rowCount(); //counts the amout of rows that were affacted 

		if ($count != 1) { //this means the query failed if so stop the code
			Session::add('feedback_negative', Text::get('FAILED_CREATION'));//gives the user an error message
			return false;
			exit(); //return false and exit against headless browsers
		}

		$last_id = $database->lastInsertId(); //gets thhe last insert id of the previuse query

		$query = $database->prepare("INSERT INTO newsletter (user_id,news_id) 
									VALUES (:user_id,:news_id) ");//
		$query->execute(array(':user_id' => $user_id ,':news_id' => $last_id));
		//inserts on the
		$count = $query->rowCount();
		if ($count != 1) {
			self::removeLastId($last_id,$database);
			Session::add('feedback_negative', Text::get('FAILED_CREATION'));
			return false;
			exit();
		}
		$database = null; //we are done close the connection 
		return true;//everything went good so return true now
	}
	protected static function removeLastId($id,$database)
	{
		$query = $database->prepare("UPDATE letters SET active=:active
									WHERE id=:last_id ");//
		$query->execute(array(':active' => "0",':last_id' => $id));
		//makes it so that should the inser on newsletter fail then were gonna insert a dummy row to make sure should that happen the aplication does not break
		$query = $database->prepare("INSERT INTO newsletter (user_id,news_id) 
									VALUES (:user_id,:news_id) ");//
		$query->execute(array(':user_id' => "0" ,':news_id' => "0"));

		return false;
	}
	public static function EditOrDeleteNewsletter()
	{
		$database = DatabaseFactory::getFactory()->getConnection(); //gets the database connection 


		if (!isset($_POST['id'])) {//cheks if we have a id if not give the user an error message
			Session::add('feedback_negative', Text::get('FEEDBACK_UNKNOWN_ERROR'));//gives the user a feedback message
			return false;//return false
			exit();//stop the code
		}
		if (!isset($_POST['name'])) {//cheks if we have a name if not give the user an error message
			Session::add('feedback_negative', Text::get('NO_NAME_OF_NEWSLETTER'));//gives the user a feedback message
			return false;//return false
			exit();//stop the code
		}
		$id = $_POST['id'];
		$value = filter_var($_POST['name'], FILTER_SANITIZE_STRING);///filters the user input to prevent html from entering the database

		if ($value === null || $value == "") {
			Session::add('feedback_negative', Text::get('NO_NAME_OF_NEWSLETTER'));//cheks if value is not null or ""
			return false;//return false
			exit();//stop the code
		}

		$user_id = Session::get('user_id'); //gets the user id from the session 

		$query = $database->prepare("SELECT * FROM letters WHERE id=:id AND users_id=:user_id AND active=:active");//
		$query->execute(array(':active' => "1",':user_id' => $user_id,'id' => $id));
		$result = $query->fetch();

		if ($result === false) {
			Session::add('feedback_negative', Text::get('NOT_OWNER_OF_THIS_NEWSLETTER'));//gives the user a negatve feedback message
			return false;//return false
			exit();//exit the code
		}
		if (isset($_POST['delete'])) {//cheks if delete is cheked on in this case it will not exisist unless chek so we use that as to determine if the chekbox is cheked :)
			$query = $database->prepare("UPDATE letters SET active=:active WHERE users_id=:user_id AND id=:id");//
			$query->execute(array(':active' => "0",':user_id' => $user_id,'id' => $id));//soft deletes the letter
			$newsId = $result->id;

			$query = $database->prepare("UPDATE newsletter SET active=:active WHERE  news_id=:news_id");//
			$query->execute(array(':active' => "0",':news_id' => $newsId));//soft deletes the newsletter

			$query = $database->prepare("UPDATE usersubscribedtonewsletter SET active=:active WHERE  letters_id=:letter_id");//if a user is subscirbed to this newsletter soft delete it
			$query->execute(array(':active' => "0",':letter_id' => $newsId));

			Session::add('feedback_positive', Text::get('SUCCES_DELETION_OF_NEWSLETTER'));//returns a succes message
		} else {
			$query = $database->prepare("UPDATE letters SET name=:name WHERE users_id=:user_id AND id=:id");//
			$query->execute(array(':name' => $value,':user_id' => $user_id,'id' => $id));

			Session::add('feedback_positive', Text::get('SUCCES_EDIT_OF_NEWSLETTER'));//returns a succes message
		}
		return true;
	}
}