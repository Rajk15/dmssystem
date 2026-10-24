<?php 
include('include/config.php');
require_once(DIR_FS_SITE.'include/classes/newsClass.php');	
require_once(DIR_FS_SITE.'include/classes/sliderClass.php');	
require_once(DIR_FS_SITE.'include/classes/productClass.php');

$QueryObj=new query('setting');
$QueryObj->Where=" where `id`='1' ";
$setting=$QueryObj->DisplayOne();
	
$QueryObj= new slider();	
$sliders=$QueryObj->listAll();
	
$QueryObj= new news();	
$allNews=$QueryObj->listAll();	

$QueryObj=new query('category');		
$QueryObj->Where=" where id!='' and `parent`='0' order by ordering";						  
$parentRecords=$QueryObj->ListOfAllRecords('object');	

$QueryObj=new query('article');		
$QueryObj->Where=" where id!='0' order by ordering";						  
$articles=$QueryObj->ListOfAllRecords('object');

$QueryObj=new query('art_cat');		
$QueryObj->Where="  order by ordering";						  
$artCats=$QueryObj->ListOfAllRecords('object');	

$allCats=array();

if(!empty($parentRecords)):
	foreach($parentRecords as $key=>$vvv):
		$QueryObj=new query('category');		
		$QueryObj->Where=" where id!='' and `parent`='".$vvv->id."' order by ordering";						  
		$productSubCat=$QueryObj->ListOfAllRecords('object');
		if(!empty($productSubCat)):
			foreach($productSubCat as $subkey=>$subValue):	
				$QueryObj=new query('category');		
				$QueryObj->Where=" where id!='' and `parent`='".$subValue->id."' order by ordering";						  
				$productThrdCat=$QueryObj->ListOfAllRecords('object');		
				if(!empty($productThrdCat)):
					foreach($productThrdCat as $thrdkey=>$thrdValue): 	
						$allCats[$vvv->id]['title']=$vvv->title;
						$allCats[$vvv->id]['cats'][]="cat_".$thrdValue->id;
						$allCats[$vvv->id]['data'][]=$thrdValue;
					endforeach;
				else:
					$allCats[$vvv->id]['title']=$vvv->title;
					$allCats[$vvv->id]['cats'][]="cat_".$subValue->id;
					$allCats[$vvv->id]['data'][]=$subValue;
			
				endif;	
			endforeach;
		else:
			$allCats[$vvv->id]['title']=$vvv->title;
			$allCats[$vvv->id]['cats'][]="cat_".$vvv->id;
			$allCats[$vvv->id]['data'][]=$vvv;
		endif;
	endforeach;
endif;

?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$setting->site_title?></title>
    <link href="css/bootstrap.css" type="text/css" rel="stylesheet">
    <link href="css/royal_preloader.css" type="text/css" rel="stylesheet">
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
<body class="royal_loader">
<div class="tp-banner-container" id="slides">
    <div class="tp-banner">
        <ul>
            <?php if(!empty($sliders)):
					foreach($sliders as $kkey=>$slide):?>
					<!-- SLIDE  -->
					<li data-transition="fade" data-slotamount="5" data-masterspeed="700">
						<!-- MAIN IMAGE -->
						<img src="<?=get_image($slide->image,'slider')?>"  data-bgfit="cover" data-bgposition="center center" data-bgrepeat="no-repeat">
						<?php if($slide->small_image_slider!=''):?>
							<div class="tp-caption lft" data-x="center" data-hoffset="0" data-y="50" data-voffset="0" data-speed="800" data-start="700" data-easing="Power4.easeOut" data-endspeed="1000" data-endeasing="Power1.easeIn">
								<img src="<?=get_image($slide->small_image_slider,'slider')?>"/>
							</div>
						<?php endif; ?>	
						<div class="tp-caption main-title lfl" data-x="center" data-hoffset="0" data-y="250" data-speed="500" data-start="1500" data-easing="Power4.easeOut" data-endspeed="1000" data-endeasing="Power1.easeIn">
							<?=$slide->title?>
						</div>
						<!--<div class="tp-caption info-title lfr" data-x="center" data-hoffset="0" data-y="300" data-voffset="0" data-speed="500" data-start="2000" data-easing="Power4.easeOut" data-endspeed="1000" data-endeasing="Power1.easeIn">
							<?=$slide->description?>
						</div>-->
					</li>
				<?php endforeach;
				endif; ?>
        </ul>
        <div class="tp-bannertimer">
        </div>
    </div>
</div>
<div class="top-scroll-bar">
    <a href="#portfolio" class="scroll-down wow pulse" data-wow-iteration="infinite"><i class="fa fa-angle-double-down wow pulse" data-wow-iteration="infinite"></i></a>
</div>
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
                            <a class="sticky-logo" href="#slides">
                                <img src="img/sticky-logo.png" alt=""/>
                            </a>
                        </div>
                        <div class="navbar-collapse collapse ddsmoothmenu" id="smoothmenu1">
                            <ul class="nav navbar-nav pull-right top-nav sf-menu">
                                <li><a href="#slides">Home</a></li>
                                 <li><a href="#portfolio">Portfolio</a></li>
                                
                                <li><a href="#blog">Blog</a>
                                    
                                </li>
                                <li><a href="#contact">Contact</a></li>
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
<div class="section-portfolio" id="portfolio">
<div class="container">
<div class="row ajaxCatCont">
	<?php //include_once('ajax.php');?>
</div>
<div class="row catOnload">
    <div class="col-md-12">
       <div class="section-title">            
            <h2 class="section-subheading-nobg"></h2>
        </div>
    </div>
</div>
<div class="row catOnload">
<div class="col-md-12">
<section>
<!-- THE FILTER STYLED FOR MEGAFOLIO -->
<div class="tags filter-nav">
    <ul>
		<li class="filter selected" data-category="cat-all">ALL</li>	
		<?php foreach($allCats as $kk=>$value): ?>
			<li class="filter" data-category="cat-<?=$kk?>"><?=$value['title']?></li>		
		<?php endforeach; ?>
		<? if(!empty($artCats)): ?>
			<li class="filter" data-category="cat-art">Articles</li>
		<?php endif; ?>	
    </ul>
</div>
<div class="clear">
</div>
<!-- The GRID System -->
<div class="megafolio-container light-bg-entries">
	<?php foreach($allCats as $kk=>$val): ?>
			<!-- A GALLERY ENTRY -->
			<?php foreach($val['data'] as $kkssss=>$value): ?>
				<div class="mega-entry cat-<?=$kk?> cat-all" 
						<?php if(!empty($value->image)): ?>
								data-src="<?=get_image($value->image,'category')?>" data-width="577" 
						<?php else: ?>
								data-src="img/portfolio/image1.jpg" data-width="577" 
						<?php endif; ?>
							data-height="500">
							<div class="mega-hover alone">
								<div class="mega-hovertitle">
									<?=$value->title?>
									
								</div>
								<a onClick="return loadAjaxCatList(<?=$value->id?>);">
									<div class="mega-hoverview">
									</div>
								</a>
							</div>
				</div>	
		
		<?		endforeach;
			endforeach; ?>
	
	
	<? if(!empty($artCats)):
			foreach($artCats as $sub=>$value):?>
				<div class="mega-entry cat-art cat-all" 
						<?php if(!empty($value->image)): ?>
								data-src="<?=get_image($value->image,'art_cat')?>" data-width="577" 
						<?php else: ?>
								data-src="img/portfolio/image1.jpg" data-width="577" 
						<?php endif; ?>
							data-height="500">
							<div class="mega-hover alone">
								<div class="mega-hovertitle">
									<?=$value->title?>									
								</div>
								<a onClick="return loadCatArticles('<?=$value->title?>');">
									<div class="mega-hoverview">
									</div>
								</a>
							</div>
				</div>
	<?php 	endforeach;
		endif; ?>			
</div>
</section>
</div>
</div>
</div>
<!--section portfolio end-->
</div>


<div class="section-works" id="blog">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title">
                   
                    <h2 class="section-subheading-nobg"></h2>
                </div>
            </div>
        </div>
        <div class="blog-container">
			<?php if(!empty($allNews)):
					foreach($allNews as $kkk=>$blog):?>
							<div class="item blogItem <?=($kkk>2)?'hide':''?>">
								<div class="post-content">
									<div class="post-img">
										<?php if(!empty($blog->image)): ?>
											<img src="<?=get_image($blog->image,'news')?>"  alt="Blog">
										<?php else: ?>											
											<img src="img/blog/image1.jpg" alt="Blog">
										<?php endif; ?>	
									</div>
									<h4><?=$blog->title?></h4>									
									<p><?=$blog->description?></p>									
								</div>
							</div>						
			<?php 	endforeach;
				endif; ?>
        </div>
        <?php if(count($allNews)>3):?>
			<div class="row loadIconDiv">
				<div class="col-md-12 btn-load">
					<a onClick="return loadOtherEvent();" class="btn btn-view-all">View All Events</a>
				</div>
			</div>
		<?php endif; ?>	
    </div>
</div>

<div class="section-contact" id="contact">
    
    <div class="contact-map">
       
        <div class="contact-forms ">
            <div class="form-container">
                <h3>Drop me a line</h3>
                <form role="form" >
                    <div class="form-group">
                        <input type="text" class="form-control contact_name" placeholder="Name">
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control contact_email" placeholder="Email Address">
                    </div>
                    <div class="form-group">
                        <textarea rows="4" class="form-control contact_message" cols="50" placeholder="Message"></textarea>
                    </div>
                    <span  class="btn btn-danger btn-contact" onClick="return submitContactForm();">Submit</span>
					<p class='success_message'></p>
                </form>
            </div>
        </div>
        
    </div>
   
    <div class="copyright">
        <div class="container">
            <div class="row">
                <div class="col-md-12 centercopy">
                    &copy; <?=date('Y')?>. All Rights Reserved. Designed & powered by <a href="#">VijayKDahiyon</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="demoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
	<div class="modal-content">
	<div class="modal-header">
	<button type="button" class="close" onClick='return closeVideo();' data-dismiss="modal" aria-hidden="true">&times;</button>

	</div>
	<div class="videoBox">	
		
	</div>
	<div class="modal-footer">
	<button type="button" class="btn btn-default" onClick='return closeVideo();' data-dismiss="modal">Close</button>	
	</div>
	</div>
</div>
</div>

<!--section contact end-->
<!-- jQuery v1.10.2  -->
<script data-cfasync="false" src="cdn-cgi/scripts/d07b1474/cloudflare-static/email-decode.min.js"></script><script src="js/jquery.js"></script>
<script src="js/royal_preloader.js"></script>
<script type="text/javascript">
	
	function loadOtherEvent(){
		$('.loadIconDiv').hide();	
		$('.blogItem').removeClass('hide');
 var $container = $('.blog-container'),
                colWidth = function () {
                    var w = $container.width(),
                        columnNum = 1,
                        columnWidth = 0;
                    if (w > 1200) {
                        columnNum = 3;
                    } else if (w > 900) {
                        columnNum = 3;
                    } else if (w > 600) {
                        columnNum = 3;
                    } else if (w > 300) {
                        columnNum = 1;
                    }
                    columnWidth = Math.floor(w / columnNum);
                    $container.find('.item').each(function () {
                        var $item = $(this),
                            multiplier_w = $item.attr('class').match(/item-w(\d)/),
                            multiplier_h = $item.attr('class').match(/item-h(\d)/),
                            width = multiplier_w ? columnWidth * multiplier_w[1] - 20 : columnWidth - 20;
                        //                                height = multiplier_h ? columnWidth*multiplier_h[1]*0.5-4 : columnWidth*0.5-4;
                        $item.css({
                            width: width
                            //                            height: height
                        });
                    });
                    return columnWidth;
                },
                isotope = function () {
                    $container.isotope({
                        resizable: false,
                        itemSelector: '.item',
                        masonry: {
                            columnWidth: colWidth()
                            //                            gutterWidth: 10
                        }
                    });
                };
            isotope();
            $(window).smartresize(isotope);
            $(window).load(isotope);
	}
	
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
		$('.ajaxCatCont').html("<div style='text-align:center;'><img src='img/AjaxLoader.gif'/></div>");
		$('.ajaxCatCont').show(300);
		$('.catOnload').hide(300);
		
		var data ='artCat=' + artCat;
		var url= 'ajax/ajax.php?action=catArticle';
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
	


    var images = {
        'Banner One':     'http://rxhtml.themebucket.net/img/banner1.jpg',
        'Banner Two':     'http://rxhtml.themebucket.net/img/banner2.jpg',
        'Banner Three':     'http://rxhtml.themebucket.net/img/banner3.jpg',
        'Theme Bucket':  'http://rxhtml.themebucket.net/img/p1.jpg',
        'About Me':     'http://rxhtml.themebucket.net/img/about-bg.jpg',
        'Experience':   'http://rxhtml.themebucket.net/img/experience-bg.jpg',
        'Connect Monitor': 'http://rxhtml.themebucket.net/img/connect-monitor.png'
    };
    // config
    Royal_Preloader.config({
        mode:       'number',
        images:     images,
        timeout:    20,
        showInfo:   true,
        background: ['#424242']
    });
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

function showMe(pid){
	$('#demoModal').modal('show');
	$('.videoBox').html("<div style='text-align:center;'><img src='img/AjaxLoader.gif'/></div>");
	var data ='pid=' + pid;
		var url= 'ajax/ajax.php?action=local_video';
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: 'success',
            success:function (success) { 
                $('.videoBox').html(success);
            }
        });		
}

function closeVideo(){ 
	$('.videoBox').html('');
}
</script>
</body>

</html>
