    <?php
        include('db.php');
        header("Content-Type:application/json");  
        if($_SERVER['REQUEST_METHOD'] === 'GET'){ 
            $query="SELECT * FROM `bill`";
            if(isset($_GET["cusId"])) {
                $tem=$_GET["cusId"];
                $query = "SELECT * FROM `bill` WHERE `cusId`='$tem'";
            }
            if(isset($_GET["billno"])) {
                $tem=$_GET["billno"];
               $query="SELECT * FROM `bill` WHERE `billno`='$tem'";
            }
            
            $res=$con->query($query);
            if ($res) {
                $arr=[];
                while ($row = $res->fetch_assoc()) {
                    $data=array(
                        "billno"=> $row['billno'],
                        "billDate"=> $row['billDate'],
                        "cusID" => $row['cusId'],
                        "totalAmount" => $row['totalAmount'],
                        "paymentMethod" => $row['paymentMethod'],
                        "paymentStatus" => $row['paymentStatus'],
                        "paymentDate" => $row['paymentDate']
                    );
                    array_push($arr,$data);
                }
                echo (json_encode(["status"=>"OK","data"=>$arr]));
            }
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST'){ 
            $row=json_decode(file_get_contents("php://input"),true);
            $billDate= $row['billDate'];
            $cusID = $row['cusId'];
            $totalAmount = $row['totalAmount'];
            $paymentMethod = $row['paymentMethod'];
            $paymentStatus = $row['paymentStatus'];
            $paymentDate = $row['paymentDate'];
            $rs=$con->query("INSERT INTO `bill`(`billDate`, `cusId`, `totalAmount`, `paymentMethod`, `paymentStatus`, `paymentDate`) VALUES ('$billDate','$cusID','$totalAmount','$paymentMethod','$paymentStatus','$paymentDate')");
            if($rs) echo(json_encode(["status"=>"OK"]));
            else    echo(json_encode(["status"=>"NO"]));
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'PUT'){ 
            $row=json_decode(file_get_contents("php://input"),true);
            $billNo=$row['billNo'];
            $cusID = $row['cusId'];
            $totalAmount = 0;
            $paymentMethod = $row['paymentMethod'];
            $paymentStatus = $row['paymentStatus'];
            $paymentDate = $row['paymentDate'];
            $res=$con->query("SELECT * FROM bill_items WHERE billno = '$billNo'");
            while($rw=$res->fetch_assoc()){
                $temp=$rw["quantity"]*$rw["price"];
                $temp+=$temp*$rw["gst"]/100;
                $totalAmount+=$temp;
            }
            $rs=$con->query("UPDATE `bill` SET `cusId` ='$cusID',`totalAmount`='$totalAmount',`paymentMethod`='$paymentMethod',`paymentStatus`='$paymentStatus',`paymentDate`='$paymentDate' WHERE `billno`='$billNo'");
            if($rs) echo(json_encode(["status"=>"OK"]));
            else    echo(json_encode(["status"=>"NO"]));
        }
    ?>