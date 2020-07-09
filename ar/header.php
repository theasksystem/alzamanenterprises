<?php

if(!isset($_SESSION['UNIQUEID'])){

	$_SESSION['UNIQUEID'] = uniqid().time().str_shuffle(12345);
}


//cart query 
$cartquery = $conn->prepare("select count(*) as cartcount, sum(price) as total,sum(qty) as totalqty from cart where un_id = '".$_SESSION['UNIQUEID']."' OR curr_ip = '".$_SERVER["REMOTE_ADDR"]."'");
$cartquery->execute();
$carttoggle = $cartquery->fetch(PDO::FETCH_ASSOC);
if($carttoggle['cartcount']!='')
{
	$carttopcount = $carttoggle['cartcount'];
}else
{
	$carttopcount = 0;
}

if($carttoggle['total']!='')
{
	$carttoptotal = $carttoggle['total'];
}else
{
	$carttoptotal = 0;
}
$carttopqty=$carttoggle['totalqty'];

function url_origin( $s, $use_forwarded_host = false )
{
    $ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
    $sp       = strtolower( $s['SERVER_PROTOCOL'] );
    $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
    $port     = $s['SERVER_PORT'];
    $port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
    $host     = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null );
    $host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;
    return $protocol . '://' . $host;
}

function full_url( $s, $use_forwarded_host = false )
{
    return url_origin( $s, $use_forwarded_host ) . $s['REQUEST_URI'];
}

$absolute_url = full_url( $_SERVER );

$en_url = str_replace("alzamanenterprises.com/ar","alzamanenterprises.com",$absolute_url);

?>
<!DOCTYPE html>
<html lang="ar_SA" dir="rtl">
<head>
	<meta charset="UTF-8">
	<meta content="ar" http-equiv="content-language" />
    <meta name="google" value="notranslate">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Alzaman Enterprises is Qatar's Best Fashion and Lifestyle Online Shopping site for men, women & kids. Buy clothing, shoes, Watches, footwear and more from your...">
    <meta name="keywords" content="Alzaman Enterprises,Online shopping in Qatar,Shopping in Qatar,Shopping Qatar,Online shopping,Buy online,Shop online,Buy and sale in Qatar,Online website in Qatar">
    <meta name="author" content="Alzaman Enterprises">
    <title>Alzaman Enterprises</title>
    <link href="<?= $WebsiteUrl2.'/'; ?>css/bootstrap.css" rel="stylesheet" />
    <link href="<?= $WebsiteUrl2.'/'; ?>css/style.css" rel="stylesheet" />
    <link href="<?= $WebsiteUrl.'/'; ?>css/jquery-ui.css" rel="stylesheet" />
    <link href="<?= $WebsiteUrl2.'/'; ?>css/responsive.css" rel="stylesheet" />
    <link href="<?= $WebsiteUrl2.'/'; ?>css/slick-theme.css" rel="stylesheet" />
    <link href="<?= $WebsiteUrl2.'/'; ?>css/slick-theme-2.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    <link rel="icon" href="<?= $WebsiteUrl.'/'; ?>images/favicon.jpeg" type="image/gif" sizes="16x16">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="<?= $WebsiteUrl2.'/'; ?>css/new/owl.carousel.min.css"/>
    <link rel="stylesheet" href="<?= $WebsiteUrl2.'/'; ?>css/new/style.css"/>
    
    	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300,300i,400,400i,700,700i" rel="stylesheet">
    	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link href="<?= $WebsiteUrl2.'/'; ?>css/responsive2.css" rel="stylesheet" />
    <style>
 	.mySidenav{
		width:280px;
	}
 </style> 
</head>
<body>


<div class="loads">
<div class="loader">
          <div class="loader-img">
             <img  src="<?=$WebsiteUrl.'/'; ?>images/logo.png">
           </div>
            <div id="movingBallG">
                <div class="movingBallLineG"></div>
                <div id="movingBallG_1" class="movingBallG"></div>
            </div>
</div>
</div>



<div id="main3">
<?php
$topbar2 = $conn->prepare("select name_en,name_ar,visible from notification where id = 2");
$topbar2->execute();
$topbar = $topbar2->fetch(PDO::FETCH_ASSOC);
if($topbar['visible']==1){
?>
    <div class="col-md-12 col-xs-12 topoffersection">
                    <marquee direction="right"  behavior="alternate" scrollamount="1" class="qus text-left"><?php if($topbar['name_ar']!=''){ echo $topbar['name_ar']; }else{ echo $topbar['name_en']; } ?></marquee>
         
    </div><!--end of topoffersection-->
<?php } ?> 

<div id="header2" > 
<section id="header">
  <div class="container-fluid">
      <div class="logo-head">
          <div class="desktop-none">
              <div id="mySidenav" class="sidenav">
                

                
                 <div class="logo mobile-view">
                 <a href="javascript:void(0)" class="closebtn">&times;</a> <a href="<?= $WebsiteUrl2; ?>"><img src="<?= $WebsiteUrl.'/'; ?>images/logo.png"></a> 
                  
<?php if($_SESSION['LOGIN_ID']!=''){ ?>
 <div class="login_register"><div class="inner"> <a href="javascript:;" class="has-sub-m accordion"><?=$_SESSION['LOGIN_NAME']; ?> <i class="fas fa-caret-down"></i></a>                      
                        
                        <ul class="panel" style="display:none;">
                            <li><a href="<?= $WebsiteUrl2.'/'; ?>profile">حسابي</a></li>
                            <li><a href="<?= $WebsiteUrl2.'/'; ?>dashboard">الطلبات</a></li>
                            <li><a href="<?= $WebsiteUrl2.'/'; ?>wishlist">المفضلات</a></li>
                            <li><a href="<?= $WebsiteUrl2.'/'; ?>address">عناويني</a></li>
                            <li><a href="<?= $WebsiteUrl2.'/'; ?>logout.php">تسجيل الخروج</a></li>
                            
                        </ul>
 </div></div>        
                      <?php }else{ ?>
                      <div class="login_register"><div class="inner"> <a href="<?= $WebsiteUrl2.'/'; ?>login-register">تسجيل الدخول</a> / <a href="<?= $WebsiteUrl2.'/'; ?>login-register">إنشاء الحساب</a> </div></div>
                      <?php } ?>
                 </div>
        <?php
        
       $tab2 = $conn->prepare("select id,name_ar from  department where visible = 1 order by id ASC");
       $tab2->execute();
       
         while ($tab = $tab2->fetch(PDO::FETCH_ASSOC))
         {
         $category2 = $conn->prepare("select id,name_ar from  sub_department where did = '".$tab['id']."' and visible = 1 order by id ASC");
         $category2->execute();
         if($category2->rowCount() != 0){
          $tabLink = 'javascript:;';
         }
         else{
           $tabLink = $WebsiteUrl2.'/'.'products?tid='.base64_encode(base64_encode($tab['id']));
         }      
                    
        ?>
                <a href="<?=$tabLink; ?>" class="has-sub-m accordion font-weight-bold"><?=$tab['name_ar']; ?> <?php if($category2->rowCount() != 0){ ?><i class="fa fa-caret-down"></i><?php } ?></a>
                <?php if($category2->rowCount() != 0){ ?>
                <div class="panel" style="display:none;">
                <?php 
         while ($category = $category2->fetch(PDO::FETCH_ASSOC)){
         $subcategory2 = $conn->prepare("select id,name_ar from  sub_cat where did = '".$tab['id']."' and sid = '".$category['id']."' and visible = 1 order by id ASC");
         $subcategory2->execute();
         if($subcategory2->rowCount() != 0){
          $catLink = 'javascript:;';
         }
         else{
           $catLink = $WebsiteUrl2.'/products?tid='.base64_encode(base64_encode($tab['id'])).'&did='.base64_encode(base64_encode($category['id']));
         }
        ?>
                  <a href="<?=$catLink; ?>" class="has-sub-m1 accordion mmmm"><?=$category['name_ar']; ?> <?php if($subcategory2->rowCount() != 0){ ?><i class="fa fa-caret-down"></i><?php } ?></a>
                  <?php if($subcategory2->rowCount() != 0){ ?>
                    <div class="panel  panel2 " style="display:none;">
                    <?php
           while ($subcategory = $subcategory2->fetch(PDO::FETCH_ASSOC)){
          ?>
                      <a href="<?= $WebsiteUrl2.'/'; ?>products?tid=<?=base64_encode(base64_encode($tab['id'])); ?>&did=<?=base64_encode(base64_encode($category['id'])); ?>&sid=<?=base64_encode(base64_encode($subcategory['id'])); ?>"><?=$subcategory['name_ar']; ?></a>
                    <?php } ?>
                    </div>
                <?php }} ?>    
                </div>
              <?php }} ?>
              </div>

              <span  class="toggle"><i class="fa fa-bars barss" aria-hidden="true"></i></span>
             </div>

          <div class="logo desktop-view"> <a href="<?= $WebsiteUrl2; ?>"><img src="<?= $WebsiteUrl.'/'; ?>images/logo.png"></a> </div>
          <div class="search-bar">
            <form name="" action="products" method="get">
            <input type="text" id="myInput" class="typeahead" name="s" placeholder="البحث عن منتجات" autocomplete="off" value="<?=$_GET['s']; ?>">
            <button type="submit"><i class="fa fa-search"></i></button>
            <div id="myInput2"></div>
            </form>
          </div>
          <div class="login-list">
            <ul>
                        <?php if($_SESSION['LOGIN_ID']!=''){ ?>
                        
                        <li class="login-p  mobile-none-2"><a href="javascript:;"><?=$_SESSION['LOGIN_NAME']; ?> <i class="fas fa-caret-down"></i></a>
                        <ul class="login-drop" style="display:none;">
                            <li><a href="<?= $WebsiteUrl2.'/'; ?>profile">حسابي</a></li>
                            <li><a href="<?= $WebsiteUrl2.'/'; ?>dashboard">الطلبات</a></li>
                            <li><a href="<?= $WebsiteUrl2.'/'; ?>wishlist">المفضلات</a></li>
                            <li><a href="<?= $WebsiteUrl2.'/'; ?>address">عناويني</a></li>
                            <li><a href="<?= $WebsiteUrl2.'/'; ?>logout.php">تسجيل الخروج</a></li>
                            
                        </ul>
                        </li>          
                      <?php }else{ ?>
                      <li class="mobile-none-2" ><a href="<?= $WebsiteUrl2.'/'; ?>login-register">Login & Signup</a>
                      </li>
                      <?php } ?>
                            
                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
                        <script>
                        $(document).ready(function(){
                          $(".login-p").click(function(){
                            $(".login-drop").toggle();
                          });
                        });
                        </script>
                      
                      <?php if($carttopcount!=0){ ?>
                      <li><a href="<?= $WebsiteUrl2.'/'; ?>cart"><i class="fa fa-cart-plus" aria-hidden="true"></i> <span class="mobile-none-2">Cart</span> (<?=$carttopcount; ?>)</a></li>
                      <?php }else{ ?>
                      <li><a href="javascript:;"><i class="fa fa-cart-plus" aria-hidden="true"></i> <span class="mobile-none-2">Cart</span></a></li>
                      <?php } ?>
                      
                      <li class="google"><a href="<?= $en_url; ?>"> EN </a></li>
            </ul>
          </div>
      </div>
  </div>
</section>
</div>


<section class="navigation mobile-none" id="navbar">
  <div class="container-fluid">
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
      <button type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbars" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler"> <span class="navbar-toggler-icon"></span> </button>
      <div id="navbarContent" class="collapse navbar-collapse">
        <ul class="navbar-nav mx-auto">
          <?php
        
       $tab2 = $conn->prepare("select id,name_ar from  department where visible = 1 order by id ASC");
       $tab2->execute();
       
       while ($tab = $tab2->fetch(PDO::FETCH_ASSOC))
       {
       $category2 = $conn->prepare("select id,name_ar from  sub_department where did = '".$tab['id']."' and visible = 1 order by id ASC");
       $category2->execute();
            
                  
          ?>
          <li class="nav-item dropdown megamenu"><a id="megamneu" href="<?= $WebsiteUrl2.'/'; ?>products?tid=<?=base64_encode(base64_encode($tab['id'])); ?>" <?php if($category2->rowCount() != 0){ ?>data-toggle="dropdown"<?php } ?> aria-haspopup="true" aria-expanded="false" class="nav-link <?php if($category2->rowCount() != 0){ ?> dropdown-toggle<?php } ?> font-weight-bold text-uppercase"><?=$tab['name_ar']; ?></a>
            <div aria-labelledby="megamneu" class="dropdown-menu border-0 p-0 m-0">
              <div class="container">
                <div class="row bg-white rounded-0 m-0 shadow-sm">
                  <?php
               
               if($category2->rowCount() != 0){
          ?>
                  <div class="col-lg-7 col-xl-8 col-sm-12 col-xs-12">
                    <div class="p-4">
                      <div class="row">
                        <?php
        
             
             while ($category = $category2->fetch(PDO::FETCH_ASSOC))
             {
              $subcategory2 = $conn->prepare("select id,name_ar from  sub_cat where did = '".$tab['id']."' and sid = '".$category['id']."' and visible = 1 order by id ASC");
              $subcategory2->execute();
                  
                      ?>
                        <div class="col-lg-6 mb-4">
                          <h6 class="text-uppercase font-weight-bold"><a href="<?= $WebsiteUrl2.'/'; ?>products?tid=<?=base64_encode(base64_encode($tab['id'])); ?>&did=<?=base64_encode(base64_encode($category['id'])); ?>" class="navcat"><?=$category['name_ar']; ?></a></h6>
                          <?php
               
               if($subcategory2->rowCount() != 0){
             ?>
                          <ul class="list-unstyled">
                            <?php
        
                 
                 while ($subcategory = $subcategory2->fetch(PDO::FETCH_ASSOC))
                 {
                
                        ?>
                            <li class="nav-item"><a href="<?= $WebsiteUrl2.'/'; ?>products?tid=<?=base64_encode(base64_encode($tab['id'])); ?>&did=<?=base64_encode(base64_encode($category['id'])); ?>&sid=<?=base64_encode(base64_encode($subcategory['id'])); ?>" class="nav-link text-small pb-0 navsubcat"><?=$subcategory['name_ar']; ?></a></li>
                            <?php } ?>
                          </ul>
                          <?php } ?>
                        </div>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                  <?php } ?>
                </div>
              </div>
            </div>
          </li>
          <?php } ?>
          <!-- <li class="nav-item"><a href="product.php" class="nav-link font-weight-bold text-uppercase">Products</a></li>-->
        </ul>
      </div>
    </nav>
  </div>
</section>