<?php
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
Router::get("/", "ExampleController@index");

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
 * 
 * Routes can be defined with dynamic arguments using @ symbol.
 * Very useful to pass variables from url to the controller or function.
 * So basically dynamic arguments are GET variables 
 */
Router::get("/@myvar", "ExampleController@varFromGet");
