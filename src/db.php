<?php
/**
 * Created by PhpStorm.
 * Author: Nika Jorjoliani
 * Contributor: Giorgi Gagoshidze
 * Date: 5/5/14
 * Time: 11:51 AM
 */
define("DB_HOST", 'localhost:3306');
define("DB_USERNAME", 'root');
define("DB_NAME",'webquizz');
define("DB_PASSWORD", '');

function pdoObject(){
    $pdo = new PDO('mysql:host='.DB_HOST.';'.'dbname='.DB_NAME, DB_USERNAME.',');
    $pdo->query("set names utf8");
    return $pdo;
}


//we will use update query to delete bd records
function db_update($table, $data, $conditions) {
    $pdo =pdoObject();
    $data_keys = array_keys($data);
    $data_values = array_values($data);
    $data_array = array();
    for($i=0; $i<sizeof($data_keys); $i++){
        $data_array[] = "$data_keys[$i]=?";
    }
    $conditions_keys = array_keys($conditions);
    $conditions_values = array_values($conditions);
    $conditions_array = array();
    for($i=0; $i<sizeof($conditions_keys); $i++){
        $conditions_array[] = "$conditions_keys[$i]=?";
        }
    $query = "UPDATE $table SET ".implode(', ',$data_array)." WHERE ".implode(' and ', $conditions_array);
    $concatenated_array=array_merge($data_values,$conditions_values);
    $stmt = $pdo->prepare($query);
    $stmt->execute($concatenated_array);
    }

function db_insert($table, $data) {
    $pdo =pdoObject();
    $data_keys = array_keys($data);
    $data_values = array_values($data);
    $question_marks = array_fill(0, count($data), '?');
    $query ="INSERT INTO $table (".implode(',', $data_keys).") VALUES (".implode(',', $question_marks).')';
    $stmt = $pdo->prepare($query);
    if($stmt->execute($data_values)){
        return true;
    }
    else{
        return false;
    }
}
function db_select($table, $conditions) {
    $pdo =pdoObject();
    $conditions_keys = array_keys($conditions);
    $conditions_values = array_values($conditions);
    $conditions_array = array();
    for($i=0; $i<sizeof($conditions_keys); $i++){
        $conditions_array[] = "$conditions_keys[$i]=?";
    }

    if($conditions=''){
        $query = "select * from $table";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
    }else{
        $query = "SELECT * FROM $table WHERE ".implode(' and ',$conditions_array);
        $stmt = $pdo->prepare($query);
        $stmt->execute($conditions_values);
    }

    $result = $stmt->fetchAll();
    return $result;


}
function db_query($query){
    $pdo = pdoObject();
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $result;
}