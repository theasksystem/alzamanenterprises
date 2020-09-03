<?php

    session_start();
    error_reporting(0);
    include('include/db.class.php');
    if($_SESSION['LOGIN_ID']=='')
    {
        echo "<script>window.location.href='".$WebsiteUrl."/login-register'</script>";
    }
?>

<?php 
    include('header.php');
    $_SESSION['previous_page'] = $absolute_url; 
?>

<style>
    .disabled-link{
    cursor: default;
    pointer-events: none;        
    text-decoration: none;
    color: grey;
}
</style>

<?php

    $orderId = $_GET['id'];
    $query = $conn->prepare("SELECT * FROM cart_orders WHERE id = '$orderId'");
    $query->execute();
    $orderdetail = $query->fetch(PDO::FETCH_ASSOC);
?>
    <link rel="stylesheet" type="text/css" href="<?= $WebsiteUrl.'/'; ?>css/checkout.css">
    <div class="main-dv-sec" >
    <div class="heading-main">
    <h2><strong><a href="<?= $WebsiteUrl.'/'; ?>">Home</a></strong>  /  <a href="<?= $WebsiteUrl.'/'; ?>dashboard">User Dashboard</a> / <span>Order Items</span></h2>
    </div>
    <section class="orderdetails">
    <section class="checkout  py-md-3 py-sm-3 py-3" >
        <div class="container py-md-4 py-sm-4 py-3">
            <h3 class="fsz-25 ptb-15 text-left"><span class="light-font"></span> <strong>Order Details #ALZ - <?=$orderId?></strong> </h3>
            <div class="shop_inner_inf">
                <div class="privacy about">
                    <div class="checkout-right">
                        <table class="timetable_sub">
                            <thead>
                            <tr>
                                <th>SL No.</th>
                                <th>Product</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Sub Total</th>
                                <th>Return</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $orderItems = $conn -> prepare("SELECT * from cart_order_item WHERE order_id = '$orderId'");
                                    $orderItems->execute();
                                    $itemCounter = 1;
                                    if($orderItems->rowCount()!='') {
                                        while($item = $orderItems->fetch(PDO::FETCH_ASSOC)) {
                                            $itemId = $item['pid'];
                                            $quantity = $item['qty'];
                                            $price = $item['price'];
                                            $product = $conn->prepare("select * from products where id = '$itemId'");
                                            $product->execute();
                                            $productDetails = $product->fetch(PDO::FETCH_ASSOC);
                                            $colorquery = $conn ->prepare("select color from products_color where id = '".$item['color_id']."'");
                                            $colorquery->execute();
                                            $color1 = $colorquery->fetch(PDO::FETCH_ASSOC);
                                            $color = $color1['color'];
                                            $sizequery = $conn ->prepare("select size from products_size where id = '".$item['size_id']."'");
                                            $sizequery->execute();
                                            $size1 = $sizequery->fetch(PDO::FETCH_ASSOC);
                                            $size = $size1['size'];
                                            ?>
                                            <tr>
                                                <td class="invert">
                                                    <?=$itemCounter?>
                                                </td>
                                                <td class="invert-image">
                                                    <img src="<?= $WebsiteUrl.'/'; ?>adminuploads/product/<?= $productDetails['image'];?>" style="width: 70px;" class="img-responsive">
                                                </td>
                                                <td class="invert">
                                                    <?= $productDetails['product_name_en']; ?> 
                                                    <?php   if ($size != "") {  ?>
                                                                <br>(Size: <?=$size; ?>) 
                                                    <?php   }
                                                            if ($color != "") { ?>
                                                                <br>(Color: <?=$color; ?>)
                                                    <?php   }   ?>
                                                </td>
                                                <td class="invert">
                                                    <?='QAR '.$price; ?>
                                                </td>
                                                <td class="invert">
                                                    <?=$quantity; ?>
                                                </td> 
                                                <td class="invert">
                                                    <?='QAR '.($quantity * $price);?>
                                                </td>
                                                <?php if($orderdetail['returnable'] == 1 && $item['returned'] == 0){?>
                                                    <td class="center"><a onClick="returnItem('<?=$orderId?>','<?=$itemId?>','<?=$quantity?>','<?=$price?>');" style="cursor: pointer;color: goldenrod;border:1px solid;border-color:#daa61f;padding:8px;" >Return</a></td>
                                                <?php   } else {    
                                                        if ($item['returned'] == 1)
                                                            $message = "ITEM RETURNED";
                                                        else
                                                            $message = "Return Period Expired"
                                                        ?>
                                                    <td class="center disabled-link"><a style="cursor: pointer;color: grey;border:1px solid;border-color:grey;padding:8px;" disabled><?=$message;?></a></td>
                                                <?php   }   ?>
                                            </tr>
                                            <?php $itemCounter++;?>
                                    <?php    }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </section>
    </div>
    <script>
        function returnItem(orderId,itemId,quantity,price)  {
            var dataString = 'orderId='+ orderId + '&itemId='+ itemId;
            $.ajax({
              type: "POST",
              url: "<?= $WebsiteUrl.'/'; ?>dataFetcher.php",
              data: dataString,
              cache: false,
              success: function(response){
                location.reload();
              }
        });
        }
    </script>
</body>
</html>