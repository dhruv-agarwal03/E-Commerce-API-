<?php
    include('db.php');
    header("Content-Type:application/json");  
    if($_SERVER['REQUEST_METHOD'] === 'GET'){ 
        $res=$con->query("SELECT * FROM classification");
        if ($res) {
            $arr=[];
            while ($row = $res->fetch_assoc()) {
                $data=array(
                    "ID"=> $row['ID'],
                    "name" => $row['Name']
                );
                array_push($arr,$data);
            }
            echo (json_encode(["status"=>"OK","data"=>$arr]));
        }
    }
    if($_SERVER['REQUEST_METHOD'] === 'POST'){ 
        $data = file_get_contents("php://input");
        $data = json_decode($data, true);
        if($data){
            $name=$data["name"];
        }
        $res=$con->query("INSERT INTO `classification`( `Name`) VALUES ('$name')");
        if($res){
            echo (json_encode(["status"=>"OK","data"=>"Updated"]));
        }
        else echo (json_encode(["status"=>"NO","data"=>"Error"]));
    }
?>