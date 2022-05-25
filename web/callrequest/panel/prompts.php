<?php

define("INTERNAL_ACCESS",true);

session_start();
if(!isset($_SESSION['webphone_user_logged_in'])){
    header('Location: login.php');
    exit(0);
}
$error = '';
$success = '';

include_once 'inc/database.php';

if(isset($conn)){
    if(isset($_POST['new-record'])){
        $prompt = isset($_POST['new-prompt']) ? $_POST['new-prompt'] : '';
        $text = isset($_POST['new-text']) ? $_POST['new-text'] : '';
        $description = isset($_POST['new-description']) ? $_POST['new-description'] : '';
        if(mb_strlen($prompt) > 0){
         try {
             $insert_sql = "INSERT INTO $db_prompts_table(prompt,text,description) VALUE(:prompt,:text,:description)";
             $insert_statement = $conn->prepare($insert_sql);
             $result = $insert_statement->execute([':prompt' => rawurldecode($prompt), ':text' => rawurldecode($text), ':description' => rawurldecode($description)]);
             $success .= 'با موفقیت افزوده شد!';
         } catch (PDOException $e) {
             if($e->getCode() === "23000")
                 $error .= 'به دلیل تکراری بودن رشته، افزوده نشد! ';
             else
                 $error .= 'به دلیل خطای ' . $e->getMessage() . ' افزوده نشد!';
         }
        }
    }
    elseif (isset($_POST['edit-record'])){
        $failed = [];
        $data = $_POST;
        unset($data['edit-record']);
        foreach ($data as $key => $datum){
            $update_sql = "UPDATE $db_prompts_table SET text = :text WHERE prompt = :prompt";
            $update_statement = $conn->prepare($update_sql);
            $text = rawurldecode($datum);
            $prompt = rawurldecode($key);
            $update_statement->bindParam(':text',$text);
            $update_statement->bindParam(':prompt',$prompt);
            if(!$update_statement->execute()){
                $failed[] = $key;
            }

        }
        if(empty($failed)){
            $success .= 'ویرایش به درستی انجام گرفت!';
        }else{
            $error .= 'مقادیر فیلدهای ' . implode('،',$failed) . 'ذخیره نشدند!';
        }
    }
    $sql = "SELECT * FROM {$db_prompts_table}";
    $statement = $conn->query($sql);
    $result = $statement->fetchAll(PDO::FETCH_OBJ);
}
include_once 'inc/template/prompts-template.php';

