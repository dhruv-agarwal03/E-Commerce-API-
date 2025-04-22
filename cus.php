<?php
    include('db.php');
    header("Content-Type:application/json");   

    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        if (isset($_GET["number"])) {
            $number = $_GET["number"];
            $res = $con->query("SELECT * FROM customers WHERE number='$number'");
        } else {
            $res = $con->query("SELECT * FROM customers");
        }

        if ($res) {
            $arr=[];
            while ($row = $res->fetch_assoc()) {
                $data = array(
                    "ID" => $row['id'],
                    "First_Name" => $row['fname'],
                    "Last_Name" => $row['lname'],
                    "number" => $row['number'],
                    "Email" => $row['email'],
                    "password" => $row['password'],
                    "Address" => $row['address'],
                    "City" => $row['city'],
                    "State" => $row['state'],
                    "Pincode" => $row['pincode']
                );
                array_push($arr, $data);
            }
            if(count($arr)!=0){
                echo json_encode(["status" => "OK", "data" => $arr]);
            }else   echo json_encode(["status" => "Empty", "data" => $arr]);
           

        } else {
            echo json_encode(["status" => "NO", "Error" => "Error fetching data: " . $con->error]);
        }
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        add();
    }

    function add(){
        global $con;
        $data = file_get_contents("php://input");
        $data = json_decode($data, true);

        // Escape inputs to prevent SQL injection
        $firstName = $data['fname'];
        $LastName = $data['lname'];
        $Email = $data['email'];
        $number = $data['number'];
        $password = $data['password'];
        $Address = $data['address'];
        $City = $data['city'];
        $State = $data['state'];
        $Pincode = $data['pincode'];

        if ($firstName == "" || $number == "" || $Address == "" || $Pincode == "") {
            echo json_encode(["status" => "NO", 'Error' => "Enter All fields"]);
            return;
        }

        $sql = "INSERT INTO `customers` (`fname`, `lname`, `number`, `password`, `email`, `address`, `city`, `state`, `pincode`) 
                VALUES ('$firstName', '$LastName', '$number', '$password', '$Email', '$Address', '$City', '$State', '$Pincode')";

        if ($con->query($sql)) {
            echo json_encode(["status" => "OK", 'data' => "Inserted Successfully"]);
        } else {
            echo json_encode(["status" => "NO", 'error' => "Error: " . $con->error]);
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        updateCustomer($con);
    }

    function updateCustomer($con) {
        $data = file_get_contents("php://input");
        $data = json_decode($data, true);
        $firstName = $data['fname'];
        $lastName = $data['lname'];
        $number = $data['number'];
        $email = $data['email'];
        $password = $data['password'];
        $address = $data['address'];
        $city = $data['city'];
        $state = $data['state'];
        $pincode = $data['pincode'];

        if ($number == '') {
            echo json_encode(['error' => 'Phone number is required for update']);
            return;
        }

        $sql = "UPDATE customers SET fname = '$firstName', lname = '$lastName', address = '$address', password = '$password', 
                city = '$city', state = '$state', email = '$email' ,  pincode = '$pincode' WHERE number = '$number'";

        if ($con->query($sql)) {
            echo json_encode(["status" => "OK", 'message' => 'Customer updated successfully']);
        } else {
            echo json_encode(["status" => "NO", 'error' => 'Update failed: ' . $con->error]);
        }
    }

?>
