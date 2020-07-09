<?php
error_reporting(0);
include'functions/db.class.php';
if(isset($_POST['product_cat']))
{


 $state = $_POST['product_cat'];
 echo '<option value="">-------Select Sub Department-----</option>';
 
 $find=$conn->prepare("select id,name_eng,name_arb from subdepartment where department_id='$state' order by name_eng asc");
 $find->execute();
 while($nBlog=$find->fetch(PDO::FETCH_ASSOC))
 {
	 ?>
  <option value="<?php echo $nBlog['id'];  ?>"><?php echo $nBlog['name_eng'].'('.$nBlog['name_arb'].')';  ?></option>
  <?php
 }

}
if($_POST['q']==1)
{
?>
<div class="form-group">
											<label>Department</label>
												<select class="form-control" name="did" onchange="fetch_select2(this.value);" required>
												   <option value="">Select Department</option>
													  <?php 
												
												$getCat = $conn->query("SELECT * FROM `department` ORDER BY `id` asc");
												while($nBlog = $getCat->fetch(PDO::FETCH_ASSOC)){
												
												?>
												
												<option value="<?php echo $nBlog['id'];  ?>"><?php echo $nBlog['name_eng'].'('.$nBlog['name_arb'].')';  ?></option>
												
												<?php } ?>
											
											
												</select>
                               				 </div>
											 
<div class="form-group">
											<label>Sub Department</label>
												<select class="form-control" name="sid"  id="new_select2">
								  					<option value="">Select Sub Department</option>
											
												</select>
                               				 </div>
<?php
}if($_POST['q']==2)
{
?>
<div class="form-group">
											<label>Receipe</label>
												<select class="form-control" name="rid" required>
												   <option value="">Select Receipe</option>
													  <?php 
												
												$getCat = $conn->query("SELECT * FROM `shop_receipe` ORDER BY `id` asc");
												while($nBlog = $getCat->fetch(PDO::FETCH_ASSOC)){
												
												?>
												
												<option value="<?php echo $nBlog['id'];  ?>"><?php echo $nBlog['name_eng'].'('.$nBlog['name_arb'].')';  ?></option>
												
												<?php } ?>
											
											
												</select>
                               				 </div>

<?php }if($_POST['qq']==1)
{
?>
<div class="form-group">
											<label>Department</label>
												<select class="form-control" name="did" onchange="fetch_select2(this.value);" required>
												   <option value="">Select Department</option>
													  <?php 
												
												$getCat = $conn->query("SELECT * FROM `department` ORDER BY `id` asc");
												while($nBlog = $getCat->fetch(PDO::FETCH_ASSOC)){
												
												?>
												
												<option <?php if($_POST['depart']==$nBlog['id']){ echo 'selected'; } ?> value="<?php echo $nBlog['id'];  ?>"><?php echo $nBlog['name_eng'].'('.$nBlog['name_arb'].')';  ?></option>
												
												<?php } ?>
											
											
												</select>
                               				 </div>
											 
<div class="form-group">
											<label>Sub Department</label>
												<select class="form-control" name="sid"  id="new_select2">
								  					<option value="">Select Sub Department</option>
													 <?php 
												
												$getCat2 = $conn->query("SELECT * FROM `subdepartment` where department_id='".$_POST['depart']."' ORDER BY `id` asc");
												while($nBlog2 = $getCat2->fetch(PDO::FETCH_ASSOC)){
												
												?>
												
												<option <?php if($_POST['subdepart']==$nBlog2['id']){ echo 'selected'; } ?> value="<?php echo $nBlog2['id'];  ?>"><?php echo $nBlog2['name_eng'].'('.$nBlog2['name_arb'].')';  ?></option>
												
												<?php } ?>
												</select>
                               				 </div>
<?php
}if($_POST['qq']==2)
{
?>
<div class="form-group">
											<label>Receipe</label>
												<select class="form-control" name="rid" required>
												   <option value="">Select Receipe</option>
													  <?php 
												
												$getCat = $conn->query("SELECT * FROM `shop_receipe` ORDER BY `id` asc");
												while($nBlog = $getCat->fetch(PDO::FETCH_ASSOC)){
												
												?>
												
												<option <?php if($_POST['recep']==$nBlog['id']){ echo 'selected'; } ?> value="<?php echo $nBlog['id'];  ?>"><?php echo $nBlog['name_eng'].'('.$nBlog['name_arb'].')';  ?></option>
												
												<?php } ?>
											
											
												</select>
                               				 </div>

<?php }

?>