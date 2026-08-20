<?php
include('include/config.php');
if(!$admin_user->is_logged_in()){
	redirect(DIR_WS_SITE);           
}
include_once(DIR_FS_SITE."include/classes/prospectClass.php");
include_once(DIR_FS_SITE."include/classes/offersClass.php");
include_once(DIR_FS_SITE."include/classes/settingClass.php");
include_once(DIR_FS_SITE."include/classes/articleClass.php");
include_once(DIR_FS_SITE."include/classes/supplierClass.php");
include_once(DIR_FS_SITE."include/classes/customerClass.php");
include_once(DIR_FS_SITE."include/classes/staffClass.php");
include_once(DIR_FS_SITE."include/classes/notesClass.php");
include_once(DIR_FS_SITE."include/classes/taskClass.php");
include_once(DIR_FS_SITE."include/classes/mailboxClass.php");
include_once(DIR_FS_SITE."include/classes/userClass.php");

/* Get User Permission */
$QueryObj= new users();	
$object=$QueryObj->getUser($admin_user->get_user_id());
$sitePermit=array();
if(!empty($object->permissions)):
	$sitePermit=unserialize(html_entity_decode($object->permissions));	
endif;


$module=isset($_GET['module'])?$_GET['module']:'home';
$view=isset($_GET['view'])?$_GET['view']:'list';
$id=isset($_REQUEST['id'])?$_REQUEST['id']:'0';

require_once(DIR_FS_SITE.'/models/'.$module.'.php');
?>
		
<!DOCTYPE html>
<html lang="en">
<!-- BEGIN HEAD -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>DLM - <?=$pageTitle?></title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/custom.css" rel="stylesheet">
    <link href="assets/plugins/toastr/toastr.min.css" rel="stylesheet">
    <link href="assets/plugins/chosen/css/chosen.css" rel="stylesheet">
    <link href="assets/plugins/tables/css/datatable/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- Date picker plugins css -->
    <link href="assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet">
	
	<!-- LightBox plugins css -->
    <link href="assets/plugins/lightboxWithDownload/lsb.css" rel="stylesheet">	

<?php   /* Get Specific Page Head include  */
        $pageHead = 'others/pageHead/'.$module.".php";
        if(file_exists(DIR_FS_SITE.$pageHead)):
            include_once(DIR_FS_SITE.$pageHead);          
        endif;
?>  

</head>
<body class="h-100 pagebody">
    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
            </svg>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    
    <!--**********************************
        Main wrapper start
    ************************

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Header start
        ***********************************-->
        <?php require_once(DIR_FS_SITE.ADMIN_FOLDER.'/others/top_header.php');  ?>
        
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <?php require_once(DIR_FS_SITE.ADMIN_FOLDER.'/others/left_sidebar.php');  ?>
        
        <!--**********************************
            Sidebar end
        ***********************************-->

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="container-fluid mt-6">               
               
                <div class="row">                                       
                    <div class="centerBody <?=$module?>_<?=$view?>">                        
                        <?php require_once(DIR_FS_SITE.ADMIN_FOLDER.'/tmp/'.$module.'/'.$view.'.php');  ?>
                    </div>
                </div>
                
                
                
           
            <!-- #/ container -->
        </div>
        <!--**********************************
            Content body end
        ***********************************-->
                

                
            </div>
            <!-- #/ container -->
        </div>
        <!--**********************************
            Content body end
        ***********************************-->
         
        
        
        <!--**********************************
            Footer start
        ***********************************-->
        <div class="footer">
            <div class="copyright">
                <p>Copyright &copy; <?=date('Y')?></p>
            </div>
        </div>
        <!--**********************************
            Footer end
        ***********************************-->
    </div>
    <div style="height:0px;overflow:hidden;">
        <div id="htmlPrint"> </div>
    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <script src="assets/plugins/common/common.min.js"></script>
    <script src="assets/plugins/toastr/toastr.min.js"></script>
    <script src="assets/js/custom.min.js"></script>
    
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/gleek.js"></script>
    <script src="assets/js/styleSwitcher.js"></script>
    <script src="assets/plugins/chosen/js/chosen.jquery.js"></script>
    <script src="assets/plugins/chosen/js/chosen_init.js"></script>  

    <?php   /* Get Specific pageFoot include  */
        $pageFoot = 'others/pageFoot/'.$module.".php";
        if(file_exists(DIR_FS_SITE.$pageFoot)):
            include_once(DIR_FS_SITE.$pageFoot);          
        endif;
    ?> 

    <!-- Date Picker Plugin JavaScript -->
    <script src="assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>  
    
    <!-- Html TO Image -->
    <script src="assets/plugins/htmlConvasToImage/html2canvas.min.js"></script>
    <script src="assets/plugins/htmlConvasToImage/html2canvasInt.js"></script>

    <script src="assets/js/custom.js"></script>
	
	
	<script src="assets/plugins/tables/js/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/tables/js/datatable/dataTables.bootstrap4.min.js"></script>
    <script src="assets/plugins/tables/js/datatable-init/datatable-basic.min.js"></script>

    <?php include_once('others/toastr.php');

        /* Get Page Js User Info  */
        $pageJs = 'assets/pageJs/'.$module.".js";
        if(file_exists(DIR_FS_SITE.'assets/pageJs/'.$module.'.js')):
            echo "<script src='".$pageJs."'></script>";          
        endif;
    ?>   

</body>

</html>
