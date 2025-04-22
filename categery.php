<?php
    include('db.php');
    header("Content-Type:application/json");  
    if($_SERVER['REQUEST_METHOD'] === 'GET'){ 
        $res=$con->query("SELECT * FROM category");
        if ($res) {
            $arr=[];
            while ($row = $res->fetch_assoc()) {
                $data=array(
                    "ID"=> $row['catId'],
                    "name"=> $row['name'],
                    "image" => $row['img'],
                    "ClassID" => $row['CID']
                );
                array_push($arr,$data);
            }
            echo (json_encode(["status"=>"OK","data"=>$arr]));
        }
    }
    if($_SERVER['REQUEST_METHOD'] === 'POST'){ 
        $input=json_decode(file_get_contents("php://input"),true);
        if($input["img"]=="")   $input["img"]=null;
        $name=$input["name"];   
        $image=$input["img"];   
        $CID=$input["CID"];   
        $rs=$con->query("INSERT INTO `category`( `img`, `CID`, `name`) VALUES ('$image','$CID','$name')");
        if($rs) echo(json_encode(["status"=>"OK"]));
        else    echo(json_encode(["status"=>"NO"]));
    }
?>