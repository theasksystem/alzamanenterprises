<?php

session_start();
error_reporting(0);
include('../include/db.class.php');

if($_SESSION['LOGIN_ID']=='')
{
   echo "<script>window.location.href='".$WebsiteUrl2."/login-register'</script>";
}

$userData2 = $conn->prepare("select * from registration where id = '".$_SESSION['LOGIN_ID']."'");
$userData2->execute();
$userData = $userData2->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['update'])){

    $name = $_POST["name"];
	$lastname =  trim($_POST["lastname"]);
	//$company_name = $_POST["cname"];
	$phone = $_POST["phone"];
	$email = $_POST["email"];
	$gender = trim($_POST["gender"]);
	$dob = trim($_POST["dob"]);
	$pdd = $_POST["pdd"];
	$pyy = $_POST["pyy"];
	
	$oldimg = $_POST['oldimg'];

	$img = $_FILES['image']['name'];
	$imgtmp = $_FILES['image']['tmp_name'];
	
	$img ? $im = time().'-'.$img : $im = $oldimg;
	
	if($img!=''){
		unlink('../adminuploads/user/'.$oldimg);
		move_uploaded_file($imgtmp,'../adminuploads/user/'.$im);
	}

 $stmt = $conn->prepare("UPDATE `registration` set `name`=:name, `lastname`=:lastname, `gender`=:gender, `dob`=:dob, `pdd`=:pdd, `pyy`=:pyy, `phone`=:phone, `email`=:email, `image` = :image where id = '".$_SESSION['LOGIN_ID']."'");

       $stmt->bindParam(':name', $name, PDO::PARAM_STR);
       //$stmt->bindParam(':cname', $company_name, PDO::PARAM_STR);
       $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
       $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
       $stmt->bindParam(':dob', $dob, PDO::PARAM_STR);
	  $stmt->bindParam(':pdd', $pdd, PDO::PARAM_STR);
	  $stmt->bindParam(':pyy', $pyy, PDO::PARAM_STR);
       $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
       $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  	   $stmt->bindParam(':image', $im, PDO::PARAM_STR);
       $stmt->execute();
	   
	   if($stmt == true){		
	    echo "<script type='text/javascript'>alert('Your Profile have been Updated Successfully..');</script>";
        echo "<script>window.location.href='profile'</script>";
    } else {
        echo "<script type='text/javascript'>alert('It seems some error accured. please try again.');</script>";
	
	}

}


?>
<?php include('header.php'); 

$_SESSION['previous_page'] = $absolute_url;

?>
	<link href="<?= $WebsiteUrl.'/'; ?>css/bootstrap-datepicker.css" rel="stylesheet" />
<script>

function PreviewImage()
{
	var oFReader = new FileReader();
	oFReader.readAsDataURL(document.getElementById("image").files[0]);
	oFReader.onload = function (oFREvent) {
		document.getElementById("uploadPreview").src = oFREvent.target.result;
	};
};

</script>

<div class="main-dv-sec" style="background:#F1F3F6 !important;">
  <div class="heading-main">
    <h2><strong><a href="<?= $WebsiteUrl2.'/'; ?>">الرئيسية</a></strong> / <span>حسابي</span></h2>
 
 
  </div>
  
  
  <section class="pt-20 pb-40">
    <div class="container">
      <!-- Login Starts -->
      <div class="row">
      <?php include('dashboard-left.php'); ?>
        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12" id="sign-in-form">
          <div class="login-wrap product-in" style="background: #F1F3F6 !important;">
            <div class="bilinfo col-md-10" style="margin:0 auto;">
            <h3 class="fsz-25 ptb-15 text-right"><span class="light-font"></span> <strong>اعدادات الحساب </strong> </h3>
                    <form action="" name="" method="post" enctype="multipart/form-data">
                      <div class="row">
                        
                        <div class="col-md-12 col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="text" name="name" placeholder="الاسم الاول" value="<?=$userData['name']; ?>" required>
                          </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="text" name="lastname" placeholder="الاسم الاخير" value="<?=$userData['lastname']; ?>">
                          </div>
                        </div>
                        <!--<div class="col-md-12 col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="text" name="cname" placeholder="جهة العمل ( اختياري)"  value="<?=$userData['cname']; ?>">
                          </div>
                        </div>-->
                        <div class="col-md-6 col-sm-12 col-xs-12">
                          <div class="single-input">
                            <select name="gender"  required>
                            	<option value="">تحديد الجنس</option>
                                <option <?php if($userData['gender']=='Male'){ echo 'selected'; } ?> value="Male">الذكر</option>
                    			<option <?php if($userData['gender']=='Female'){ echo 'selected'; } ?> value="Female">الانثى</option>
                              
                            </select>
                            
                          </div>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="number" name="phone" placeholder="3155 9977" value="<?=$userData['phone']; ?>" required>
                          </div>
                        </div>
                         <div class="col-md-12 col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="email" name="email" placeholder="البريد الالكتروني" value="<?=$userData['email']; ?>" required>
                          </div>
                        </div>
                        
                        
                        
                               <div class="col-md-6 col-sm-12 col-xs-12">  
            
                  <div class="single-input">     
                    <select name="dob" id="dob" class="form-control" required>
                	  <option value="">Birth Month</option>
                      <option <?php if($userData['dob']=='January'){ echo 'selected'; } ?> value="January">January</option>
                      <option <?php if($userData['dob']=='February'){ echo 'selected'; } ?> value="February">February</option>
                      <option <?php if($userData['dob']=='March'){ echo 'selected'; } ?> value="March">March</option>
                      <option <?php if($userData['dob']=='April'){ echo 'selected'; } ?> value="April">April</option>
                      <option <?php if($userData['dob']=='May'){ echo 'selected'; } ?> value="May">May</option>
                      <option <?php if($userData['dob']=='June'){ echo 'selected'; } ?> value="June">June</option>
                      <option <?php if($userData['dob']=='July'){ echo 'selected'; } ?> value="July">July</option>
                      <option <?php if($userData['dob']=='August'){ echo 'selected'; } ?> value="August">August</option>
                      <option <?php if($userData['dob']=='September'){ echo 'selected'; } ?> value="September">September</option>
                      <option <?php if($userData['dob']=='October'){ echo 'selected'; } ?> value="October">October</option>
                      <option <?php if($userData['dob']=='November'){ echo 'selected'; } ?> value="November">November</option>
                      <option <?php if($userData['dob']=='December'){ echo 'selected'; } ?> value="December">December</option>
                </select> 
            
            </div>
            </div>
            
           <div class="col-md-3  col-sm-12 col-xs-12">      
                  <div class="single-input">     
                        <input type="number" name="pdd" id="pdd" class="form-control" placeholder="Birth Date(DD)" value="<?php echo $userData['pdd']; ?>" required>
                  </div>
            </div>
            
            <div class="col-md-3 col-sm-12 col-xs-12">      
                  <div class="single-input">     
                        <input type="number" name="pyy" id="pyy" class="form-control" placeholder="Birth Year(YYYY)" value="<?php echo $userData['pyy']; ?>" required>
                  </div>
            </div>  
                        
                       
                        <div class="col-md-12 col-sm-12 col-xs-12 text-right">
                        <div class="single-input">
                          <img src="<?=$WebsiteUrl; ?>/adminuploads/user/<?php echo $userData['image']; ?>" id="uploadPreview" width="150" height="150" >
                        </div>
                      </div>
						<div class="col-md-12 col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="file" name="image" id="image"  onChange="PreviewImage();">
                          </div>
                        </div>
                      
                       
                        <div class="col-md-12 col-sm-12 col-xs-12">
                          <div class="single-input">
              		        <input type="hidden" name="oldimg" value="<?php echo $userData['image']; ?>" />
                            <input type="submit" name="update" value="تحديث البيانات" class="checkout-btn">
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
          </div>
        </div>
        
        
      </div>
    </div>
  </section>
</div>

<?php include('footer.php'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>

<script type="text/javascript">
	$('.datepicker').datepicker();
	</script>

<script>

$(document).ready(function(){var owl=$('.owl-carousel');owl.owlCarousel({items:6,loop:true,margin:10,autoplay:true,autoplayTimeout:10000,autoplayHoverPause:true,responsiveClass:true,dots:false,responsive:{360:{items:1,},480:{items:2,},600:{items:3,},1000:{items:6,}}});$('.play').on('click',function(){owl.trigger('play.owl.autoplay',[1000])})
$('.stop').on('click',function(){owl.trigger('stop.owl.autoplay')})})
</script>

<script type="text/javascript">
window.onscroll = function() {myFunction()};
var navbar = document.getElementById("navbar");
var sticky = navbar.offsetTop;
function myFunction() {
  if (window.pageYOffset >= sticky) {
	navbar.classList.add("sticky")
  } else {
	navbar.classList.remove("sticky");
  }
};
</script>

<!-- Jquery for login register -->


</body>
</html>
