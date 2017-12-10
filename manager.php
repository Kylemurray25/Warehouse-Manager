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

    if ($_SESSION['message'] != "manager") {
        session_destroy();
        header("location: login.php");
        exit();
    }


include('server.php');

// connect to mysql database

$connect = mysqli_connect('$hostname', '$username', '$password', '$db_name');

// mysql select query
$query = "SELECT * FROM `Item`";
$inventoryQuery = "SELECT * FROM `Inventory`,`Item` WHERE Item.ItemNo = Inventory.ItemNo";
$orderRequestQuery = "SELECT * FROM `InventoryRequestForm`, `Item` WHERE Item.ItemNo = InventoryRequestForm.ItemNo";
$reportQuery = "SELECT * FROM `soldItems`,`Item`, `Inventory` WHERE Item.ItemNo = Inventory.ItemNo AND Item.ItemNo = soldItems.ItemNo";

// for method 1

$result1 = mysqli_query($connect, $query);
$inventoryResult = mysqli_query($connect, $inventoryQuery);
$requestResult = mysqli_query($connect, $orderRequestQuery);
$reportResult = mysqli_query($connect, $reportQuery);


?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="mystyle.css">
<h1> <center> Welcome Manager!          <a href="index.php?logout='1'" style="color: red;">logout</a>
    
    <?php
// Echo session variables that were set on previous page

//echo "Welcome " . $_SESSION["username"] . "You" ".<br>";
?>
    </center> </h1>
    
    
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

<button class ="btn sm ghost" id="Btnreq">Order Requests</button>

<div id="orderRequestModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
	<table style="width:100%">
	<tr>
		<th>Name</th>
		<th>Quantity</th> 
		<th>Description</th>
		<th>Cost</th>
		

	</tr>
          
	<tr>
      
		<?php while($row1 = mysqli_fetch_array($requestResult)):;?>
				<tr>
					<td><center><?php echo $row1[5];?></center></td>
					<td><center><?php echo $row1[2];?></center></td>
					<td><center><?php echo $row1[6];?></center></td>
					<td><center><?php echo $row1[7];?></center></td>
					
			  </tr>
        <?php endwhile;?>
				</tr>
	</table>
	
  <form method="post" action="manager.php" id="processRequest">
  <button type="submit" class="btn" name="process_request">Confirm</button>
  <button type="submit" class="btn" name="decline_request">Decline</button>
  </form>
  <center> <button class="btn" id="backRequest">Back</button></center>
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


<button class ="btn sm ghost" id="Btnplc">Place Order</button>

<div id="placeOrderModal" class="modal">

	<div class="modal-content">

		<p><form method="post" action="manager.php" id="placeOrderForm"><br>
        
      
        
		<br>Item Name:<br>
        
        <select name="item" id="item" form="placeOrderForm">

            <?php while($row1 = mysqli_fetch_array($result1)):;?>

            <option value="<?php echo $row1[0];?>"><?php echo $row1[1];?></option>

            <?php endwhile;?>

        </select>
		<!--<input type="text" name="item">--><br>
		Quantity:<br>
		<input type="text" name="quantity"><br>
		<button type="submit" class="btn" name="place_order">Confirm</button>
		</form>
		<center> <button class="btn" id="backPlace">Back</button></center>
		</p>
	</div>
</div>


<button class ="btn sm ghost" id ="Btnrpt">Create Report</button>

<div id="reportModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <table style="width:100%">
	<tr>
		<th>Name</th>
		<th>Purchased Quantity</th> 
		<th>Sold Quantity</th> 
		<th>Sell Price</th>
		<th>Cost</th>
        <th>Total Sales</th>
        <th>Total Cost</th>
        <th>Profit</th>

		

	</tr>
          
	<tr>
      
		<?php while($row1 = mysqli_fetch_array($reportResult)):;?>
				<tr>
					<td><center><?php echo $row1[3];?></center></td>
					<td><center><?php echo $row1[8];?></center></td>
					<td><center><?php echo $row1[1];?></center></td>
					<td><center><?php echo $row1[6];?></center></td>
					<td><center><?php echo $row1[5];?></center></td>
                    <td><center><?php echo $row1[6] * $row1[1];?></center></td>
                    <td><center><?php echo $row1[8] * $row1[5];?></center></td>
                    <td><center><?php echo ($row1[6] * $row1[1]) - ($row1[8] * $row1[5]);?></center></td>
			  </tr>
        <?php endwhile;?>
				</tr>
	</table>
    
 
	<center> <button class="btn" id="backReport">Back</button></center>
	</p>
  </div>

</div>




<button class ="btn sm ghost" id="Btnnew">New Item</button>

<div id="newItemModal" class="modal">

	<div class="modal-content">

		<p><form method="post" action="manager.php" id="newItemForm"><br>
        
      
        <center>
		Item Name:<br>
		<input type="text" name="name"><br>
		<!--<input type="text" name="item">--><br>
		Description:<br>
		<input type="text" name="description"><br>
		
		Cost:<br>
		<input type="text" name="cost"><br>
		
		Price:<br>
		<input type="text" name="price"><br>
		<button type="submit" class="btn" name="new_item">Confirm</button>
		</center>
		</form>
		<center> <button class="btn" id="backNew">Back</button></center>
		</p>
	</div>
</div>

</center>


<script>
var backReq = document.getElementById("backRequest");
var backInv = document.getElementById("backInventory");
var backPlc = document.getElementById("backPlace");
var backRpt = document.getElementById("backReport");
var backNew = document.getElementById("backNew");

var modalReq = document.getElementById('orderRequestModal');
var btnReq = document.getElementById("Btnreq");


var modalInv = document.getElementById('inventoryModal');
var btnInv = document.getElementById("Btninv");

var modalPlc = document.getElementById('placeOrderModal');
var btnPlc = document.getElementById("Btnplc");

var modalRpt = document.getElementById('reportModal');
var btnRpt = document.getElementById("Btnrpt");

var modalNew = document.getElementById('newItemModal');
var btnnew = document.getElementById("Btnnew");

function orderRequestController()
{
//view table of order requests sent from warehouse worker. 
//able to set boolean of processed from false to true
}

btnReq.onclick = function() {
    modalReq.style.display = "block";
}

btnInv.onclick = function() {
    modalInv.style.display = "block";
}

btnPlc.onclick = function() {
    modalPlc.style.display = "block";
}

btnRpt.onclick = function() {
    modalRpt.style.display = "block";
}

btnnew.onclick = function() {
    modalNew.style.display = "block";
}

<!-- span.onclick = function() { -->
    <!-- modal.style.display = "none"; -->
<!-- } -->

window.onclick = function(event) {
    if (event.target == modalReq) {
        modalReq.style.display = "none";
    }
}

window.onclick = function(event) {
    if (event.target == modalInv) {
        modalInv.style.display = "none";
    }
}

window.onclick = function(event) {
    if (event.target == modalPlc) {
        modalPlc.style.display = "none";
    }
}

window.onclick = function(event) {
    if (event.target == modalRpt) {
        modalRpt.style.display = "none";
    }
}

window.onclick = function(event) {
    if (event.target == modalNew) {
        modalNew.style.display = "none";
    }
}

backReq.onclick = function() {
	modalReq.style.display = "none";
	
}

backInv.onclick = function() {
	modalInv.style.display = "none";
	
}

backPlc.onclick = function() {
	modalPlc.style.display = "none";
	
}

backRpt.onclick = function() {
	modalRpt.style.display = "none";
}

backNew.onclick = function() {
	modalNew.style.display = "none";
}



</script>
</body>
</html>
