<?php

use core\app\Session;
use core\routing\Router;
use core\app\View;

/**
 * 
 * All routes accessible from the web.
 * 
 * Static Class Router have Router::get and 
 * Router::post method to add new routes to app.
 * 
 * Static Class Router read this document in order,
 * first defined routes has prority to be readed
 */
Router::get("/", "ExampleController@index")->name("main");

/**
 * 
 * Routes can be defined as a string (for call controllers) using
 * @ symbol to divided method from class controller
 * 
 * Also Routes can be defined as a anonymous functions or callable
 */
Router::get("/example", function () {
    echo "This is an example of callable route definition";

    //* Everything from controllers can be used here like: View::use function
    return View::use("welcome");
});

/**
 * Examples using logint auth and notAuth methods of responses
 */
Router::get("/login", function () {
    echo "estas en el login";
})->name("login")->auth("dashboard");

Router::get("/dashboard", function () {
    echo "estas en el dashboard";
})->name("dashboard")->notAuth("login");

Router::get("/session", function() {
    echo "creando nueva session";
    Session::createAuth([
        "name" => "Leonardo"
    ]);
    echo "<br> se ha creado tu session ve a:<br>";
    echo "<a href=\"" . Router::go("login") . "\">Login</a><br>";
    echo "<a href=\"" . Router::go("dashboard") . "\">Dashboard</a><br>";
});

Router::get("/logout", function() {
    Session::destroy();
    Router::redirect("logout");
});

/**
 * 
 * Routes can be defined with dynamic arguments using @ symbol.
 * Very useful to pass variables from url to the controller or function.
 * So basically dynamic arguments are GET variables 
 */
Router::get("/@myvar", "ExampleController@varFromGet")->name("varExample");
