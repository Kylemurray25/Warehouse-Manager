<?php 
	session_start(); 

	if (!isset($_SESSION['username'])) {
		header('location: login.php');
	}

	if (isset($_GET['logout'])) {
		session_destroy();
		unset($_SESSION['username']);
		header("location: login.php");
	}

    if ($_SESSION['message'] != "warehouse") {
        session_destroy();
        header("location: login.php");
        exit();
    }

include('server.php');

// connect to mysql database

$connect = mysqli_connect('$hostname', '$username', '$password', '$db_name');

// mysql select query
$query = "SELECT * FROM `Item`";
$upInvQuery = "SELECT * FROM `PurchaseOrder`,`Item` WHERE Item.ItemNo = PurchaseOrder.ItemNo";
$inventoryQuery = "SELECT * FROM `Inventory`,`Item` WHERE Item.ItemNo = Inventory.ItemNo";
$shipmentsQuery = "SELECT * FROM `pendingOrder`, `Item` WHERE Item.ItemNo = pendingOrder.ItemNo";



$shipmentsResult = mysqli_query($connect,$shipmentsQuery);
$inventoryResult = mysqli_query($connect, $inventoryQuery);
$result1 = mysqli_query($connect, $query);
$upInvResult = mysqli_query($connect, $upInvQuery);

?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="mystyle.css">
<h1><center>Welcome Warehouse Employee!  <a href="index.php?logout='1'" style="color: red;">logout</a></center></h1>
<style> 

.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

/* The Close Button */
.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
</style>

</head>

<body>
<center>

<button class ="btn sm ghost" id="Btnupt">Purchase Orders</button>



<div id="updateModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
	<table style="width:100%">
	<tr>
		<th>Name</th>
		<th>Quantity</th> 
		<th>Description</th>
		<th>Price</th>
		

	</tr>
          
	<tr>
      
		<?php while($row1 = mysqli_fetch_array($upInvResult)):;?>
				<tr>
					<td><center><?php echo $row1[5];?></center></td>
					<td><center><?php echo $row1[2];?></center></td>
					<td><center><?php echo $row1[6];?></center></td>
					<td><center><?php echo $row1[7];?></center></td>
					
			  </tr>
        <?php endwhile;?>
				</tr>
	</table>
	
  <form method="post" action="warehouseEmployee.php" id="updateInventoryProcess">
  <button type="submit" class="btn" name="process_placeorder_request">Confirm</button>
  <button type="submit" class="btn" name="decline_placeorder_request">Decline</button>
  </form>
  <center> <button class="btn" id="backUpdate">Back</button></center>
  </div>    
</div>


<button class ="btn sm ghost" id="Btnshp">Shipments</button>



<div id="shipmentModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
	<table style="width:100%">
	<tr>
		<th>Name</th>
		<th>Quantity</th> 
		<th>Description</th>
		
		

	</tr>
          
	<tr>
      
		<?php while($row1 = mysqli_fetch_array($shipmentsResult)):;?>
				<tr>
					<td><center><?php echo $row1[4];?></center></td>
					<td><center><?php echo $row1[2];?></center></td>
					<td><center><?php echo $row1[5];?></center></td>
					
			  </tr>
        <?php endwhile;?>
				</tr>
	</table>
	
  <form method="post" action="warehouseEmployee.php" id="shipmentProcess">
  <button type="submit" class="btn" name="process_shipment_request">Confirm</button>
  <button type="submit" class="btn" name="decline_shipment_request">Decline</button>
  </form>
  <center> <button class="btn" id="backShipment">Back</button></center>
  </div>    
</div>
    

<button class ="btn sm ghost" id="Btnplc">Place Order</button>

<div id="placeOrderModal" class="modal">

	<div class="modal-content">

		<p><form method="post" action="warehouseEmployee.php" id="orderRequestForm"><br>
        
		<br>Item Name:<br>
        
        <select name="item" id="item" form="orderRequestForm">

            <?php while($row1 = mysqli_fetch_array($result1)):;?>

            <option value="<?php echo $row1[0];?>"><?php echo $row1[1];?></option>

            <?php endwhile;?>

        </select>
		<!--<input type="text" name="item">--><br>
		Quantity:<br>
		<input type="text" name="quantity"><br>
		<button type="submit" class="btn" name="place_order_request">Confirm</button>
		</form>
		<center> <button class="btn" id="backPlace">Back</button></center>
		</p>
	</div>
</div>


<button class ="btn sm ghost" id="Btninv">View Inventory</button>

<div id="inventoryModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
      
      <table style="width:100%">
  <tr>
    <th>Name</th>
    <th>Quantity</th> 
    <th>Description</th>
    <th>Cost</th>
    <th>Sell Price</th>

  </tr>
          
  <tr>
      
       <?php while($row1 = mysqli_fetch_array($inventoryResult)):;?>
            <tr>
                <td><center><?php echo $row1[3];?></center></td>
                <td><center><?php echo $row1[1];?></center></td>
                <td><center><?php echo $row1[4];?></center></td>
                <td><center><?php echo $row1[5];?></center></td>
                <td><center><?php echo $row1[6];?></center></td>
          </tr>
            <?php endwhile;?>
            </tr>
</table>

	<center> <button class="btn" id="backInventory">Back</button></center>
	</p>
  </div>

</div>


</center>
<script>
var backUpt = document.getElementById("backUpdate");
var modalUpt = document.getElementById('updateModal');
var btnUpt = document.getElementById("Btnupt");

btnUpt.onclick = function() {
    modalUpt.style.display = "block";
}

window.onclick = function(event) {
    if (event.target != modalUpt) {
        modalUpt.style.display = "none";
    }
}

backUpt.onclick = function() {
	modalUpt.style.display = "none";
	
}

var backPlc = document.getElementById("backPlace");
var modalPlc = document.getElementById('placeOrderModal');
var btnPlc = document.getElementById("Btnplc");

btnPlc.onclick = function() {
    modalPlc.style.display = "block";
}

window.onclick = function(event) {
    if (event.target == modalPlc) {
        modalPlc.style.display = "none";
    }
}

backPlc.onclick = function() {
	modalPlc.style.display = "none";
	
}

var backView = document.getElementById("backInventory");
var modalView = document.getElementById('inventoryModal');
var btnView = document.getElementById("Btninv");

btnView.onclick = function() {
    modalView.style.display = "block";
}

window.onclick = function(event) {
    if (event.target == modalView) {
        modalView.style.display = "none";
    }
}

backView.onclick = function() {
	modalView.style.display = "none";
	
}

var backShipment = document.getElementById("backShipment");
var modalShipment = document.getElementById('shipmentModal');
var btnShp = document.getElementById("Btnshp");

btnShp.onclick = function() {
    modalShipment.style.display = "block";
}

window.onclick = function(event) {
    if (event.target == modalShipment) {
        modalShipment.style.display = "none";
    }
}

backShipment.onclick = function() {
	modalShipment.style.display = "none";
	
}


</script>
</body>
</html>
