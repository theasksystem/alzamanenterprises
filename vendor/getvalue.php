<?php


if(isset($_POST['tab']))
{
 include 'functions/db.class.php';

 $tab_id = $_POST['tab'];
 
   
    echo '<option value="">--Select--</option>';
     
     $query = $conn->prepare("select * from  sub_department WHERE `did` = '$tab_id' AND `visible`=1 ORDER BY `name_en` ASC");
	 $query->execute();
	 
     while ($row = $query->fetch(PDO::FETCH_ASSOC))
     {
 
?>
    <option value="<?php echo $row['id'];  ?>"><?php echo $row['name_en'].'('.$row['name_ar'].')';  ?></option>
    
<?php } exit; } ?>

<?php

if(isset($_POST['cat']))
{
 include 'functions/db.class.php';

 $cat = $_POST['cat'];
 
 
     echo '<option value="">--Select--</option>';
     
     $query = $conn->prepare("select * from  sub_cat where `sid` = '$cat' AND visible=1 ORDER BY `name_en` ASC");
	 $query->execute();
	 
     while ($row = $query->fetch(PDO::FETCH_ASSOC))
     {
 
?>
    <option value="<?php echo $row['id'];  ?>"><?php echo $row['name_en'].'('.$row['name_ar'].')';  ?></option>
    
<?php } exit; } ?>

<?php

if(isset($_POST['country']))
{
 include 'functions/db.class.php';

 $cat = $_POST['country'];
 
 
     echo '<option value="">--Select--</option>';
     
     $query = $conn->prepare("select * from  state where `country_id` = '$cat' AND visible=1 ORDER BY `name_en` ASC");
	 $query->execute();
	 
     while ($row = $query->fetch(PDO::FETCH_ASSOC))
     {
 
?>
    <option value="<?php echo $row['id'];  ?>"><?php echo $row['name_en'].'('.$row['name_ar'].')';  ?></option>
    
<?php } exit; } ?>
