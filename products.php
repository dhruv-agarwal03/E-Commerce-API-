<?php
    include('db.php');
    header("Content-Type:application/json");  
    if($_SERVER['REQUEST_METHOD'] === 'GET'){ 
        if(isset($_GET["id"])){
            $id=$_GET["id"];
            $query="SELECT * FROM products WHERE productId='$id' ";  
        }elseif(isset($_GET["CID"])){
            $id=$_GET["CID"];
            $query="SELECT * FROM products WHERE category='$id' ";  
        }  else{
            $query="SELECT * FROM products";
        }
        $res=$con->query($query);
        if ($res) {
            $arr=[];
            while ($row = $res->fetch_assoc()) {
                $data=array(
                    "ID"=> $row['productId'],
                    "HSNCode" => $row['HSNcode'],
                    "image" => base64_encode($row['image']),
                    "costprice" => $row['costprice'],
                    "sellingPrice" => $row['sellingPrice'],
                    "MRP" => $row['MRP'],
                    "qualityNo" => $row['qualityNo'],
                    "gst" => $row['gst'],
                    "expire" => $row['expirs'],
                    "available" => $row['Available'],
                    "category" => $row['category'],
                    "priorty" => $row['priorty']
                );
                array_push($arr,$data);
            }
            echo json_encode(["status"=>"OK","data"=>$arr]);
        }
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents("php://input"), true);
    
        if (
            isset($input['HSNCode'], $input['image'], $input['costprice'], $input['sellingPrice'], $input['MRP'],
                  $input['qualityNo'], $input['gst'], $input['expire'], $input['available'], $input['category'], $input['priorty'])
        ) {
            $HSNCode = $con->real_escape_string($input['HSNCode']);
            $image = base64_decode($input['image']);
            $costprice = floatval($input['costprice']);
            $sellingPrice = floatval($input['sellingPrice']);
            $MRP = intval($input['MRP']);
            $qualityNo = intval($input['qualityNo']);
            $gst = floatval($input['gst']);
            $expire = !empty($input['expire']) ? $con->real_escape_string($input['expire']) : NULL;
            $available = $con->real_escape_string($input['available']);
            $category = intval($input['category']);
            $priorty = intval($input['priorty']);
    
            $stmt = $con->prepare("INSERT INTO products 
                (HSNcode, image, costprice, sellingPrice, MRP, qualityNo, gst, expirs, Available, category, priorty) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
            $stmt->bind_param("ssdddiddsii", 
                $HSNCode, $image, $costprice, $sellingPrice, $MRP,
                $qualityNo, $gst, $expire, $available, $category, $priorty
            );
    
            if ($stmt->execute()) {
                echo json_encode(["status"=>"OK",
                    "message" => "Product inserted successfully.",
                    "inserted_id" => $stmt->insert_id
                ]);
            } else {
                http_response_code(500);
                echo json_encode(["status"=>"NO",
                    "error" => "Insert failed: " . $stmt->error
                ]);
            }
    
            $stmt->close();
        } else {
            http_response_code(400);
            echo json_encode(["status"=>"NO",
                "error" => "Missing required fields."
            ]);
        }
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $input = json_decode(file_get_contents("php://input"), true);
    
        if (
            isset($input['ID'], $input['HSNCode'], $input['image'], $input['costprice'], $input['sellingPrice'],
                  $input['MRP'], $input['qualityNo'], $input['gst'], $input['expire'], $input['available'],
                  $input['category'], $input['priorty'])
        ) {
            $productId = intval($input['ID']);
            $HSNCode = $con->real_escape_string($input['HSNCode']);
            $image = base64_decode($input['image']); 
            $costprice = floatval($input['costprice']);
            $sellingPrice = floatval($input['sellingPrice']);
            $MRP = intval($input['MRP']);
            $qualityNo = intval($input['qualityNo']);
            $gst = floatval($input['gst']);
            $expire = !empty($input['expire']) ? $con->real_escape_string($input['expire']) : NULL;
            $available = $con->real_escape_string($input['available']);
            $category = intval($input['category']);
            $priorty = intval($input['priorty']);
    
            $stmt = $con->prepare("UPDATE products SET 
                HSNcode = ?, image = ?, costprice = ?, sellingPrice = ?, MRP = ?, qualityNo = ?, gst = ?, expirs = ?, 
                Available = ?, category = ?, priorty = ?, updated_at = CURRENT_TIMESTAMP()
                WHERE productId = ?");
    
            $stmt->bind_param("ssdddiddsiii", 
                $HSNCode, $image, $costprice, $sellingPrice, $MRP, 
                $qualityNo, $gst, $expire, $available, $category, $priorty, $productId
            );
    
            if ($stmt->execute()) {
                echo json_encode(["status"=>"OK",
                    "message" => "Product updated successfully."
                ]);
            } else {
                http_response_code(500);
                echo json_encode(["status"=>"NO",
                    "error" => "Update failed: " . $stmt->error
                ]);
            }
    
            $stmt->close();
        } else {
            http_response_code(400);
            echo json_encode(["status"=>"NO",
                "error" => "Missing required fields for update."
            ]);
        }
    }
    
?>