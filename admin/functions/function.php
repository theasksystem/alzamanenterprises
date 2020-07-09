<?php

include('db.class.php');



function marquee_events()
{	
  $conndb = new db();	
  $marquee = $conndb->getAll("SELECT * from nimc_events where type_events='0' and visible='yes' order by id desc"); 	
  $output ='';
  foreach($marquee as $events_sql_detail) 
  { 
	 $output.='<li><img src="images/new-new.png" alt="'.$events_sql_detail['nimc_title'].'" title="'.$events_sql_detail['nimc_title'].'" /><a href="article_detail.php?id='.base64_encode(     $events_sql_detail['id']).'" alt="'.$events_sql_detail['nimc_title'].'" title="'.$events_sql_detail['nimc_title'].'">'.$events_sql_detail['title_events'].'</a></li>';
  }
  
 return $output;

}


function news_events()
{
  $conndb = new db();	
  $events = $conndb->getAll("SELECT * from nimc_events where type_events='1' and visible='Yes' order by id desc"); 	
  $output ='';
  foreach($events as $events_sql_detail) 
  { 
      if($events_sql_detail['detail_events']!="")
	  {
	    $output.='<p><img src="images/new-new.png" alt="'.$events_sql_detail['nimc_title'].'" title="'.$events_sql_detail['nimc_title'].'" />'.$events_sql_detail['title_events'].'</p>';
	  }
	  else
	  {
	     $output.='<p><img src="images/new-new.png" alt="'.$events_sql_detail['nimc_title'].'" title="'.$events_sql_detail['nimc_title'].'" /> <span alt="'.$events_sql_detail['nimc_title'].'"         title="'.$events_sql_detail['nimc_title'].'">'.$events_sql_detail['title_events'].'</span> </p>';
	  }
  }
  
 return $output;

}



function articles()
{
  $conndb = new db();	
  $events = $conndb->getAll("select * from nimc_events where type_events='0' and visible='yes'"); 	
  $output ='';
  foreach($events as $events_sql_detail) 
  {  
	 $output.='<li><a href="article_detail.php?id='.base64_encode($events_sql_detail['id']).'">'.$events_sql_detail['title_events'].'</a></li>';
  }
  
 return $output;

}



function article_detail($aid)
{
		
  $conndb = new db();	
  $id = $aid;
  return $conndb->getOne("select * from nimc_events where id = '$id' and visible='yes'"); 	

}




