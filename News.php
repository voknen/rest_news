<?php

require_once("database/DBconnect.php");
require_once("library/validator/add_edit_news.php");

Class News {
        
    public function getAllNews(){
        $databaseConnect = new DBConnect();
        $connection = $databaseConnect->connect();

        $query = "SELECT id, title, text, date FROM news";
        $stmt = $connection->prepare($query);
        $stmt->execute();
        
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        if (!empty($result)) {
            http_response_code(200);
            $json_string = json_encode($result);
            echo $json_string;
        } else {
            http_response_code(400);
            $json_string = json_encode(array('error' => 'No news found!'));
            echo $json_string;
        }

        header('Content-type: application/json');
    }
    
    public function getNews($id){
        $databaseConnect = new DBConnect();
        $connection = $databaseConnect->connect();

        $query = "SELECT id, title, text, date FROM news WHERE id = :id";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        if (!empty($result)) {
            http_response_code(200);
            $json_string = json_encode($result);
            echo $json_string;
        } else {
            http_response_code(400);
            $json_string = json_encode(array('error' => 'No news found!'));
            echo $json_string;
        }

        header('Content-type: application/json');
    }  

    public function insertNews(){
        $databaseConnect = new DBConnect();
        $connection = $databaseConnect->connect();

        $validator = new AddEditNews();
        if ($validator->isValid($_POST)) { 
            $stmt = $connection->prepare("INSERT INTO news(title, text, date) VALUES(:title, :text, :date);");
            $stmt->bindParam(':title', $validator->sanitizeText($_POST['title']), PDO::PARAM_STR);
            $stmt->bindParam(':text', $validator->sanitizeText($_POST['text']), PDO::PARAM_STR);
            $stmt->bindParam(':date', $validator->sanitizeText($_POST['date']), PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount()) {
                http_response_code(200);
                $json_string = json_encode(array('success' => 'inserted'));
                echo $json_string;
            } 
        } else {
            http_response_code(400);
            $json_string = json_encode($validator->getErrors());
            echo $json_string;
        }
        header('Content-type: application/json');
    }   

    public function updateNews($id){
        $databaseConnect = new DBConnect();
        $connection = $databaseConnect->connect();

        $validator = new AddEditNews();
        if ($validator->isValid($_POST)) { 
            $stmt = $connection->prepare("UPDATE news SET title = :title, text = :text, date = :date WHERE id = :id;");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':title', $validator->sanitizeText($_POST['title']), PDO::PARAM_STR);
            $stmt->bindParam(':text', $validator->sanitizeText($_POST['text']), PDO::PARAM_STR);
            $stmt->bindParam(':date', $validator->sanitizeText($_POST['date']), PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount()) {
                http_response_code(200);
                $json_string = json_encode(array('success' => 'updated'));
                echo $json_string;
            } else {
                http_response_code(400);
                $json_string = json_encode(array('error' => 'same data or no news with the given id'));
                echo $json_string;
            }
        } else {
            http_response_code(400);
            $json_string = json_encode($validator->getErrors());
            echo $json_string;
        }
        header('Content-type: application/json');
    }   

    public function deleteNews($id){
        $databaseConnect = new DBConnect();
        $connection = $databaseConnect->connect();

        $query = "DELETE FROM news WHERE id = :id";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount()) {
            http_response_code(200);
            $json_string = json_encode(array($id => 'deleted'));
            echo $json_string;
        } else {
            http_response_code(400);
            $json_string = json_encode(array('error' => 'No news found!'));
            echo $json_string;
        }

        header('Content-type: application/json');
    }   
}
?>