<?php
// use class to be implemented in this controller
use app\models\User;
use core\app\View;

/**
 * Class ExampleController
 * 
 * This is an example to use as standard with MixiPHP
 */
class ExampleController {
    
    /**
     * index
     * 
     * Name default Controller entry as index is no needed,
     * but for semantic reason its highly recommended
     */
    public function index() {
        //$user = new User([
        //    "name" => "pedro",
        //    "email" => "pedro@mail.com"
        //]);
        //echo $user->name . "<br>";
        //echo $user->email . "<br>";
        //$user->pass = "abc123";
        //print_r($user->getAttributes());
        //echo "<br>" . $user->pass;
        //$user->role = 1;
        //$user->save();
        return View::use("welcome");
    }

    /**
     * varFromGet
     * 
     * For pass variables from get you need to see routes guide.
     * * Entry parameter for function in controllers use the same name as defined
     * * in route file
     */
    public function varFromGet($myvar) {
        echo $myvar;
    }

    /**
     * varFromPost
     * 
     * Post variables can be obtained with magic variable $_POST
     */
    public function varFromPost() {
        echo $_POST["myvar"];
    }
}
