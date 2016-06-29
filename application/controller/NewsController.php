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
        }//cheks the token to prevent xss


    }
}


