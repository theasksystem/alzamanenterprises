<?php

session_start();
error_reporting(0);
include('../include/db.class.php');
include('../include/functions.php');

if(isset($_POST['product_id'])){

    $unid  = $_SESSION['UNIQUEID'];
    $unid2  = $_SESSION['LOGIN_ID'];
    $pid   = $_POST['product_id'];
    $price = $_POST['pagetotal'];
    $qty = $_POST['qty'];
    $size = $_POST['size'];
    $cs = $_POST['cs'];

    $cartqur = $conn->prepare("select * from cart where un_id = '$unid' and pid = '$pid' and size_id = '$size' and color_id = '$cs'");
    $cartqur->execute();
    $updatecart = $cartqur->fetch(PDO::FETCH_ASSOC);
    
    $getProductdetail = $conn->prepare("SELECT * FROM products WHERE id = '$pid'");
	$getProductdetail->execute();
	$getProductRow = $getProductdetail->fetch(PDO::FETCH_ASSOC);
	
	$subtotal2 =  $price*$qty;
	$shippingcharg = $getProductRow['ship_charge'];
	$subtotal  =  $subtotal2+$shippingcharg; 

    if($updatecart['pid']!=''){
        
		$cartid = $updatecart['id'];
		$qty = $updatecart['qty']+$qty;
		
        $stmt = $conn->prepare("UPDATE cart SET un_id=:unid, pid=:pid, price=:price, qty=:qty, size_id=:size, color_id=:cs, ship_charge=:shippingcharg, curr_ip = :curr_ip, created_at = :created_at  WHERE id=:id");
        $stmt->bindParam(':unid', $unid, PDO::PARAM_STR);
        $stmt->bindParam(':pid', $pid, PDO::PARAM_INT);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->bindParam(':qty', $qty, PDO::PARAM_INT);
        $stmt->bindParam(':size', $size, PDO::PARAM_INT);
        $stmt->bindParam(':cs', $cs, PDO::PARAM_INT);
        $stmt->bindParam(':shippingcharg', $shippingcharg, PDO::PARAM_STR);
        $stmt->bindParam(':curr_ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
        $stmt->bindParam(':created_at',$globaldate, PDO::PARAM_STR);
        $stmt->bindParam(':id', $cartid, PDO::PARAM_INT);
        $stmt->execute();
        
        $deletewish = $conn->prepare("DELETE FROM wishlist WHERE un_id = '$unid2' and pid = '$pid'");
    	$deletewish->execute();
    	
    	$deleteCpn = $conn->prepare("DELETE FROM apply_coupan WHERE un_id = '$unid' OR curr_ip = '".$_SERVER["REMOTE_ADDR"]."'");
    	$deleteCpn->execute();

    }else{

        $stmt = $conn->prepare("INSERT INTO `cart`(`un_id`, `pid`, `price`, `qty`, `size_id`, `color_id`, `ship_charge`, `curr_ip`, `created_at`)
        VALUES (:unid, :pid, :price, :qty, :size, :cs, :shippingcharg, :curr_ip, :created_at)");
        $stmt->bindParam(':unid', $unid, PDO::PARAM_STR);
        $stmt->bindParam(':pid', $pid, PDO::PARAM_INT);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->bindParam(':qty', $qty, PDO::PARAM_INT);
        $stmt->bindParam(':size', $size, PDO::PARAM_INT);
        $stmt->bindParam(':cs', $cs, PDO::PARAM_INT);
        $stmt->bindParam(':shippingcharg', $shippingcharg, PDO::PARAM_STR);
        $stmt->bindParam(':curr_ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
        $stmt->bindParam(':created_at',$globaldate, PDO::PARAM_STR);
        $stmt->execute();
        
        $deletewish = $conn->prepare("DELETE FROM wishlist WHERE un_id = '$unid2' and pid = '$pid'");
    	$deletewish->execute();
    	$deleteCpn = $conn->prepare("DELETE FROM apply_coupan WHERE un_id = '$unid' OR curr_ip = '".$_SERVER["REMOTE_ADDR"]."'");
    	$deleteCpn->execute();
    }
	
	

?>
  
		
		<table class="timetable_sub">
        <thead>
          <tr>
            <th>Product</th>
            <th>Product Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Shipping Charge</th>
            <th>Sub Total</th>
          </tr>
        </thead>
        <tbody>
        
          <tr class="rem1">
            <td class="invert-image">
            	<img src="<?= $WebsiteUrl.'/'; ?>adminuploads/product/<?= $getProductRow['image']; ?>" style="width:70px;" class="img-responsive">
            
            </td>
            <td class="invert"><?= substr($getProductRow['product_name_en'],0,20); ?></td>
            <td class="invert">ريال <?=$price; ?></td>
            <td class="invert"><?=$qty; ?></td> 
            <td class="invert">ريال <?= $shippingcharg; ?></td>
            <td class="invert">ريال <?=$subtotal; ?></td>
            
          </tr>
          
        </tbody>
        </table>
        
<?php

}

if(isset($_POST['product_id2'])){

    $unid  = $_SESSION['LOGIN_ID'];
    $pid   = $_POST['product_id2'];
    $price = $_POST['pagetotal'];
    $qty = $_POST['qty'];

    

        $stmt = $conn->prepare("INSERT INTO `wishlist`(`un_id`, `pid`, `price`, `qty`, `curr_ip`, `created_at`)
        VALUES (:unid, :pid, :price, :qty, :curr_ip, :created_at)");
        $stmt->bindParam(':unid', $unid, PDO::PARAM_STR);
        $stmt->bindParam(':pid', $pid, PDO::PARAM_INT);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->bindParam(':qty', $qty, PDO::PARAM_INT);
        $stmt->bindParam(':curr_ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
        $stmt->bindParam(':created_at',$globaldate, PDO::PARAM_STR);
        $stmt->execute();
   
	
	$getProductdetail = $conn->prepare("SELECT * FROM products WHERE id = '$pid'");
	$getProductdetail->execute();
	$getProductRow = $getProductdetail->fetch(PDO::FETCH_ASSOC);
	
	$subtotal2 =  $price*$qty;
	$shippingcharg = $getProductRow['ship_charge'];
	$subtotal  =  $subtotal2+$shippingcharg; 

?>
  
		
		<table class="timetable_sub">
        <thead>
          <tr>
            <th>Product</th>
            <th>Product Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Shipping Charge</th>
            <th>Sub Total</th>
          </tr>
        </thead>
        <tbody>
        
          <tr class="rem1">
            <td class="invert-image">
            	<img src="<?= $WebsiteUrl.'/'; ?>adminuploads/product/<?= $getProductRow['image']; ?>" style="width:70px;" class="img-responsive">
            
            </td>
            <td class="invert"><?= substr($getProductRow['product_name_en'],0,20); ?></td>
            <td class="invert">ريال <?=$price; ?></td>
            <td class="invert"><?=$qty; ?></td> 
            <td class="invert">ريال <?= $shippingcharg; ?></td>
            <td class="invert">ريال <?=$subtotal; ?></td>
            
          </tr>
          
        </tbody>
        </table>
        
<?php

}

if(isset($_REQUEST['cart_item_id']) && isset($_REQUEST['cart_item_qty']))
{
    
    $cartpid   = base64_decode($_POST['cart_item_id']);
    $cartqty   = $_POST['cart_item_qty'];

    if($cartpid!='' && $cartqty!='')
    {
      $stmt = $conn->prepare("UPDATE cart SET qty=:qty WHERE id=:id");
      $stmt->bindParam(':qty', $cartqty, PDO::PARAM_INT);
      $stmt->bindParam(':id', $cartpid, PDO::PARAM_INT);
      $stmt->execute();
      
      $deleteCpn = $conn->prepare("DELETE FROM apply_coupan WHERE un_id = '".$_SESSION['UNIQUEID']."' OR curr_ip = '".$_SERVER["REMOTE_ADDR"]."'");
      $deleteCpn->execute();
    
	  echo "<script>window.location.href='cart'</script>";
    }
    else
    {
       
      echo "<script>window.location.href='cart'</script>";
    }

}

// delete cart item
if(isset($_REQUEST['delete_cart_id']) && $_REQUEST['delete_cart_id']!='')
{
    $userunid  = $_SESSION['UNIQUEID'];
    $cartpid   = base64_decode($_POST['delete_cart_id']);

    if($cartpid!='')
    {
      $stmt = $conn->prepare("DELETE FROM cart WHERE id=:id");
      $stmt->bindParam(':id', $cartpid, PDO::PARAM_INT);
      $stmt->execute();
      
      $deleteCpn = $conn->prepare("DELETE FROM apply_coupan WHERE un_id = '".$_SESSION['UNIQUEID']."' OR curr_ip = '".$_SERVER["REMOTE_ADDR"]."'");
      $deleteCpn->execute();
      
	  echo "<script>window.location.href='cart'</script>";
    }
    else
    {
       echo "<script>window.location.href='cart'</script>";
    }

}

// delete wishlist item
if(isset($_REQUEST['delete_cart_id2']) && $_REQUEST['delete_cart_id2']!='')
{
    $userunid  = $_SESSION['LOGIN_ID'];
    $cartpid   = base64_decode($_GET['delete_cart_id2']);

    if($userunid!='' && $cartpid!='')
    {
      $stmt = $conn->prepare("DELETE FROM wishlist WHERE id=:id and un_id = :unid");
      $stmt->bindParam(':id', $cartpid, PDO::PARAM_INT);
      $stmt->bindParam(':unid', $userunid, PDO::PARAM_STR);
      $stmt->execute();
	  echo "<script>window.location.href='wishlist'</script>";
    }
    else
    {
       echo "<script>window.location.href='wishlist'</script>";
    }

}

if(isset($_POST['id']) && $_POST['id']!=''){

  $id = $_POST['id'];

  $data = OrderDetailsArb($conn,$id);
    echo $data;
}