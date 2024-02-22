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
        $user = new User;
        print_r($user->or(["name", "pedro"])->get());


        // $user = new User;
        // $user->find(1);
        // print_r($user->getAttributes());
        // $user->setAttribute("name", "andrea");
        // $user->setAttribute("pass", "abc123");
        // echo "<br>";
        // print_r($user->getAttributes());
        // echo "<br>";
        // if($user->update()) echo "actualizao";
        
        // return View::use("example");
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

    public function modelExample() {
        // $userData = [
        //     "id" => 5,
        //     "name" => "equis",
        //     "pass" => "abc123",
        //     "email" => "equis@mail.com"
        // ];
        // $newUser = new User($userData);

        // $newUser->save();
        // $newUser->setAttribute("pass", "cba321");
        // $newUser->update();
        // $newUser->remove();
    }
}
