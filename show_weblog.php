<?php 
include('topmain.php');
$schema = 'NewsArticle'; // این بهتره

if (isset($_REQUEST['id']))  			$id = intval($_REQUEST['id']);  	else $id = 0;
$ErrorMsg='';
$bankname  = 'weblog'; 	
$query=pdo_query("select * from `$bankname` where id='$id' and active='2' $shartdatetimeWeblog  ",'',0);
$row_content = pdo_fetch($query);
if ($row_content=='') {	$ErrorMsg .= "<div class='ShowError'>کد مورد نظر صحیح نمی باشد</div>";} else{
	$tetr  			= $row_content['tetr'];				$leds 		= $row_content['leds'];
	$date_new		= $row_content['date'];				$time_new	= $row_content['time'];
	$matn 			= $row_content['matn'];				$photo_matn	= $row_content['photo_matn'];
	$keywords  		= $row_content['tags'];				$id_topics	= $row_content['id_topics'];
	$open_comment  	= $row_content['open_comment'];		$countdn	= $row_content['countdn'];
	$nazarcount		= $row_content['nazarcount'];		$them		= $row_content['them'];
	$videofile		= $row_content['videofile'];		$soundfile	= $row_content['soundfile'];
	$pdffile		= $row_content['pdffile'];			$show_header= $row_content['show_header'];
	//-----------------------
	$password			= $row_content['password'];
	$meta_title			= $row_content['meta_title'];
	$meta_description 	= $row_content['meta_description'];
	$url_redirect 		= $row_content['url_redirect'];
	if ($photo_group!='') {
		if (!validateURL($photo_group)) $photo_group = $upload_path_main.$photo_group;
		$photo_group=$logopage;
	}
	$title_main      = $tetr;
	if ($leds!='') $description= $leds;
	if ($keywords!='') $keyword = str_replace("\r\n",",",$keywords);
	//-------------------
	$tetrseo	= $row_content['meta_title'];
	if ($tetrseo=='') $tetrseo=$tetr;
	$canonical  = "$canonical/weblog/$id/" . LinkSeo($tetrseo);

	if ($url_redirect!='') header("Location: $url_redirect");
	if ($meta_title!='') 		{$meta = $meta_title;			$title_main=$meta_title;		 }
	if ($meta_description!='')  {$meta_dsc = $meta_description;	$description = $meta_description; }
	//--------------------------------------------------
	$StrTopics = ShowWeblogTopics($id_topics);
	if ($photo_matn!='') {
		if (!validateURL($photo_matn)) $photo_matn = $upload_path_main.$photo_matn;
	}
	//-
	$sql=pdo_query("update `$bankname` set countdn=countdn+1 where id='$id' ");
	//----------------------------------------------------------
	$posimg = strpos(strtolower($matn), '<img');
	if ($posimg !== false) {
		$html = $matn;
		$dom = new DOMDocument('1.0', 'UTF-8');
		libxml_use_internal_errors(true);	
		$dom->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
		libxml_clear_errors();
		foreach ($dom->getElementsByTagName('img') as $item) {
			$width = $item->getAttribute('width');
			if ($width == 0 ) {
				$item->setAttribute('style', 'margin-left:auto; margin-right:auto; display:block;');
				$item->setAttribute('width', '90%');
//				$item->setAttribute('height','90%');
			}
		}
		$matn= $dom->saveHTML();
	}
	//--------------------------------------------
	$header_output = '';
	if ($show_header==1) {
		$toc_items = [];
		$header_tags = ['h1','h2', 'h3', 'h4', 'h5', 'h6']; 
		$html = $matn;
		$id_counter = 1;
		$dom = new DOMDocument('1.0', 'UTF-8');
		libxml_use_internal_errors(true); 
		$dom->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
		libxml_clear_errors();
		foreach ($header_tags as $tag_name) {
			$headers = $dom->getElementsByTagName($tag_name);

			foreach ($headers as $header) {
				// حذف IDهای احتمالی قبلی
				$header->removeAttribute('id');

				// استخراج متن تیتر (برای فهرست)
				$title_text = $header->nodeValue;

				// تولید ID منحصر به فرد (Slug)
				// یک روش ساده: استفاده از شمارنده
				$unique_id = 'toc-item-' . $id_counter++;
				
				// 2. تنظیم ID (Bookmark) روی تگ اصلی در محتوا
				$header->setAttribute('id', $unique_id);

				// 3. ذخیره در آرایه فهرست مطالب
				$toc_items[] = [
					'title' => $title_text,
					'id' => $unique_id,
					'level' => (int)substr($tag_name, 1) // سطح هدر (مثلاً 2 برای h2)
				];
			}
		}
		// 4. استخراج محتوای HTML اصلاح شده (حاوی تگ‌های IDدار)
		// از saveHTML استفاده می‌کنیم و body را استخراج می‌کنیم
		$matn = $dom->saveHTML();
		// حذف تگ‌های اضافه شده توسط loadHTML و loadHTML

		if (!empty($toc_items)) {
			$header_output .= '<ul>';

			foreach ($toc_items as $item) {
				// برای نمایش تورفتگی بر اساس سطح هدر (مثلاً h3 تورفتگی بیشتری از h2 داشته باشد)
				$indent_style = ($item['level'] > 2) ? 'style="margin-right: ' . (($item['level'] - 2) * 20) . 'px;"' : '';

				$header_output .= '<li ' . $indent_style . '>';
				// ایجاد لینک لنگر (Anchor Link)
				$header_output .= '<a href="#' . $item['id'] . '">' . $item['title'] . '</a>';
				$header_output .= '</li>';
			}

			$header_output .= '</ul>';
		}		
	}
	//----------------------------------
	$OkComment = true;
	if ($row_setting['ok_save_comment']==1) {
		if (!$ok_cookie) $OkComment= false;
	}
	if ($open_comment==0) $OkComment= false;
	//----------------------------------
}
/*
آیتم مثبت و منفی برای نظرات
آیتم نمایش اولیه فقط 10 نظر و برای بیشتر از آن به صورت آژاکس باشد
طراحی صحیح فرم ارسال نظر
برنامه آژاکس دریافت و ثبت نظر
*/
include('top.php');
if ($password!='') { 	if (CheckPassword($password) === false) { include('bottom.php');  exit;} }
//------------------------
$count_abzarak=0;
if ($them==1) {
$query = pdo_query("SELECT * FROM `them_detils`  where id_them='$id_them' and isdeleted=0 and active=1 and id_position=4 order by id_position, idsort",'',0);
$row_abzarak = pdo_fetchall($query);		$count_abzarak = count($row_abzarak);
}
$StyleDiv1 = "col-lg-12 col-md-12 col-xs-12 pr mt-0";
if ($count_abzarak>0) $StyleDiv1 = "col-lg-9 col-md-8 col-xs-12 pr mt-0";
?>
<main class="main-row mb-2 mt-0 d-block">
<div class="container-main">
<div class="d-block">
	<div class="<?php echo $StyleDiv1;?>">
	<?php 
	if ($them==4) $photo_matn='';
	if (($them==2) or ($them==4)) { 
	echo "
		<div style=\"background-image: url('$photo_matn');  padding: 150px 0 160px;   background-size: cover;    text-align: center;  margin: -40px 0 0;  background-position: center;  border-radius: 0.5rem !important;   background-color: #018227;\"> 
		<div class='container'>
			<div class='row'>
				<div class='d-none d-lg-block col-lg-1 col-xl-2'></div>
				<div class='col-md-12 col-lg-10 col-xl-8'>
					<h1 itemprop='headline' style='  color: #fff;font-size: 36px;  font-weight: bold;'>
						$tetr
					</h1>
				</div>
			</div>
		</div>
	</div>
	<div class='container'>
	<div class='row justify-content-center'>
	<div class='col-12'>
	<div class='border-0 rounded' style='margin-top: -100px;    background: #fff;    border: 1px solid #ccc;    padding: 20px 20px;    box-shadow: 0px 20px 50px 0px rgba(0, 0, 0, 0.07);  line-height: 200%;'>
	";
	$photo_matn='';
	}
	?>
		<section class="blog-home">
			<article class="post-item">
				<header class="entry-header mb-3">
					<div class="post-meta date"><i class="fa fa-calendar"></i> <?php echo $date_new;?></div>
					<div class="post-meta author"><i class="fa fa-clock-o"></i> <?php echo $time_new;?></div>
					<div class="post-meta category"><i class="mdi mdi-folder"></i> <?php echo $StrTopics;?></div>
					<?php if ($row_setting['showamarpage']==1) {
						echo "<div class='post-meta Visit'><i class='mdi mdi-eye'></i> $countdn بازدید </div>";
					}
					?>
				</header>
				<?php 
				if ($tetr!='') {
					echo "<div class='text-center mb-5'><h1 class='title-tag'>$tetr</h1></div>";
				}
				if ($header_output!='') {
					echo "<div class='col-12 mb-5 mt-5 title-leds'><h3>فهرست مطالب:</h3>$header_output</div>";
				}
				if ($leds!='') {
					echo "<div class='text-center mb-5'><h3 class='title-leds'>".nl2br($leds)."</h3></div>";
				}
				if ($photo_matn!='') {
					echo "<div class='post-thumbnail'><img src='$photo_matn' alt='$tetr'></div>";
				}
				if ($videofile!='') {
					echo "<div class='col-12'><video controls style='width:100%;'> <source src='$videofile' type='video/mp4'></video></div>";
				}
				if ($soundfile!='') {
					echo "<div class='col-12'><audio controls style='width:100%;'> <source src='$soundfile' type='audio/mp3'></audio></div>";
				}
				if ($pdffile!='') {
					echo "<div class='col-12 text-center'><a target=_blank href='$pdffile'>PDF</a></div>";
				}
				?>
				<div class="content-blog"><?php echo $matn;?> </div>
				<div class="col-12"><div class="TagsList">
				<?php
				if ($keywords!='') {
					$KeywordList = array_filter(explode("\n", $keywords));
					$JamRec = count($KeywordList);
					$temp = '';
					foreach ($KeywordList as $key => $val)	{
						$word = $KeywordList[$key];
						if ($word != '') {
							$temp .= "<a target=_blank href='/CategoryWeblog/4/search/$word'>$word </a>\r\n";
						}
					}
					if ($temp!='') $keywords = $temp;
					echo $keywords;
				}
				?>
				</div></div>
				<div class='col-12 text-left main-social' dir='ltr' >
					<?php echo ListShare($id,$tetr);?>
					<span style='font-size:14px;margin:10px;  display: inline-block;'>اشتراک گذاری مطلب</span>
				</div>
				
			</article>
			<div class="post-comments">
				<div class="comments-area">
					<?php if ($nazarcount>0) { ?>
					<h2 class="comments-title mb-3">
						<i class="fa fa-comment-o"></i><a onclick='$("#List_Comment").toggle();' style='  cursor: pointer;'>نظرات کاربران</a>
						<p class="count-comment"><?php echo $nazarcount;?> نظر</p>
					</h2>
					<?php } ?>
					<ol id='List_Comment' class="comment-list"><?php echo ListComment();?></ol>
					<?php if ($OkComment) {?>
					<div class="comment-us-section">
						<div class="col-12 box-title mb-5"><a onclick='$("#SaveComment").toggle();' style='  cursor: pointer;'>نظر شما</a></div>
						<div class="row" id='SaveComment'>
						<?php 
						$comment_name = '';		$useremail   = '';
						if ($ok_cookie) {
						$comment_name = $usernamefarsi;		$useremail   = $comment_email;
						echo "<input type='hidden' name='comment_iduser'  id='comment_iduser' value='$usernameid'>";
						}else{
						echo "<input type='hidden' name='comment_iduser'  id='comment_iduser' value='0'>";
						}
						?>
						<div class="col-12 col-md-6 mb-3">
							<label class="form-label">نام و نام خانوادگی<span class='text-danger'>*</span></label>
							<input class="form-control" value="<?php echo $comment_name;?>" id="comment_name" name="comment_name" type="text" >
						</div>
						<div class="col-12 col-md-6  mb-3">
							<label class="form-label">ایمیل  <span class='text-danger' style='font-size:10px;' >جهت دریافت پاسخ نظر ، ایمیل خود را وارد نمایید</span></label>
							<input class="form-control" value="<?php echo $comment_email;?>" id="comment_email" name="comment_email" type="text" dir='ltr'  >
						</div>
						<div class="col-12">
							<label class="form-label">متن نظر <span class='text-danger'>*</span></label>
							<textarea class="form-control" id="comment_message" name="comment_message" rows=5><?php echo $comment_message;?></textarea>
						</div>
						<?php
						if ($row_setting['ok_capcha_comment']==1) { 
//							session_start();
							echo "
							<div class='col-12 mb-3 mt-3'>
							<label class='form-label'>کد کپچا <span class='text-danger'>*</span></label>
							<input type='text'   name='comment_secCode' id='comment_secCode' class='form-control' size='6' style='width:120px;display:unset;margin-right:10px;'>";
							$verify_string_hash = captcha2('width:80px;',4);							
							echo "
							<input type='hidden' name='comment_sec2Code' value='$verify_string_hash'  id='comment_sec2Code'>
							</div>";
							$token_var = 'token_nazar0_'.$id;  
							$_SESSION[$token_var]= $verify_string_hash; 
						} else{
							echo "
							<input type='hidden' name='comment_secCode' id='comment_secCode'  value='' >
							<input type='hidden' name='comment_sec2Code' id='comment_sec2Code'  value=''>
							";
						}
						?>
						<div class="col-12 text-left mt-2">
							<button class="btn comment-submit-button" onclick="sendAjaxComment(<?php echo $id; ?>, 0)">ثبت نظر</button>
						</div>
						</div>
					</div>
					<?php }?>
				</div>
			</div>
		</section>
	</div>
	<?php 
	if (($them==2) or ($them==4)) { 
		echo "</div></div></div>";
	}
	
	if ($count_abzarak>0) { 
		echo "<div class='col-lg-3 col-md-4 col-xs-12 pr mt-0 sticky-sidebar'><div class='row'>";
		foreach($row_abzarak as $key=>$value) {
			ShowAbzarak($row_abzarak[$key]);
		}
		echo "</div></div>";
	} 
	?>
</div>
</div>
</main>
<?php
include('bottom.php');

if ($OkComment) {
?>
<div  class="modal fade"  id="AnswerComment"  tabindex="-1"   aria-labelledby="FormSeoLabel"  aria-hidden="true">
	<input type='hidden' value='' name='comment_id' id='comment_id'>
	<?php 
	$comment_name2 = '';		$useremail2   = '';
	if ($ok_cookie) {
	$comment_name2 = $usernamefarsi;		$useremail2   = $comment_email;
	echo "<input type='hidden' name='comment_iduser2'  id='comment_iduser2' value='$usernameid'>";
	}else{
	echo "<input type='hidden' name='comment_iduser2'  id='comment_iduser2' value='0'>";
	}
	
	?>
	
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content" style='border-color:#ffa101;border-block-width: thin;'>
			<div class="modal-header" >
				<h5 class="modal-title" id="AddRecordTetr" style='font-size: 20px;  margin-bottom: 12px;'>
				پاسخ به نظر</h5>
				<header id="LastEditor" class='row comment-meta' style='gap:10px;margin-top:5px;font-size:14px;'></header>
				
				<span style='cursor:pointer;' onclick='$("#AnswerComment").modal("hide");'>
				<i class="fa fa-times" data-bs-dismiss="modal"  aria-label="Close"></i>
				</span>
			</div>
			<div class="modal-body">
			<div class='col-12 mb-3' >
				<div class='row mt-1' style='border-radius: 20px; background-color:#F9F9F9; padding: 5px 5px'>
					<div class='col-6 mt-1 mb-2'>
						<label class="form-label">نام و نام خانوادگی:</label>
						<input type='text' name='comment_name2' class='form-control' id='comment_name2' value='<?php echo $comment_name2;?>' >
					</div>
					<div class='col-6 mb-2'>
						<label class="form-label">پست الکترونیکی:</label>
						<input type='email' dir='ltr' name='comment_email2' class='form-control' id='comment_email2' value='<?php echo $comment_email2;?>' >
					</div>
					<div class='col-12 mb-2'>
						<label class="form-label">متن نظر:</label>
						<textarea class="form-control" cols="20"  id="comment_message2" name="comment_message2"  rows="3" ></textarea>
					</div>
					<?php
					if ($row_setting['ok_capcha_comment']==1) { 
						echo "
						<div class='col-12 mb-3 mt-3'>
						<label class='form-label'>کد کپچا <span class='text-danger'>*</span></label>
						<input type='text'   name='comment_secCode2' id='comment_secCode2' class='form-control' size='6' style='width:120px;display:unset;margin-right:10px;'>";
						$verify_string_hash = captcha2('width:80px;',4);							
						echo "
						<input type='hidden' name='comment_sec2Code2' id='comment_sec2Code2'  value='$verify_string_hash'>
						</div>";
						$token_var = 'token_nazar1_'.$id;  
						$_SESSION[$token_var]= $verify_string_hash; 
					} else{
						echo "
						<input type='hidden' name='comment_secCode2' id='comment_secCode2'  value='' >
						<input type='hidden' name='comment_sec2Code2' id='comment_sec2Code2'  value=''>
						";
					}
					?>
				</div>
			</div>
			</div>
			<div class="modal-header text-left" style='display:unset;'  >
					<a  class='btn btn-success text-white' onclick="sendAjaxComment(<?php echo $id; ?>, $('#comment_id').val())" >
						<i class="fa fa-check mr-1"></i> ارسال
					</a>
					<a class='btn btn-secondary text-white' onclick='$("#AnswerComment").modal("hide");'>
						<i class="fa fa-list  mr-1"></i> بازگشت
					</a>
			</div>
		</div>
	</div>
</div>
<?php } ?>
<script>
$(document).ready(function(){
	<?php if ($OkComment) {?>
	$("#comment_message").val('');
	<?php } ?>
	<?php if (!($ok_cookie)) {?>
	$("#comment_name").val('');
	$("#comment_email").val('');
	<?php } ?>
	<?php if ($row_setting['ok_capcha_comment']==1) {  ?>
	$("#comment_secCode").val('');
	<?php } ?>
	 
});
//--------------------------
function ReplayComment(id_tag,id) {
	var HeaderComment = $('#'+id_tag).html();
	$("#LastEditor").html('['+HeaderComment+']');
	$("#comment_id").val(id);
	$("#comment_name2").val('');
	$("#comment_email2").val('');
	$("#comment_message2").val('');
	<?php if ($row_setting['ok_capcha_comment']==1) { ?>
	$("#comment_secCode2").val('');
	<?php } ?>
	$("#AnswerComment").modal("show");
}
//-------------------------------------------------
function sendAjaxComment(idnews,idnazar) {
	tmp = '<input id="idButton" value="'+idnazar+'"  type="hidden">';
	if (idnazar==0) {
		var name = document.getElementById("comment_name");  
		var email = document.getElementById("comment_email");  
		var nazar = document.getElementById("comment_message");  
		var secCode  = document.getElementById("comment_secCode");  
		var sec2Code = document.getElementById("comment_sec2Code");  
		var iduser = document.getElementById("comment_iduser");  
	} else {
		var name = document.getElementById("comment_name2");  
		var email = document.getElementById("comment_email2");  
		var nazar = document.getElementById("comment_message2");  
		var secCode  = document.getElementById("comment_secCode2");  
		var sec2Code = document.getElementById("comment_sec2Code2");  
		var iduser = document.getElementById("comment_iduser2");  
	}
	if (name.value == "") {
		message('لطفا نام و نام خانوادگی خود را  وارد نمایید','error',0,'');
		name.focus();
		return false;
	}
	if ( (email.value!='')) {
		if (!checkEmail(email.value))  { 
			message('لطفا ایمیل خود را به درستی وارد نمایید','error',0,'');
			email.select();			email.focus();
			return false;
		}
	}
	if (nazar.value == "") {
		message('لطفا متن پیام خود را وارد نمایید','error',0,'');
		nazar.focus();
		return false;
	}
	<?php 	if ($row_setting['ok_capcha_comment']==1) { ?>
	if (secCode.value == "") {
		message('لطفا کد کپچا خود را وارد نمایید','error',0,'');
		secCode.focus();
		return false;
	}
	<?php }	?>
	nazar_value = nazar.value;
	$.ajax({
		type: 'POST',
		url: '/data_ajax.php',
		data: { type: 2, typebank: 2, idnews: idnews, idnazar: idnazar, comment_name: name.value, comment_email: email.value, comment_message: nazar_value, secCode: secCode.value, sec2Code: sec2Code.value, iduser:iduser.value, security:<?php echo rand(); ?>},
		dataType: 'json',
		success: function (data, status, xhr) {
			if (data[0]<0) { message(data[1], 'error') }
			if (data[0]==1) { 
				var new_message = data[1];
				message(data[1], 'success') 
				if (idnazar==0) {	
					var newdata = '<div class="col-10 bg-success text-white text-center p-3 rounded" style="margin:auto;">'+data[1]+'</div><BR><BR>';
					$('#SaveComment').html(newdata);
				} else { $("#AnswerComment").modal("hide");}
			}
		},
		error: function (jqXhr, textStatus, errorMessage) {alert(' textStatus = '+textStatus+' Error: ' + errorMessage);}
	});		
}
//-----------------------------------------
//--------------------------
function ReadCommentNew(startrec, idnews, maxread) {
	IdContinue = 'ContinueComment';
	$.ajax({
		type: 'POST',
		url: '/data_ajax.php',
		data: { type: 3, typebank: 2, startrec: startrec, idnews: idnews, maxread: maxread},
		dataType: 'json',
		success: function (data, status, xhr) {
			if (data[0]<0) { message(data[1], 'error') }
			if (data[0]==1) { 
			//	$('#ContinueComment').prop(data[1]);
				document.getElementById('ContinueComment').outerHTML= data[1] ;
			}
		},
		error: function (jqXhr, textStatus, errorMessage) {alert(' textStatus = '+textStatus+' Error: ' + errorMessage);}
	});		
}

</script>
<script src="/assets/js/vendor/theia-sticky-sidebar.min.js"></script>

<?php

function ListShare($id, $tetr) {
	global $sitenamelink;
	$link = $sitenamelink."/weblog/$id/";//.LinkSeo($tetr);
	$tetr2 = LinkSeo($tetr);
	$ret = "
	<ul>
	<li>
		<a target='_blank' href='https://pinterest.com/pin/create/link/?url=$link'><i class='fa fa-pinterest-p'></i> </a> 
	</li>
	<li>
		<a target='_blank' href='https://www.facebook.com/share.php?u=$link'><i class='fa fa-facebook'></i></a>  
	</li>
	<li>
		<a target='_blank' href='https://twitter.com/intent/tweet?text=$link'><i class='fa fa-twitter'></i> </a>  
	</li>
	<li>
		<a target='_blank' href='https://telegram.me/share/url?url=$link'><i class='fa fa-telegram'></i> </a>  
	</li>
	<li>
		<a target='_blank' href='https://api.whatsapp.com/send?text=$link'><i class='fa fa-whatsapp'></i> </a>  
	</li>
	<li>
		<a target='_blank' href='https://www.linkedin.com/shareArticle?mini=true&url=$link'><i class='fa fa-linkedin'> </i> </a>  
	</li>
	<li>
		<a target='_blank' href='https://twitter.com/share?url=$link&text=$tetr2'><i class='fa fa-twitter'> </i> </a>  
	</li>
	</ul>
	";
	return $ret;
}

function ListComment() {
	global $bankname, $id, $OkComment;
	$MaxReadComment=10;
	$StrReplay='';
	$ret = '';
	$query = pdo_query("select * from `nazar` where idrec='$id' and parentid='0' and deleted='f' and ok='y' and tablename='$bankname' order by date desc, time desc limit 0,$MaxReadComment ",'',0);
	$counter=0;
	while ($row_comment = pdo_fetch($query)) {
		$counter++;
		$id_comment = $row_comment['id'];
		$message = nl2br($row_comment['message']);			$answer = nl2br($row_comment['answer']);
		$country = $row_comment['country'];					$city = $row_comment['city'];
		$email 	 = $row_comment['email'];
		if ($email!='') $email = "<div class='post-meta Visit'><i class='fa fa-envelope'></i> $email </div>";
		if ($answer!='') $answer="<p class='text-danger'><span class='bg-danger text-white p-1'> پاسخ: </span><BR>$answer</p>";
		if ($country!='') $country = "<div class='post-meta'><i class='fa fa-flag'></i> $country</div>";
		if ($city!='') $city = "<div class='post-meta'><i class='fa fa-map-marker'></i> $city</div>";
		if ($OkComment) {
			$StrReplay = "<div class='reply text-left'><a onclick=\"ReplayComment('header$id_comment',$id_comment)\" class='comment-reply-link' style='cursor:pointer;'>پاسخ دادن</a></div>";
		}
		//----------------------
		$ListAnswerThisComment = ListCommentAnswer($id_comment, $bankname, $id, $OkComment);
		//-----------------------
		$ret .= "
		<li class='comment-even'>
			<div class='comment-body'>
				<header id='header$id_comment' class='row comment-meta' style='gap:10px;'>
					<div class='post-meta date'>$counter |</div>
					<div class='post-meta date'><i class='fa fa-calendar'></i> $row_comment[date] </div>
					<div class='post-meta author'><i class='fa fa-clock-o'></i> $row_comment[time] </div>
					<div class='post-meta'><i class='fa fa-user'></i> $row_comment[name]  </div>
					$email
					$country
					$city
				</header>
				<p>$message</p>
				$answer
				$StrReplay
			</div>
		</li>
		$ListAnswerThisComment
		";
	}
	if ($counter>=$MaxReadComment) { 
		$ret .= "
		<span id='ContinueComment'>
		<div class='col-12 text-center bg-success text-white rounded p-2 mt-5'>
		<a onclick='ReadCommentNew($MaxReadComment,$id, $MaxReadComment)' class='mbtn' style='cursor:pointer;'>خواندن نظرات بیشتر</a>
		</div>		
		</span>
		"; 
	} 
	return $ret;
}
//---------------------------------------------------------
function ListCommentAnswer($idcomment, $bankname, $id, $OkComment) { 
	$query2 = pdo_query("select * from `nazar` where idrec='$id' and deleted='f' and ok='y' and tablename='$bankname' and parentid='$idcomment' order by date desc, time desc  ",'',0);
	$counter2=0;
	$ret = '';
	while ($row_comment2 = pdo_fetch($query2)) {
		$counter2++;
		$id_comment = $row_comment2['id'];
		$message = nl2br($row_comment2['message']);			$answer = nl2br($row_comment2['answer']);
		$country = $row_comment2['country'];				$city = $row_comment2['city'];
		$email 	 = $row_comment2['email'];
		if ($email!='') $email = "<div class='post-meta Visit'><i class='fa fa-envelope'></i> $email </div>";
		
		if ($answer!='') $answer="<p class='text-danger'><span class='bg-danger text-white p-1'> پاسخ: </span><BR>$answer</p>";
		if ($country!='') $country = "<div class='post-meta'><i class='fa fa-flag'></i> $country</div>";
		if ($city!='') $city = "<div class='post-meta'><i class='fa fa-map-marker'></i> $city</div>";
		
		$ret .= "
		<li class='comment-even'>
			<div class='comment-body mr-5' style='background-color:#f3f9f1;line-height:20px;padding-top:10px;padding-bottom:1px;'>
				<header id='header$id_comment' class='row comment-meta' style='gap:10px;'>
					<div class='post-meta date'>$counter2 |</div>
					<div class='post-meta date'><i class='fa fa-calendar'></i> $row_comment2[date] </div>
					<div class='post-meta author'><i class='fa fa-clock-o'></i> $row_comment2[time] </div>
					<div class='post-meta'><i class='fa fa-user'></i> $row_comment2[name]  </div>
					$email
					$country
					$city
				</header>
				<p>$message</p>
				$answer
			</div>
		</li>
		";
	}
	return $ret;

}
?>
