<?php
    include('db.php');
    header("Content-Type:application/json"); 
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['billno'])) {
            $billno = $_GET['billno'];
            $res = $con->query("SELECT * FROM `bill_items` WHERE `billno` = '$billno'");
        } else {
            $res = $con->query("SELECT * FROM `bill_items`");
        }
    
        if ($res) {
            $arr = [];
            while ($row = $res->fetch_assoc()) {
                $arr[] = [
                    "id" => $row['id'],
                    "billno" => $row['billno'],
                    "productId" => $row['productId'],
                    "quantity" => $row['quantity'],
                    "price" => $row['price'],
                    "gst" => $row['gst']
                ];
            }
            echo json_encode(["status" => "OK", "data" => $arr]);
        }
    }
    
    if($_SERVER['REQUEST_METHOD'] === 'POST'){ 
        $row=json_decode(file_get_contents("php://input"),true);
         //	id	billno	productId	quantity	price	gst	
        $billno= $row['billno'];
        $productId = $row['productId'];
        $quantity = $row['quantity'];
        $price = $row['price'];
        $gst = $row['gst'];
        $rs=$con->query("INSERT INTO `bill_items`(`billno`,`productId`, `quantity`, `price`, `gst`) VALUES ('$billno','$productId','$quantity','$price','$gst')");
        if($rs) echo(json_encode(["status"=>"OK"]));
        else    echo(json_encode(["status"=>"NO"]));
    }
    
    if($_SERVER['REQUEST_METHOD'] === 'PUT'){ 
        $row=json_decode(file_get_contents("php://input"),true);
        $id=$row['id'];
        $quantity = $row['quantity'];
        $price = $row['price'];
        $gst = $row['gst'];
        $rs=$con->query("UPDATE `bill_items` SET `quantity`='$quantity',`price`='$price',`gst`='$gst' WHERE `id`='$id'");
        if($rs) echo(json_encode(["status"=>"OK"]));
        else    echo(json_encode(["status"=>"NO"]));
    }
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $row = json_decode(file_get_contents("php://input"), true);
        $id = $row['id'];
        $rs = $con->query("DELETE FROM `bill_items` WHERE `id` = '$id'");
        if ($rs) echo json_encode(["status" => "OK"]);
        else     echo json_encode(["status" => "NO", "error" => $con->error]);
    }
    
?>