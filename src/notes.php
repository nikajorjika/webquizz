<?php
require 'db.php';
    if(isset($_POST['note'])){
        $current_date = date('Y/m/d');
        $note_array = array(
            'note' => $_POST['note'],
            'date'=> $current_date
        );
        if(db_insert('webquizz', $note_array)){
            http_response_code(201);
            header('content-type: application/json');
            header('content: {"status": {"message": "Note was create successfully..."}} ');
        }
    }
    $content = db_query('select * from webquizz');
    $json_content = json_encode($content, JSON_PRETTY_PRINT);
    print($json_content);

?>