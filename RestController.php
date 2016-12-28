<?php
require_once("News.php");
        
$view = "";
if(isset($_GET["view"]))
    $view = $_GET["view"];
/*
controls the RESTful services
URL mapping
*/
switch($view){

    case "all":
        // to handle REST Url /news/list/
        $news = new News();
        $news->getAllNews();
        break;
        
    case "single":
        // to handle REST Url /news/list/<id>/
        $news = new News();
        $news->getNews($_GET["id"]);
        break;
    case "insert":
        // to handle REST Url /news/insert/
        $news = new News();
        $news->insertNews();
        break;
    case "update":
        // to handle REST Url /news/update/<id>/
        $news = new News();
        $news->updateNews($_GET["id"]);
        break;
    case "delete":
        // to handle REST Url /news/delete/<id>/
        $news = new News();
        $news->deleteNews($_GET["id"]);
        break;    

    case "" :
        //404 - not found;
        break;
}
?>