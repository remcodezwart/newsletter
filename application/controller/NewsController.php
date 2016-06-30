<?php 

class NewsController extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();
        Auth::checkAuthentication();//secures the class so none logged users are redirect towards a loginscreen
    }
    public function index()
    {
    	$this->View->render('newsletter/index', array(
    		'news' => NewsModel::getAllNewsLetters(), 
    	));//renders the index of the NewsController 
    }
    public function setNewsLetter()
    {
        if (!Csrf::isTokenValid()) {
            LoginModel::logout();
            Redirect::home();
            exit();
        }//cheks the token to prevent cross side scripting forging
        NewsModel::AddRemoveNewsletterFromUser();

    }
    public function addLetter()
    {
        Auth::checkAdminAuthentication();//this makes sure only users with acount type 7 (admin) can acces this function
        $this->View->render('newsletter/NewLetter');
    }
    public function addLetter_action()
    {
        Auth::checkAdminAuthentication();//cheks that the user is an admin since thise is an admin only feature 
        if (!Csrf::isTokenValid()) {//token agains Cross-Site Request Forgery even if the user needs to be logged in and an admin still chek it
            LoginModel::logout();
            Redirect::home();
            exit();
        }//cheks the token to prevent cross side scripting forging
        $result = NewsModel::addNewLetter();
        if ($result) {
            redirect::to('user/index');
        } else {
            redirect::to('News/addLetter');
        }
    }
}


