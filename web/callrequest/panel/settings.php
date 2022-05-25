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
        $parameter = isset($_POST['new-parameter']) ? $_POST['new-parameter'] : '';
        $value = isset($_POST['new-value']) ? $_POST['new-value'] : '';
        $description = isset($_POST['new-description']) ? $_POST['new-description'] : '';
        if(mb_strlen($parameter) > 0){
         try {
             $insert_sql = "INSERT INTO $db_settings_table(parameter,value,description) VALUE(:parameter,:value,:description)";
             $insert_statement = $conn->prepare($insert_sql);
             $result = $insert_statement->execute([':parameter' => rawurldecode($parameter), ':value' => rawurldecode($value), ':description' => rawurldecode($description)]);
             $success .= 'با موفقیت افزوده شد!';
         } catch (PDOException $e) {
             if($e->getCode() === "23000")
                 $error .= 'به دلیل تکراری بودن پارامتر، افزوده نشد! ';
             else
                 $error .= 'به دلیل خطای ' . $e->getMessage() . ' افزوده نشد!';
         }
        }
    }
    elseif (isset($_POST['edit-record'])){
        $failed = [];
        $data = $_POST;
        unset($data['edit-record']);
        if(isset($data['Direction'])){
            $data['Direction'] = $data['Direction'] === "FirstCallCustomer" ? "FirstCallCustomer" : "FirstCallPbx";
        }
        foreach ($data as $key => $datum){
            $update_sql = "UPDATE $db_settings_table SET value = :value WHERE parameter = :parameter";
            $update_statement = $conn->prepare($update_sql);
            $value = rawurldecode($datum);
            $parameter = rawurldecode($key);
            $update_statement->bindParam(':value',$value);
            $update_statement->bindParam(':parameter',$parameter);
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
    $sql = "SELECT * FROM {$db_settings_table}";
    $statement = $conn->query($sql);
    $result = $statement->fetchAll(PDO::FETCH_OBJ);
}
include_once 'inc/template/settings-template.php';

