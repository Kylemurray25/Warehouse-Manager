<?php 
	session_start();
echo "";
	// variable declaration
	$username = "";
	$email    = "";
	$errors = array(); 
	$_SESSION['success'] = "";

	// connect to database
$connect = mysqli_connect('$hostname', '$username', '$password', '$db_name');

    function debug_to_console( $data ) {
    $output = $data;
    if ( is_array( $output ) )
        $output = implode( ',', $output);

        echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
    }

	// LOGIN USER
	if (isset($_POST['login_user'])) {
		$username = mysqli_real_escape_string($db, $_POST['username']);
		$password = mysqli_real_escape_string($db, $_POST['password']);

		if (empty($username)) {
			array_push($errors, "Username is required");
		}
		if (empty($password)) {
			array_push($errors, "Password is required");
		}

		if (count($errors) == 0) {
			$query = "SELECT position FROM myUsers WHERE username='$username' AND password='$password'";
			$results = mysqli_query($db, $query);

			if (mysqli_num_rows($results) == 1) {
				$_SESSION['username'] = $username;
				$_SESSION['success'] = "You are now logged in";
     
                 while ($obj = $results->fetch_object()) {
                     $_SESSION['message'] = $obj->position;
                     
                     if ($obj->position == 'manager')
                     {
                        header('location: manager.php');
                     }
                       else if ($obj->position == 'warehouse')
                     {
                        header('location: warehouseEmployee.php');
                     }
                       else 
                     {
                        header('location: salesEmployee.php');
                     }
                }
                
			}
            
            else {
				array_push($errors, "Wrong username/password combination");
			}
		}
	}
	
	
	
	
	
	
	
	
		if (isset($_POST['process_request'])) {

				$query = "SELECT * FROM `InventoryRequestForm`";
				$result = mysqli_query($db, $query);
				while($row1 = mysqli_fetch_array($result))
				{
					$item = $row1[1];
					$quantity = $row1[2];
					
					$selQuery = "SELECT * FROM `pendingOrder`";
					$selQueryResult = mysqli_query($db, $selQuery);
					$orderRequestNo=0;
					while($row1 = mysqli_fetch_array($selQueryResult))
					{
					$orderRequestNo = $row1[0];
					}
					$orderRequestNo++;
                    $upQuery = "INSERT INTO `pendingOrder` (orderNo,ItemNo,quantity)
					VALUES ('$orderRequestNo','$item','$quantity')";
					$upQueryResult = mysqli_query($db,$upQuery);
					
					$deleteQuery = "DELETE FROM `InventoryRequestForm`";
					$deleteResult = mysqli_query($db,$deleteQuery);
				}

    	}

	
		if (isset($_POST['decline_request'])) {

					$deleteQuery = "DELETE FROM `InventoryRequestForm`";
					$deleteResult = mysqli_query($db,$deleteQuery);

    	}

	
    if (isset($_POST['place_order'])) {

        $item = $_POST['item'];
		$quantity = mysqli_real_escape_string($db, $_POST['quantity']);


		if (empty($item)) {
			array_push($errors, "Item is required!");
		}
		if (empty($quantity)) {
			array_push($errors, "Quantity is required!");
		}

		if (count($errors) == 0) {

            $selQuery = "SELECT * FROM `pendingOrder`";
			$selQueryResult = mysqli_query($db, $selQuery);
			$orderRequestNo=0;
			while($row1 = mysqli_fetch_array($selQueryResult))
			{
				$orderRequestNo = $row1[0];
			}
			$orderRequestNo++;
            $newQuery = "INSERT INTO `pendingOrder` (orderNo,ItemNo,quantity)
			VALUES ('$orderRequestNo','$item','$quantity')";
            $newQueryResult = mysqli_query($db, $newQuery);


            }
        }
	
		if (isset($_POST['place_order_request'])) {

        $item = $_POST['item'];
		$quantity = mysqli_real_escape_string($db, $_POST['quantity']);

            
		if (empty($item)) {
			array_push($errors, "Item is required!");
		}
		if (empty($quantity)) {
			array_push($errors, "Quantity is required!");
		}

		if (count($errors) == 0) {

            $selQuery = "SELECT * FROM `InventoryRequestForm`";
			$selQueryResult = mysqli_query($db, $selQuery);
			$orderRequestNo=0;
			while($row1 = mysqli_fetch_array($selQueryResult))
			{
				$orderRequestNo = $row1[0];
			}
			$orderRequestNo++;
            			
            $insQuery = "INSERT INTO `InventoryRequestForm` (reqeustNo,ItemNo,quantity) 
			VALUES ('$orderRequestNo','$item','$quantity')";
            $insQueryResult = mysqli_query($db, $insQuery);


        }
    }


	if (isset($_POST['place_purchase_order'])) {

        $item = $_POST['item'];
		$quantity = mysqli_real_escape_string($db, $_POST['quantity']);

            
		if (empty($item)) {
			array_push($errors, "Item is required!");
		}
		if (empty($quantity)) {
			array_push($errors, "Quantity is required!");
		}

		if (count($errors) == 0) {

            $selQuery = "SELECT * FROM `PurchaseOrder`";
			$selQueryResult = mysqli_query($db, $selQuery);
			$orderRequestNo;
			while($row1 = mysqli_fetch_array($selQueryResult))
			{
				$orderRequestNo = $row1[0];
			}
			$orderRequestNo++;
            			
            $insQuery = "INSERT INTO `PurchaseOrder` (orderNo,ItemNo,quantity) 
			VALUES ('$orderRequestNo','$item','$quantity')";
            $insQueryResult = mysqli_query($db, $insQuery);


        }
    }

		if (isset($_POST['process_placeorder_request'])) {

				$query = "SELECT * FROM `PurchaseOrder`";
				$result = mysqli_query($db, $query);
				while($row1 = mysqli_fetch_array($result))
				{
					$item = $row1[1];
					$quantity = $row1[2];

                    $upQuery = "UPDATE `Inventory` SET quantity = quantity-'$quantity' WHERE ItemNo='$item'";
					$upQueryResult = mysqli_query($db,$upQuery);
					
					$soldQuery = "UPDATE `soldItems` SET quantity = quantity+'$quantity' WHERE ItemNo='$item'";
					$soldResult = mysqli_query($db,$soldQuery);
					
					$deleteQuery = "DELETE FROM `PurchaseOrder`";
					$deleteResult = mysqli_query($db,$deleteQuery);
				}

    	}

	
		if (isset($_POST['decline_placeorder_request'])) {

					$deleteQuery = "DELETE FROM `PurchaseOrder`";
					$deleteResult = mysqli_query($db,$deleteQuery);

    	}
		
		if (isset($_POST['process_shipment_request']))  {
			
				$query = "SELECT * FROM `pendingOrder`";
				$result = mysqli_query($db, $query);
				while($row1 = mysqli_fetch_array($result))
				{
					$item = $row1[1];
					$quantity = $row1[2];

                    $upQuery = "UPDATE `Inventory` SET quantity = quantity+'$quantity' WHERE ItemNo='$item'";
					$upQueryResult = mysqli_query($db,$upQuery);
					
					$deleteQuery = "DELETE FROM `pendingOrder`";
					$deleteResult = mysqli_query($db,$deleteQuery);
				}
			
		}
		
		if (isset($_POST['decline_shipment_request'])) {

					$deleteQuery = "DELETE FROM `pendingOrder`";
					$deleteResult = mysqli_query($db,$deleteQuery);

    	}
		
		if (isset($_POST['new_item'])) {

        $name = mysqli_real_escape_string($db, $_POST['name']);
		$desc = mysqli_real_escape_string($db, $_POST['description']);
		$cost = mysqli_real_escape_string($db, $_POST['cost']);
		$price = mysqli_real_escape_string($db, $_POST['price']);
        
            
		if (empty($name)) {
			array_push($errors, "Item is required!");
		}
		if (empty($desc)) {
			array_push($errors, "Description is required!");
		}
		if (empty($cost)) {
			array_push($errors, "Cost is required!");
		}
		if (empty($price)) {
			array_push($errors, "Price is required!");
		}


		if (count($errors) == 0) {

            $selQuery = "SELECT * FROM `Item`";
			$selQueryResult = mysqli_query($db, $selQuery);
			$itemNo=0;
            
			while($row1 = mysqli_fetch_array($selQueryResult))
			{

				$itemNo = $row1[0];                
			}
			$itemNo++;

            $zeroInsert=0;
            
            $insQuery = "INSERT INTO `Item` (ItemNo,name,`desc`,cost,sellPrice) 
			VALUES ('$itemNo','$name', '$desc', '$cost','$price')";
            $insQueryResult = mysqli_query($db, $insQuery);

            $insQuery2 = "INSERT INTO `Inventory` (ItemNo,quantity) 
			VALUES ('$itemNo','$zeroInsert')";
            $insQueryResult2 = mysqli_query($db, $insQuery2);
            

            $insQuery3 = "INSERT INTO `soldItems` (ItemNo,quantity) 
			VALUES ('$itemNo','$zeroInsert')";
            $insQueryResult3 = mysqli_query($db, $insQuery3);
            
            
            
			}

		}
		
		
		
		
?>