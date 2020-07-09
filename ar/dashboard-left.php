<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
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
          <div class="_3buEbR text-right">مرحبا,</div>
          <div class="text-right"><?=$userData['name'].' '.$userData['lastname']; ?></div>
          </div>
          </div>
          </div>
        </div>
        
        <!-- left acordion -->
        <ul id="accordion" class="accordion  text-right">
          <li>
            <div class="link"><a href="<?= $WebsiteUrl2.'/'; ?>profile">حسابي</a></div>
            
          </li>
          <li>
            <div class="link"><a href="<?= $WebsiteUrl2.'/'; ?>dashboard">الطلبات</a></div>
            
          </li>
          <li>
            <div class="link"><a href="<?= $WebsiteUrl2.'/'; ?>wishlist">المفضلات</a></div>
            
          </li>
          <li>
            <div class="link"><a href="<?= $WebsiteUrl2.'/'; ?>address">عناويني</a></div>
            
          </li>
          <li>
            <div class="link"><a href="<?= $WebsiteUrl2.'/'; ?>logout.php">تسجيل الخروج</a></div>
            
          </li>
        </ul>
      </div>
    </div>