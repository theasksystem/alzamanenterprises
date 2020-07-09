<div class="col-md-3 col-sm-12 col-xs-12">
      <div class="product-in">
        <div class="headline">
          <div class="_1GRhLX">
          <div class="-yAF57 row">
          <?php if($userData['image']!=''){ ?>
          <img class="fUkK-z" height="50px" width="50px" src="<?= $WebsiteUrl.'/'; ?>adminuploads/user/<?php echo $userData['image']; ?>">
          <?php }else{ ?>
          <img class="fUkK-z" height="50px" width="50px" src="<?= $WebsiteUrl.'/'; ?>images/dashboard.svg">
          <?php } ?>
          <div class="M6fKa7">
          <div class="_3buEbR">Hello,</div>
          <div class=""><?=$userData['name'].' '.$userData['lastname']; ?></div>
          </div>
          </div>
          </div>
        </div>
        
        <!-- left acordion -->
        <ul id="accordion" class="accordion">
          <li>
            <div class="link"><a href="<?= $WebsiteUrl.'/'; ?>profile">My Profile</a></div>
            
          </li>
          <li>
            <div class="link"><a href="<?= $WebsiteUrl.'/'; ?>dashboard">Order History</a></div>
            
          </li>
          <li>
            <div class="link"><a href="<?= $WebsiteUrl.'/'; ?>wishlist">Wishlist</a></div>
            
          </li>
          <li>
            <div class="link"><a href="<?= $WebsiteUrl.'/'; ?>address">Delivery Addresses</a></div>
            
          </li>
          <li>
            <div class="link"><a href="logout.php">Logout</a></div>
            
          </li>
        </ul>
      </div>
    </div>