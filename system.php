<?php
require_once("includes/config.php");
require_once("includes/fungsi.php");

$con = new mysqli($db_host,$db_user,$db_pass,$db_name);


if(!empty($_GET['act'])){
	if($_GET['act'] === 'response_client'){
		if(!empty($_POST)){
			$user = array();
			$user['id'] = $_POST['deviceId'];
			$grup = $_POST['toId'] == 3 ? 0 : $_POST['toId'];
			$msg = $_POST['msg'];
			if($_POST['isImage'] == true){
				$files = $_POST['files'];
				$url = upload_image($_FILES);
				if($url){
					$msg = '<a href="" class="view-image-larger"><img src="'.$url.'"></a>';
				}
				else $msg = 'Failed to upload image.';
			}
			$time = add_pengaduan($_POST['deviceId'], $msg, 1, $grup, $_POST['lat'], $_POST['lng']);
			//echo $time;
                 $temp = time();
		//tambahan
		$arrData = array('timeServer' => $temp);
		echo json_encode($arrData);

		}
	}
	if($_GET['act'] === 'response_admin'){
		if(!empty($_POST)){
			$user = array();
			$user['id'] = $_POST['deviceId'];
			// $grup = $_POST['toId'] == 3 ? 0 : $_POST['toId'];
			$grup = $_POST['toId'];
			$time = add_pengaduan($_POST['deviceId'], $_POST['msg'], 0, $grup, 0, 0);
			$date = new DateTime("@$time");
			$date->setTimezone(new DateTimeZone('Asia/Jakarta'));
			$temp = $date->format('H:i');
			echo $temp;
		}
	}
	if($_GET['act'] === 'listen'){
		if(!empty($_POST)){
			$temp = get_pengaduan_terbaru($_POST['id'], $_POST['grup'], $_POST['last_timestamp']);
			$counter["help"] = get_new_total_pengaduan(0);
			$counter["kritiksaran"] = get_new_total_pengaduan(1);
			$counter["laporan"] = get_new_total_pengaduan(2);
			$tmp = array();
			$tmp['data'] = $temp;
			$tmp['counter'] = $counter;
			echo json_encode($tmp);
		}
	}
	if($_GET['act'] === 'check'){
		if(!empty($_POST)){
			$temp = get_latest_message($_POST['last_notif']);
			echo json_encode($temp);
		}
	}
	if($_GET['act'] === 'register'){
		if(!empty($_POST)){
			if(check_client($_POST['deviceId']))
				create_client($_POST['deviceId'], $_POST['ktp'], $_POST['nama'], $_POST['alamat'], $_POST['telp']);
		}
	}
	if($_GET['act'] === 'listen_client'){
		if(!empty($_POST)){
			$temp = get_latest_response($_POST['id'], $_POST['grup'], $_POST['last_timestamp']);
			echo json_encode($temp);
		}
	}
	if($_GET['act'] === 'check_client'){
		if(!empty($_POST)){
			if(check_client($_POST['deviceId'])){
				echo '1';
			} else echo '0';
		}

	}
}

?>
