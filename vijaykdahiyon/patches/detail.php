<?php 
include('include/config.php');
require_once(DIR_FS_SITE.'include/classes/newsClass.php');	
require_once(DIR_FS_SITE.'include/classes/sliderClass.php');	
require_once(DIR_FS_SITE.'include/classes/productClass.php');

$catid=(isset($_REQUEST['cat']))?$_REQUEST['cat']:'0';
$id=(isset($_REQUEST['id']))?$_REQUEST['id']:'0';
$artId=(isset($_REQUEST['artId']))?$_REQUEST['artId']:'0';
$pid=(isset($_REQUEST['pid']))?$_REQUEST['pid']:'0';
$front=(isset($_REQUEST['front']))?$_REQUEST['front']:'0';
$artCat=(isset($_REQUEST['artCat']))?$_REQUEST['artCat']:'';
$art=(isset($_REQUEST['art']))?$_REQUEST['art']:'0';

if($art==1):
	$artId=$id;
endif;

if(!empty($artId)):
	$QueryObjS=new query('article');		
	$QueryObjS->Where=" where id='".$id."' ";	
	$sglPrd=$QueryObjS->DisplayOne();
else:
	$QueryObjS=new query('product');		
	$QueryObjS->Where=" where id='".$id."' ";	
	$sglPrd=$QueryObjS->DisplayOne();
endif;	

$action=(isset($_REQUEST['action']))?$_REQUEST['action']:'categories';
$prdouctResult=array(1,4,3,4,5,8,5,8);

$QueryObj=new query('category');		
$QueryObj->Where=" where id!='' and `parent`='0' order by ordering";						  
$productParentCat=$QueryObj->ListOfAllRecords('object');

$QueryObj=new product();
$proRec=$QueryObj->catListAll($catid);

$QueryObj=new query('setting');		
$QueryObj->Where=" where id='1' ";						  
$singleSetting=$QueryObj->DisplayOne();

$superPar=array();
$QueryObj=new query('category');		
$QueryObj->Where=" where id='".$catid."' ";						  
$catDet=$QueryObj->DisplayOne();
if(is_object($catDet)):	
	if($catDet->parent!='0'):	
			$superPar[$catDet->parent]=$catDet->parent;
			$QueryObj=new query('category');		
			$QueryObj->Where=" where id='".$catDet->parent."' ";						  
			$catSubDet=$QueryObj->DisplayOne();
			$superPar[$catSubDet->parent]=$catSubDet->parent;			
	endif;
endif;


$QueryObj=new query('art_cat');		
$QueryObj->Where="  order by ordering";						  
$artCatRec=$QueryObj->ListOfAllRecords('object');		


$productCat=array();
foreach($productParentCat as $key=>$value):
		// sub_cat must be an array — an empty string breaks child assignment on PHP 8+
		// and leaves Painting (and other parents) with an empty dropdown submenu.
		$productCat[$value->id]=array(
									'id'=>$value->id,
									'title'=>$value->title,
									'sub_cat'=>array()
									);	

		$QueryObj=new query('category');		
		$QueryObj->Where=" where id!='' and `parent`='".$value->id."' order by ordering";						  
		$productSubCat=$QueryObj->ListOfAllRecords('object');	
		
		if(!empty($productSubCat)):
			foreach($productSubCat as $key=>$object):
				
				$QueryObj=new query('category');		
				$QueryObj->Where=" where `parent`='".$object->id."' order by ordering";	
				$productthirdCat=$QueryObj->ListOfAllRecords('object');				
				$productCat[$value->id]['sub_cat'][$object->id]=array(
											'id'=>$object->id,
											'title'=>$object->title,
											'sub_cat'=>(!empty($productthirdCat))?$productthirdCat:array()
										);
			endforeach;	
		endif;	
endforeach;

?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$sglPrd->title?></title>
    <!-- You can use Open Graph tags to customize link previews.
    Learn more: https://developers.facebook.com/docs/sharing/webmasters -->
	<meta property="og:url"           content="<?=DIR_WS_SITE.'detail.php?id='.$sglPrd->title.'&cat='.$catid?>" />
	<meta property="og:type"          content="website" />
	<meta property="og:title"         content="<?=$sglPrd->title?>" />
	
	<? if($art==1): ?>
		<meta property="og:image"         content="<?=getProductImg($sglPrd->image,'article')?>" />	
	<? else:?>
		<meta property="og:description"   content="<?=$sglPrd->description?>" />
		<meta property="og:image"         content="<?=getProductImg($sglPrd->image,'product')?>" />	
	<? endif ;?>
	
    <link href="css/bootstrap.css" type="text/css" rel="stylesheet">
    
    <link href="css/animate.css" type="text/css" rel="stylesheet">
    <link href="css/prettify.css" type="text/css" rel="stylesheet">
    <link href="css/rev-slider-settings.css" type="text/css" rel="stylesheet">
    <!-- MEGAFOLIO GALLERY  CSS -->
    <link href="css/megafolio-style.css" type="text/css" rel="stylesheet">
    <link href="css/megafolio-settings.css" type="text/css" rel="stylesheet">
    <!-- Magnific Popup core CSS file -->
    <link href="css/magnific-popup.css" type="text/css" rel="stylesheet">
    <!-- Owl Carousel Assets -->
    <link href="css/owl.carousel.css" type="text/css" rel="stylesheet">
    <link href="css/owl.theme.css" type="text/css" rel="stylesheet">
    <link href="css/owl.transitions.css" type="text/css" rel="stylesheet">
    <link href="css/font-awesome.css" type="text/css" rel="stylesheet">
    <link href="css/superfish.css" type="text/css" rel="stylesheet">
    <link href="css/style.css" type="text/css" rel="stylesheet">
    <link href="css/style-responsive.css" type="text/css" rel="stylesheet">
    <link href="css/apps-reset.css" type="text/css" rel="stylesheet">
    <link href="css/default-theme.css" type="text/css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Raleway:900,300,200,100' rel='stylesheet' type='text/css'>
</head>
<body class="">

<!--home section end -->
<div class="navigation clearfix">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="navbar navbar-static-top">
                    <div>
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                <span class="fa fa-bars"></span>
                            </button>
                            <a class="sticky-logo" href="https://vijaykdahiyon.com/">
                                <img src="img/sticky-logo.png" alt=""/>
                            </a>
                        </div>
                        <div class="navbar-collapse collapse ddsmoothmenu" id="smoothmenu1">
                            <ul class="nav navbar-nav pull-right top-nav sf-menu">
                                <li><a class="external" href="https://vijaykdahiyon.com/#slides">Home</a></li>
                                <li><a class="external" href="https://vijaykdahiyon.com/#portfolio">Portfolio</a></li>
                                <li><a class="external" href="https://vijaykdahiyon.com/#blog">Blog</a></li>
                                <li><a class="external" href="https://vijaykdahiyon.com/#contact">Contact</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="clear">
</div>
<!-- about me section end-->


<!--section portfolio start-->
<div class="section-portfolio" id="" style="padding-top:0px;">
<div class="container">
<div class="row ajaxCatCont">
	<?php //include_once('ajax.php');?>
</div>

<?php
if($action=='categories'):?>
<div class="col-md-3" style="padding:0px;margin-bottom:30px;">	

	<h4 class="mainhd"><a href="index.php" style="color:#424242;">Go Back</a></h4>		
  <div id="MainMenu">
<div class="list-group panel navbg">
	<?php 
	 if(!empty($productCat)): 
		
		foreach($productCat as $kk=>$vv):
			if(!empty($vv['sub_cat'])):				
				echo '<a href="#SubMenu'.$vv['id'].'" class="list-group-item fristLabel t1" data-toggle="collapse" data-parent="#MainMenu">'.$vv['title'].' <i class="fa fa-caret-down"></i></a>';
						echo '<div class="list-group-submenu t2 collapse ' .((in_array($vv['id'],$superPar))?'in':'').'  " id="SubMenu'.$vv['id'].'">';
							
						foreach($vv['sub_cat'] as $sub=>$s_val):
							if(!empty($s_val['sub_cat'])):
														
								//echo '<li class="has-children">
								//<input type="checkbox"  '.((in_array($s_val['id'],$superPar))?'checked':'').' name ="group-'.$kk.$sub.'" id="group-'.$kk.$sub.'" checked/>
								//<label for="group-'.$kk.$sub.'">'.$s_val['title'].'</label>';
								
								echo '<a href="#SubMenu'.$s_val['id'].'" class="list-group-item t2 SecondLabel" data-toggle="collapse" data-parent="#SubMenu'.$vv['id'].'">'.$s_val['title'].' <i class="fa fa-caret-down"></i></a>';
								echo '<div class="list-group-submenu collapse '.((in_array($s_val['id'],$superPar))?'in':'').'  " id="SubMenu'.$s_val['id'].'">';
								foreach($s_val['sub_cat'] as $thrd_key=>$th_val):
									$th_val=(array)$th_val;
									//echo "<li onClick='return loadProducts(".$th_val['id'].");' class=' cats sgl_".$vv['id']." ".(($th_val['id']==$catid)?'current':'')."'><a>".$th_val['title']."</a></li>";
									echo "<a onClick='return loadProducts(".$th_val['id'].");' data-parent='#SubMenu".$s_val['id']."' class='list-group-item list-group-item-success thirdLabel t3 sgl_".$th_val['id']." ".(($th_val['id']==$catid)?'active':'')."'>".$th_val['title']."</a>";
								endforeach;				
								echo '</div>';									
							else:
								//echo "<li onClick='return loadProducts(".$s_val['id'].");' class='cats sgl_".$vv['id']." ".(($s_val['id']==$catid)?'current':'')."'><a>".$s_val['title']."</a></li>";
								echo "<a onClick='return loadProducts(".$s_val['id'].");' data-parent='#SubMenu".$vv['id']."' class='list-group-item t2 secondLabel list-group-item-success sgl_".$s_val['id']." ".(($s_val['id']==$catid)?'active':'')."'>".$s_val['title']."</a>";
							endif;
						endforeach;
				echo "</div>";											
			else:
				echo "<a onClick='return loadProducts(".$vv['id'].");' data-parent='#MainMenu' class='list-group-item fristLabel list-group-item-success sgl_".$vv['id']." ".(($vv['id']==$catid)?'active':'')."'>".$vv['title']."</a>";
			endif;
		endforeach;		
	endif;
	
	if(!empty($artCatRec)):
			echo '<a href="#SubMenu-art" class="list-group-item fristLabel t1" data-toggle="collapse" data-parent="#MainMenu">Articles <i class="fa fa-caret-down"></i></a>';
				echo '<div class="list-group-submenu t2 collapse ' .((!empty($art))?'in':'').'  " id="SubMenu-art">';
					foreach($artCatRec  as $sub=>$s_val):
						echo "<a onClick='return loadCatArticles(`".$s_val->title."`);' data-parent='#SubMenu-art' class='list-group-item t2 secondLabel list-group-item-success sgl_".$s_val->id." ".(($s_val->title==$artCat)?'active':'')."'>".$s_val->title."</a>";
					endforeach;	
				echo "</div>";	
	endif;
	
?>
</div>		
</div>	
</div>


<div class="col-md-9 ajaxOnlyProduct"> 
	<div class="singleProduct bt30 ">
		<? if($art=='1'): ?>
<div class=" col-md-12 bord"><div class=" col-md-12"><div class=" col-md-4">	</div>
				<div class=" col-md-4">
<div class="arttit"><?=$sglPrd->title?></div></div><div class=" col-md-4"></div></div>
			<p style="text-align:center;"><?=$sglPrd->subtitle?></p>					
			<?=html_entity_decode($sglPrd->description)?></div>	
		<? else: ?>
			<?php if(!empty($sglPrd->image)): ?>							
				<img src="<?=get_image($sglPrd->image,'product')?>" style='width:100%;' class=""/>
			<?php else: ?>	
				<img src="img/portfolio/image2.jpg" style='width:100%;' class="imgheightdetail"/>
			<?php endif; ?>		
		<? endif; ?>
	</div>
	<div class="row">
	<div class="col-md-8">
		<p class="boldtxt">Watch More artworks</p>
	<?php if(!empty($proRec)):
			foreach($proRec as $key=>$prod):?>
				<div class="col-md-4 singleProduct bt15 " style="padding-left:0px;">

					<?php if(!empty($prod->image)): ?>							
							<?php if($prod->local_video==1): ?>
								<a data-caption="<?=$prod->title?>" onClick='return showMe(<?=$prod->id?>)' title="<?=$prod->title?>">
							<?php elseif(!empty($prod->product_link)): ?>
								<a href="<?=DIR_WS_SITE.'detail.php?id='.$prod->id.'&cat='.$catid?>" title="<?=$prod->title?>">
							<?php else: ?>
								<a href="<?=DIR_WS_SITE.'detail.php?id='.$prod->id.'&cat='.$catid?>" title="<?=$prod->title?>">
							<?php endif; ?>
							
							<img src="<?=getProductImg($prod->image,'product')?>" style='width:100%;' class=""/>
							</a>
						<?php else: ?>	
							<img src="img/portfolio/image2.jpg" style='width:100%;' class=""/>
						<?php endif; ?>

					<div class="mega-hoverview"></div>		
				</div>
				<!-- Modal -->
								
	<?php 	endforeach;
		  else: ?>
			<p>Sorry No Record Found.!</p>
	<?php endif; ?>
	</div>
	<div class="col-md-4" style="padding-left:0px;">

		<p class="boldtxt">Share With Friends</p>
		<div class="social">
			<div class="col-md-2">
				<div class="row">
					<a target='_blank' href="http://www.facebook.com/sharer.php?u=<?=urlencode(DIR_WS_SITE.'detail.php?id='.$sglPrd->id.'&cat='.$catid)?>&p[title]=<?=urlencode($sglPrd->title)?>">
						<img src="img/facebook1.jpg" class="img-responsive"/>
					</a>
				</div>
			</div>
			<div class="col-md-2">
				<div class="row">				
					<a target='_blank' href="http://www.twitter.com/sharer.php?text=<?=urlencode($sglPrd->title)?>&url=<?=urlencode(DIR_WS_SITE.'detail.php?id='.$sglPrd->id.'&cat='.$catid)?>">
						<img src="img/twitter.jpg" class="img-responsive"/>
					</a>
				</div>
			</div>
			<div class="col-md-2">
				<div class="row">
					<a target='_blank' href="http://pinterest.com/pin/create/button/?url=<?=urlencode(DIR_WS_SITE.'detail.php?id='.$sglPrd->id.'&cat='.$catid)?>&description=<?=urlencode($sglPrd->title)?>">
						<img src="img/pin.jpg" class="img-responsive"/>
					</a>
				</div>
			</div>
		</div>
		
		<p class="boldtxt">Write a comment</p>
		<textarea id='comment' class="commentrow" placeholder="Write Your Comment"></textarea>
		<button class='pull-right' onClick='submitComment(<?=$id?>)'>Submit</button>
		<p class='success_msg'></p>
		
		
		
	</div></div>
<div class="row"><div class="col-md-12"><h4 class="boldtxt">Comments</h4></div></div>
 


<?php 			
			$QueryObj= new query('product_comment');
			$QueryObj->Where="Where `product_id`='".$id."' AND `approve`=1";
			$records=$QueryObj->ListOfAllRecords('object');	
			
			if(!empty($records)):
				foreach($records as $kk=>$cmt):
                                        
                                        echo"<div class='col-md-12 comst'>";
echo"<div class='row'>";
                                        echo"<div class='col-md-1'><img src='img/comment.jpg' class='img-responsive'> </div>";
					echo "<div class='col-md-11'>";
                                    echo "<div class='row pdtop10'>".$cmt->comment."</div>";
                                        echo"</div>";
					echo"</div>";
                                        echo"</div>";
				endforeach;	
			else:
				echo '<p>No comment found..!</p>';
			endif;
		?>	</div></div>
	</div>	
</div>

		
				
<?
endif; 
?>
<!-- 1. Add latest jQuery and fancyBox files -->
<!--<a onClick='return showMe(10)'>Click</a> -->
<style>
.list-group-submenu {
    padding-left: 0px;
    text-transform: capitalize;
font-weight:normal;
}

.panel{margin-bottom:0px;}
.list-group-submenu a{padding-left:30px;font-weight: normal;}
</style>
<!--<script src="//code.jquery.com/jquery-3.2.1.min.js"></script> -->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.js"></script>

<!--[if lt IE 9]>
          <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->


</div>
<!--section portfolio end-->
</div>


<div id="fb-root"></div>
  <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));</script>



<!--section contact end-->
<!-- jQuery v1.10.2  -->
<script src="js/jquery.js"></script>
<script src="js/royal_preloader.js"></script>
<script type="text/javascript">

	function loadAjaxCatList(catId){		
		$('.ajaxCatCont').html("<div style='text-align:center;'><img src='img/AjaxLoader.gif'/></div>");
		$('.ajaxCatCont').show(300);
		$('.catOnload').hide(300);
		
		var data ='cat_id=' + catId;
		var url= 'ajax/ajax.php?action=categories';
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: 'success',
            success:function (success) { 
                $('.ajaxCatCont').html(success);				
				var etop = $('#portfolio').offset().top;
				$(window).scrollTop(etop);		
            }
        });
	}
	
	function loadProducts(catId){
		$('.ajaxOnlyProduct').html("<div style='text-align:center;'><img src='img/AjaxLoader.gif'/></div>");
		$('.ajaxOnlyProduct').show(300);
		$('.cats').removeClass('current');
		$('.sgl_'+catId).addClass('current');
		
		var data ='cat_id=' + catId;
		var url= 'ajax/ajax.php?action=product';
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: 'success',
            success:function (success) { 
                $('.ajaxOnlyProduct').html(success);
            }
        });
	}
	
	function loadArticle(artId){
		$('.ajaxOnlyProduct').html("<div style='text-align:center;'><img src='img/AjaxLoader.gif'/></div>");
		$('.ajaxOnlyProduct').show(300);
		$('.cats').removeClass('current');
		$('.sgl_'+artId).addClass('current');
		
		var data ='artId=' + artId;
		var url= 'ajax/ajax.php?action=article';
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: 'success',
            success:function (success) { 
                $('.ajaxOnlyProduct').html(success);
            }
        });
	}
	
	function loadCatArticles(artCat){
		$('.ajaxOnlyProduct').html("<div style='text-align:center;'><img src='img/AjaxLoader.gif'/></div>");
		//$('.ajaxOnlyProduct').show(300);
		//$('.ajaxOnlyProduct').hide(300);
		
		var data ='artCat=' + artCat;
		var url= 'ajax/ajax.php?action=catArticleSgl';
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: 'success',
            success:function (success) { 
                $('.ajaxOnlyProduct').html(success);				
				var etop = $('#portfolio').offset().top;
				$(window).scrollTop(etop);	
            }
        });
	}	
	
	function submitContactForm(){				
		
		var contact_name=$('.contact_name').val();
		var email=$('.contact_email').val();
		var contact_message=$('.contact_message').val();
		if(contact_name==''){	$('.contact_name').css('border-color', 'red'); return false;	}
		else{	$('.contact_name').css('border-color', '#ccc'); }
		
		if(email==''){	$('.contact_email').css('border-color', 'red');	return false; }
		else{	$('.contact_email').css('border-color', '#ccc');	}
		
		if(contact_message==''){	$('.contact_message').css('border-color', 'red'); return false;	}
		else{	$('.contact_message').css('border-color', '#ccc');	}

		$('.success_message').html("<div style='text-align:center;'><img src='img/AjaxLoader.gif'/></div>");
		
		var data ='name=' + contact_name+'&email='+email+'&message='+contact_message;
		var url= 'ajax/ajax.php?action=message';
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: 'success',
            success:function (success) { 
				$('.contact_name').val('');
				$('.contact_email').val('');
				$('.contact_message').val('');
				$('.success_message').html("<div style='text-align:center;color:green'>Thanks for your request</div>");
				$('.btn-contact').hide();
            }
        });
	}
	

</script>
<!-- Bootstrap v3.1.0-->
<script src="js/bootstrap.js"></script>
<script src="js/jquery-ui-1.10.4.custom.min.js"></script>
<!-- jQuery REVOLUTION Slider -->
<script src="js/jquery.revslider.plugins.min.js"></script>
<script src="js/jquery.themepunch.revolution.js"></script>
<!-- Scroll Top-->
<script src="js/jquery.scrollUp.js"></script>
<!-- Easing Effect-->
<script src="js/jquery.easing.js"></script>
<!-- Knob-->
<script src="js/jquery.easypiechart.js"></script>
<!-- One Page Nav-->
<script src="js/jquery.nav.js"></script>
<!-- Scroll To-->
<script src="js/jquery.scrollTo.js"></script>
<!-- Sticky Nav-->
<script src="js/waypoints.js"></script>
<script src="js/waypoints-sticky.js"></script>
<!-- Elements Animation On Scroll -->
<script src="js/wow.js"></script>
<!-- jQuery Fittext -->
<script src="js/jquery.fittext.js"></script>
<!-- Appear Animation -->
<script src="js/jquery.appear.js"></script>
<!-- Masonry Grid -->
<script src="js/jquery.isotope.js"></script>
<!-- Smooth Scroll Effect -->
<script src="js/jquery.smooth-scroll.js"></script>

<!-- MEGAFOLIO GALLERY  JS FILES  -->
<script src="js/jquery.megafolio.plugins.min.js"></script>
<script src="js/jquery.themepunch.megafoliopro.js"></script>
<!-- Magnific Popup core JS file -->
<script src="js/jquery.magnific-popup.js"></script>
<!-- jQuery OwlCarousel -->
<script src="js/owl.carousel.js"></script>
<!-- Drop Down Menu -->
<script src="js/hoverIntent.js"></script>
<script src="js/superfish.js"></script>
<!-- Syntax Highlighter -->
<script src="js/prettify.js"></script>
<!-- Flickr Feed -->
<script src="js/jflickrfeed.js"></script>
<!-- Plugins Initialize Scripts -->
<script src="js/scripts.js?v=20261024"></script>
<script>

function submitComment(pid){
	var cmt=$('#comment').val();
	if(cmt==''){
		$('#comment').focus();
		return false;
	}
	var data='product_id='+pid+'&comment='+cmt;
	var url= 'ajax/ajax.php?action=comment';
	$.ajax({
		url: url,
		type: 'POST',
		data: data,
		success: 'success',
		success:function (success) { 
			$('.success_msg').html('Thanks for your message, we will review this.');
			$('#comment').val('');
		}
	});		
}



function closeVideo(){ 
	$('.videoBox').html('');
}
</script>
</body>

</html>
