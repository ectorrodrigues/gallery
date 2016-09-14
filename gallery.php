<?php
	// -------------------------------- SETTINGS --------------------------------
	
	include 'inc/connect.php'; // DB Connection file
	
	$id_products	= $_GET['id']; // Sended ID from item, via GET
	
	$item		= "produtos"; // Table from DB where you store the item data (I store here the main image from item  ['img'])
	$imgs		= $item."_img"; // Table from DB where you stored the image names
	$path		= "admin/server/php/files/".$item."/"; // Path where images are stored
	
	/*
	
	Just to let you know, that's how I create my DB:
	
	Table produtos                       Table produtos_img
	id  |  title  |  img                 id  |  title  |  img     |  produtos_id
	------------------------             ----------------------------------------
	1   |  name   | img1.jpg             1   |  name   | img1.jpg |       1
	    |         |                      2   |  name2  | img2.jpg |       1
		
	*/

	// -------------------------------------------------------------------------------
?>

<!-- Jquery Libraries -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-1.10.2.js"></script>

<!-- Our Jquery Functions -->
<script type="text/javascript" charset="utf-8">
function showUser(str) {
	$("#main_photo").css("background-image", "url(admin/server/php/files/produtos/"+str+")");
}
</script>

<script type="text/javascript" charset="utf-8">

function lightbox() {
	
	var doc_height		= $( document ).height();
	var win_height		= $( window ).height();
	window.win_height2	= win_height-50;
	var margin_top		= doc_height/2;
	var scrolled 		= $(window).scrollTop();
	var scrolled2 		= scrolled+25;
	var main_photo		= $("#main_photo").css('background-image')
	var main_photo_res	= main_photo.replace('url("', '');
	var main_photo_res2	= main_photo_res.replace('")', '');
	
	var tmpImg	= new Image();
	tmpImg.src	= main_photo_res2;
	
	$(tmpImg).on('load',function(){
		var orgWidth	= tmpImg.width;
		var orgHeight	= tmpImg.height;
		var finalWidth	= (orgWidth*win_height2)/orgHeight;
		
		$('body').prepend('<div class="black_overlay" style="height:'+doc_height+'px;"></div>');
		$('body').prepend('<div class="whitescreen" align="center" style="height:'+win_height+'px; margin-top:'+scrolled2+'px;" onclick="fechar()"><img src="'+main_photo_res2+'" "height="'+win_height2+'" width="'+finalWidth+'"></div></div>');
		
	});
	
}

function fechar() {
	$( ".black_overlay" ).remove();
	$( ".whitescreen" ).remove();
}
	
</script>

<?php	
	// Query to get the main photo of the item (It´s here because we need it to use on CSS)
	$sql_img1		= mysqli_query($con, "SELECT img FROM ".$item." WHERE id = '".$id_products."' ");
	$fetch_img1		= mysqli_fetch_array($sql_img1);
	$img1			= $fetch_img1['img'];
?>

<style type="text/css">

#container_photo{
	width:100%;
	height:100%;
}

#main_photo{
	width:460px;
	height:460px;
	background-image:url(<?php echo $path.$img1; ?>);
	background-size:cover;
	background-repeat:no-repeat;
	
	border:thin;
	border-color:#CCC;
	border-style:solid;
	border-width:1px;
	
	transition:0.3s;
	-o-transition:0.3s;
	-ms-transition:0.3s;
	-moz-transition:0.3s;
	-webkit-transition:0.3s;
}

#thumbstrip{
	width:460px;
	height:100px;
	margin-top:10px;
}

#thumb{
	width:100px;
	height:100px;
	margin-left:20px;
	display:inline-block;
	border:none;
	font-size:1px;
	color:#FFF;
	text-align:left;
	
	border:thin;
	border-color:#CCC;
	border-style:solid;
	border-width:1px;
}

#fullscreen{
	width:100%;
	height:100%;
	position:absolute;
	float:left;
	z-index:1000;
	background-color:#000;
}

.black_overlay{
	position: absolute;
	width: 100%;
	background-color: black;
	z-index:1005;
	-moz-opacity: 0.8;
	opacity:.80;
	filter: alpha(opacity=80);
}

.whitescreen{
	position: absolute;
	display:table;
	vertical-align:central;
	text-align:center;
	width:100%;
	z-index:1006;
}

</style>
    
<div id="container_photo">
	
    <!-- This div below is our big image, who is selected -->
	<div id="main_photo" onclick="lightbox()">
    </div>
    
    <div id="thumbstrip">
    <?php
		
		// Query to get images to the gallerry
        $sql_img2	= mysqli_query($con, "SELECT * FROM ".$imgs." WHERE ".$item."_id = '".$id_products."' ");
		
		// This echo below shows the main image from, which is the first image of our thumbstrip
		echo '<input type="button" id="thumb" value="'.$img1.'" onclick="showUser(this.value)"
				style="background-image:url('.$path.$img1.'); background-size:cover; background-repeat:no-repeat;
				margin-left:0" />';
		
		// This while below shows us all images of our thumbstrip (That are stored on imgs table on DB)		
		while($fetch_img2 = mysqli_fetch_array($sql_img2)){
			$img2	= $fetch_img2['img'];
			echo '<input type="button" id="thumb" value="'.$img2.'" onclick="showUser(this.value)"
				style="background-image:url('.$path.$img2.'); background-size:cover; background-repeat:no-repeat;" />';
		}
	?>
    </div>
    
</div>