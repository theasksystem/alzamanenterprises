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

if(isset($_GET['ud']) && $_GET['ud']!=''){

$ud = base64_decode(base64_decode($_GET['ud']));
$DelAdd = $conn->prepare("delete from tbl_address where id = '$ud'");
$DelAdd->execute();

echo "<script>window.location.href='address'</script>";

}

?>
<?php include('header.php'); 

$_SESSION['previous_page'] = $absolute_url;

?>

<div class="main-dv-sec" style="background:#F1F3F6 !important;">
  <div class="heading-main">
    <h2><strong><a href="<?= $WebsiteUrl2.'/'; ?>">الرئيسية</a></strong> / <span>حسابي</span></h2>
  
  
  </div>
  <section class="pt-20 pb-40">
    <div class="container">
      <!-- Login Starts -->
      <div class="row">
       <?php include('dashboard-left.php'); ?>
        <div class="col-md-9  col-sm-12 col-xs-12" id="sign-in-form">
        
          <div class="login-wrap product-in"  style="background: #F1F3F6 !important;">
            <div class="bilinfo col-md-12  col-sm-12 col-xs-12" style="margin:0 auto;">
            <h3 class="fsz-25 ptb-15  text-right"><span class="light-font"></span> <strong>إدارة العناوين</strong> </h3>
                    
             <div class="_1yf-9T">
             	<div>
                	<div class="_2kr2AM  text-right" id="youradd">
                    	<i class="fa fa-plus" aria-hidden="true"></i>اضافة عنوان جديد
                    </div>
               </div>
           </div>  
           <div class="_1yf-9T" id="myadd" style="display:none;">
           <form action="" name="sign_up" method="post">
                      <div class="row">
                          <div class="col-md-12  col-sm-12 col-xs-12">
                              <div class="single-input">
                          <div class="heading-main">
                            <h2 style="padding-left: 0px;"><span><center><b>يرجى منكم تعبئة البيانات التالية، لتتمتعو بخدمة تسليم سلسة و سريعة🚚</b></center></span></h2>
                          </div>
                          </div>
                        </div>
                        <div class="col-md-8  col-sm-12 col-xs-12">
                        <div class="col-md-12  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="text" name="name" id="name" placeholder="الاسم">
                          </div>
                        </div>
                        <div class="col-md-12  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="number" name="state" id="state" placeholder="رقم المبنى">
                            	
                          </div>
                        </div>
                        <div class="col-md-12  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="number" name="zip" id="zip" placeholder="منطقة">
                          </div>
                        </div>
                        <div class="col-md-12  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="number" name="address" id="address" placeholder="اسم الشارع">
                          </div>
                        </div>
                        </div>
                        
                        <div class="col-md-4  col-sm-12 col-xs-12">
                           <div class="col-md-12  col-sm-12 col-xs-12">
                          <div class="single-input"> 
                            <img src="<?= $WebsiteUrl.'/'; ?>images/address.jpg" class="img-responsive">
                          </div>
                          </div>
                          
                        </div>
                        
                        <div class="col-md-6  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <select name="country" id="country" onChange="fetch_select(this.value);">
                            	<option value="">البلد</option>
                                <?php
                                     $query = $conn->prepare("SELECT id,name_ar FROM country WHERE visible=1");
										$query->execute();

									while ($row = $query->fetch(PDO::FETCH_ASSOC))
                                     {
                               ?>
                               <option value="<?php echo $row['id']; ?>"><?php echo $row['name_ar']; ?></option>
                               <?php } ?>
                            </select>
                            
                          </div>
                        </div>
                        
                        <div class="col-md-6  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <select name="city" id="city" >
                            	<option value="">المدينة</option>
                                
                            </select>
                          </div>
                        </div>
                        <div class="col-md-12  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="number" name="alt_mobile" id="alt_mobile" placeholder="+974-31559977" >
                          </div>
                        </div>
                                               
                        <div class="col-md-6  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="button" name="update" id="addAddress" value="اضافة العنوان" class="checkout-btn">
                          </div>
                        </div>
                        <div class="col-md-6  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="button" id="cancel" value="الالغاء" class="checkout-btn">
                          </div>
                        </div>
                        <div><img id="subsloader" height="100" style="display: none;" src="<?=$WebsiteUrl.'/'; ?>images/loader.gif"></div>
                      </div>
                    </form>
           </div>
           <?php
		   
		    $userData2 = $conn->prepare("select * from tbl_address where user_id = '".$_SESSION['LOGIN_ID']."' order by id desc");
			$userData2->execute();
			while($userData = $userData2->fetch(PDO::FETCH_ASSOC)){
			
			$addressData =$conn->prepare("SELECT `name_ar` as country FROM `country` where id='".$userData['country']."'");
			$addressData->execute();
			$addressData2 = $addressData->fetch(PDO::FETCH_ASSOC);
			$addressData4 =$conn->prepare("SELECT `name_ar` as city FROM `city` where id='".$userData['city']."'");
			$addressData4->execute();
			$addressData24 = $addressData4->fetch(PDO::FETCH_ASSOC);
			
			?>
           <div class="_2HW10N">
           <div class="_1MIUfH">
           <div class="iqngYe">
           <div class="_1suckO">
           <a id="UpdShow<?=$userData['id']; ?>"><i class="fa fa-edit" aria-hidden="true"></i></a> 
           <a href="address?ud=<?=base64_encode(base64_encode($userData['id'])); ?>" onClick="return confirm('Are you sure to remove this Address');"><i class="fa fa-trash" aria-hidden="true"></i></a>
			</div></div>
            <?php if($userData['setByDefault']==1){ ?> <div class="_2AIo8W">
           <span class="_3zENzM">الرئيسية</span>
           </div><?php  } ?>
           <p class="ZBYhh4"><span class="_2Fw4MM"><?=ucfirst($userData['name']); ?> &nbsp;&nbsp;<i style="font-size: 13px;"><?=$userData['alt_mobile']; ?></i></span>
           <span class="_3MbGVP _2Fw4MM"></span></p>
           <span class="ZBYhh4 _1Zn3iq text-right"><?php if($userData['state']!=''){ echo '<b> رقم المبنى - </b>'.$userData['state'];} ?><br><?php if($userData['zip']!=''){ echo '<b>منطقة - </b>'.$userData['zip'];} ?><br><?php if($userData['address']!=''){ echo '<b>اسم الشارع - </b>'.$userData['address'];} ?><br><?php if($userData['city']!=''){ echo $addressData24['city'].' - ';} ?><?php if($userData['country']!=''){ echo $addressData2['country'];} ?></span>
           </div>
           </div>
           
          <div class="_1yf-9T" id="UpdAdd<?=$userData['id']; ?>" style="display:none">
           <form action="" name="sign_up<?=$userData['id']; ?>" method="post">
                      <div class="row">
                        <div class="col-md-12  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="text" name="name<?=$userData['id']; ?>" id="name<?=$userData['id']; ?>" placeholder="الاسم" value="<?=ucfirst($userData['name']); ?>">
                          </div>
                        </div>
                        <div class="col-md-12  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="number" name="state<?=$userData['id']; ?>" id="state<?=$userData['id']; ?>" placeholder="رقم المبنى" value="<?=$userData['state']; ?>">
                            	
                          </div>
                        </div>
                        <div class="col-md-12  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="number" name="zip<?=$userData['id']; ?>" id="zip<?=$userData['id']; ?>" placeholder="منطقة" value="<?=$userData['zip']; ?>" >
                          </div>
                        </div>
                        <div class="col-md-12  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="text" name="address<?=$userData['id']; ?>" id="address<?=$userData['id']; ?>" placeholder="اسم الشارع" value="<?=$userData['address']; ?>">
                          </div>
                        </div>
                        <div class="col-md-6  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <select name="country<?=$userData['id']; ?>" id="country<?=$userData['id']; ?>" onChange="fetch_select<?=$userData['id']; ?>(this.value);">
                            	<option value="">البلد</option>
                                <?php
                                     $query = $conn->prepare("SELECT id,name_ar FROM country WHERE visible=1 order by name_en ASC");
										$query->execute();

									while ($row = $query->fetch(PDO::FETCH_ASSOC))
                                     {
                               ?>
                               <option <?php if($row['id']==$userData['country']){ echo 'selected'; } ?> value="<?php echo $row['id']; ?>"><?php echo $row['name_ar']; ?></option>
                               <?php } ?>
                            </select>
                            
                          </div>
                        </div>
                        <div class="col-md-6  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <select name="city<?=$userData['id']; ?>" id="city<?=$userData['id']; ?>" >
                            	<option value="">المدينة</option>
                                <?php
                                    $query3 = $conn->prepare("SELECT id,name_ar FROM city WHERE visible=1 and country_id='".$userData['country']."'");
									$query3->execute();
									while ($row3 = $query3->fetch(PDO::FETCH_ASSOC))
                                     {
                               ?>
                               <option <?php if($row3['id']==$userData['city']){ echo 'selected'; } ?> value="<?php echo $row3['id']; ?>"><?php echo $row3['name_ar']; ?></option>
                               <?php } ?>
                            </select>
                          </div>
                        </div>

                        <div class="col-md-12  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="number" name="alt_mobile<?=$userData['id']; ?>" id="alt_mobile<?=$userData['id']; ?>" value="<?=$userData['alt_mobile']; ?>" placeholder="+974-31559977" >
                          </div>
                        </div>
                                               
                        <div class="col-md-6  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="button" name="update" id="addAddress<?=$userData['id']; ?>" value="تحديث العنوان" class="checkout-btn">
                          </div>
                        </div>
                        <div class="col-md-6  col-sm-12 col-xs-12">
                          <div class="single-input">
                            <input type="button" id="cancel<?=$userData['id']; ?>" value="الالغاء" class="checkout-btn">
                          </div>
                        </div>
                        <div><img id="subsloader<?=$userData['id']; ?>" height="100" style="display: none;" src="<?=$WebsiteUrl.'/'; ?>images/loader.gif"></div>
                      </div>
                    </form>
           </div>
           
          
           <?php } ?>   
                    
                  </div>
          </div>
        </div>
        
        
      </div>
    </div>
  </section>
</div>

<?php include('footer.php'); ?>

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
<script>

$(document).ready(function(){
	
	$("#myadd").hide();	
	$("#cancel").click(function(){
		$("#myadd").hide();
	});
		
	$("#youradd").click(function(){
		$("#myadd").toggle();
	});
	
	
});	
</script>

<script type="text/javascript">
		
function fetch_select(val)
{
   //alert(val); 
	 $.ajax({
	 type: 'post',
	 url: '<?= $WebsiteUrl2.'/'; ?>getvalue.php',
	 data: {tab:val},
	 success: function (response) {
	   //alert(response);
	   document.getElementById('city').innerHTML=response; 
	 }
	 });

}



$('#addAddress').on("click", function(){
	
	var name = $("#name").val();
	var address = $("#address").val();
	var country = $("#country").val();
    var state = $("#state").val();
	var city = $("#city").val();
    var zip = $("#zip").val();
	var alt_mobile = $("#alt_mobile").val();		

     if(name==''){
	  
        $("#name").css({'border':'red 1px solid','background-color':'#eee'});
		document.sign_up.name.focus();
		return false;
    }
    if(state=='')
    {
	   $("#state").css({'border':'red 1px solid','background-color':'#eee'});
	   document.sign_up.state.focus();
	   return false;
    
	}
	if(zip=='')
    {
	   $("#zip").css({'border':'red 1px solid','background-color':'#eee'});
	   document.sign_up.zip.focus();
	   return false;
    
	}
	if(address=='')
    {
	  
        $("#address").css({'border':'red 1px solid','background-color':'#eee'});
		document.sign_up.address.focus();
		return false;
    }
	

	if(country=='')
    {
	   $("#country").css({'border':'red 1px solid','background-color':'#eee'});
	   document.sign_up.country.focus();
	   return false;
    }
	if(city=='')
    {
	   $("#user_cpass").css({'border':'red 1px solid','background-color':'#eee'});
	   document.sign_up.city.focus();
	   return false;
    }
	if(alt_mobile=='')
    {
	   $("#alt_mobile").css({'border':'red 1px solid','background-color':'#eee'});
	   document.sign_up.alt_mobile.focus();
	   return false;
    
	}else{
       
        $.ajax({
                type: "POST",
                url: "<?= $WebsiteUrl2.'/'; ?>login.php",
                data: {'name':name,'address':address,'country':country,'state':state,'city':city,'zip':zip,'alt_mobile':alt_mobile},
                cache: false,
				beforeSend: function(){
				
                        $( "#subsloader" ).show();
                    },
                    complete: function(){

                    },
                    success: function(data){
                        
                        setTimeout(function() {                                          
                          $( "#subsloader" ).hide();
						  
						  $("#myadd").hide();
						  location.reload();
                    }, 2000);

                    }
                
            });
     }

});

</script>
 <script>
 
         <?php
            $userData4 = $conn->prepare("select id from tbl_address where user_id = '".$_SESSION['LOGIN_ID']."' order by id desc");
			$userData4->execute();
			while($userData3 = $userData4->fetch(PDO::FETCH_ASSOC)){
        ?>

				$("#cancel<?=$userData3['id']; ?>").click(function(){
					$("#UpdAdd<?=$userData3['id']; ?>").hide();
				});
					
				$("#UpdShow<?=$userData3['id']; ?>").click(function(){
					$("#UpdAdd<?=$userData3['id']; ?>").toggle();
				});
				
				function fetch_select<?=$userData3['id']; ?>(val)
				{
				   //alert(val); 
					 $.ajax({
					 type: 'post',
					 url: '<?= $WebsiteUrl.'/'; ?>getvalue.php',
					 data: {tab:val},
					 success: function (response) {
					   // alert(response);
					   document.getElementById('city<?=$userData3['id']; ?>').innerHTML=response; 
					 }
					 });
					 
				}
			
				
				
				$('#addAddress<?=$userData3['id']; ?>').on("click", function(){
					
					var name = $("#name<?=$userData3['id']; ?>").val();
					var address = $("#address<?=$userData3['id']; ?>").val();
					var country = $("#country<?=$userData3['id']; ?>").val();
                    var state = $("#state<?=$userData3['id']; ?>").val();
					var city = $("#city<?=$userData3['id']; ?>").val();
					var zip = $("#zip<?=$userData3['id']; ?>").val();	
					var alt_mobile = $("#alt_mobile<?=$userData3['id']; ?>").val();	
				
					if(name==''){
					  
						$("#name<?=$userData3['id']; ?>").css({'border':'red 1px solid','background-color':'#eee'});
						document.sign_up<?=$userData3['id']; ?>.name<?=$userData3['id']; ?>.focus();
						return false;
					}
					if(state=='')
                    {
                	   $("#state<?=$userData3['id']; ?>").css({'border':'red 1px solid','background-color':'#eee'});
                	   document.sign_up<?=$userData3['id']; ?>.state<?=$userData3['id']; ?>.focus();
                	   return false;
                    
                	}
                	if(zip=='')
                    {
                	   $("#zip<?=$userData3['id']; ?>").css({'border':'red 1px solid','background-color':'#eee'});
                	   document.sign_up<?=$userData3['id']; ?>.zip<?=$userData3['id']; ?>.focus();
                	   return false;
                    
                	}
					if(address=='')
					{
					  
						$("#address<?=$userData3['id']; ?>").css({'border':'red 1px solid','background-color':'#eee'});
						document.sign_up<?=$userData3['id']; ?>.address<?=$userData3['id']; ?>.focus();
						return false;
					}
					
					
					if(country=='')
					{
					   $("#country<?=$userData3['id']; ?>").css({'border':'red 1px solid','background-color':'#eee'});
					   document.sign_up<?=$userData3['id']; ?>.country<?=$userData3['id']; ?>.focus();
					   return false;
					}
					if(city=='')
					{
					   $("#city<?=$userData3['id']; ?>").css({'border':'red 1px solid','background-color':'#eee'});
					   document.sign_up<?=$userData3['id']; ?>.city<?=$userData3['id']; ?>.focus();
					   return false;
					}
					if(alt_mobile=='')
					{
					   $("#alt_mobile<?=$userData3['id']; ?>").css({'border':'red 1px solid','background-color':'#eee'});
					   document.sign_up<?=$userData3['id']; ?>.alt_mobile<?=$userData3['id']; ?>.focus();
					   return false;
					
					}else{
					   
						$.ajax({
								type: "POST",
								url: "<?= $WebsiteUrl2.'/'; ?>getvalue.php",
								data: {'name':name,'address':address,'country':country,'state':state,'city':city,'zip':zip,'addid':<?=$userData3['id']; ?>,'alt_mobile':alt_mobile},
								cache: false,
								beforeSend: function(){
								
										$( "#subsloader<?=$userData3['id']; ?>" ).show();
									},
									complete: function(){
				
									},
									success: function(data){
										
										setTimeout(function() {                                          
										  $( "#subsloader<?=$userData3['id']; ?>" ).hide();
										  $("#UpdAdd<?=$userData3['id']; ?>").hide();
										  location.reload(); 
									}, 2000);
				
									}
								
							});
					 }
				
				});
				
				
<?php } ?>				
			</script>

</body>
</html>
