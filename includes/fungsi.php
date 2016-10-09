<?php
function create_client($id, $ktp, $nama, $alamat, $notelp){
	global $con;
	$res = $con->query("INSERT INTO client VALUES ('$id', $ktp, '$nama', '$alamat', '$notelp')");
	if($res) return true;
	return false;
}
function check_client($id){
	global $con;
	$id = mysql_real_escape_string($id);
	$sql = "SELECT * FROM client WHERE client_id = '$id'";
	$res = $con->query($sql);
	if($res->num_rows > 0) return false;
	else {
		//create_client('$id', $ktp, $nama, $alamat, $notelp);
		return true;
	}
}
function add_pengaduan($client_id, $message, $type, $grup, $lat, $lng){
	global $con;
	$status = 0;
	if($type === 1) $status = 1;
	$time = time();
	$sql = "INSERT INTO pengaduan VALUES (null, '$client_id', '$message', $time, $type, $status, $grup, '$lat', '$lng')";
	$res = $con->query($sql);
	return $time;

}
function retrieve($client_id, $message, $type, $grup){

}

function get_total_pengaduan($grup){
	global $con;
	$sql = "SELECT COUNT(*) total FROM pengaduan WHERE grup = $grup AND type = 1 GROUP BY client_id";
	$res = $con->query($sql);
	return $res->num_rows;
}
function get_new_total_pengaduan($grup){
	global $con;
	$sql = "SELECT * FROM pengaduan WHERE grup = $grup AND status = 1 GROUP BY client_id";
	$res = $con->query($sql);
	return $res->num_rows;
}
function get_list_pengaduan($grup){
	global $con;
	$sql = "SELECT * FROM (SELECT client_id, MAX(date) AS date FROM pengaduan WHERE grup = $grup GROUP BY client_id) AS x JOIN
	pengaduan p USING (client_id, date)
	LEFT JOIN client c ON c.client_id = p.client_id
	ORDER BY p.date DESC";
	$pengaduan = array();
	$res = $con->query($sql);
	if ($res->num_rows > 0) {
		while($row = $res->fetch_assoc()) {
			$pengaduan[] = $row;
		}
	}
	return $pengaduan;
}
function get_pengaduan($client_id, $grup){
	global $con;
	$pengaduan = array();
	$sql = "SELECT * FROM pengaduan WHERE client_id = '$client_id' AND grup = $grup ORDER BY `date` ASC";
	$res = $con->query($sql);
	if ($res->num_rows > 0) {
		while($row = $res->fetch_assoc()) {
			$pengaduan[] = $row;
		}
	}
	return $pengaduan;
}
function get_pengaduan_terbaru($client_id, $grup, $last_timestamp){
	global $con;
	$pengaduan = array();
	mark_read($client_id, $grup);
	$sql = "SELECT * FROM pengaduan WHERE client_id = '$client_id' AND grup = $grup AND date > $last_timestamp AND type = 1 ORDER BY `date` ASC";

	$res = $con->query($sql);
	if ($res->num_rows > 0) {
		while($row = $res->fetch_assoc()) {
			// $date = new DateTime("@$row[date]");
			// $temp = $date->format('H:i');
			$date = new DateTime("@$row[date]");
			$date->setTimezone(new DateTimeZone('Asia/Jakarta'));
			$temp = $date->format('H:i');
			$row['time'] = $temp;
			$pengaduan[] = $row;
		}
	}
	return $pengaduan;
}
function get_latest_help($last){
	global $con;
	$pengaduan = array();
	$sql = "SELECT * FROM pengaduan p LEFT JOIN client c ON p.client_id = c.client_id WHERE grup = 0 AND status = 1 AND `date` > $last
	ORDER BY `date` DESC LIMIT 1";
	$res = $con->query($sql);
	if ($res->num_rows > 0) {
		while($row = $res->fetch_assoc()) {
			$pengaduan = $row;
		}
	}
	return $pengaduan;
}
function get_latest_message($last){
	global $con;
	$pengaduan = array();
	$sql = "SELECT * FROM pengaduan p LEFT JOIN client c ON p.client_id = c.client_id WHERE status = 1 AND `date` > $last
	ORDER BY `date` DESC LIMIT 1";
	$res = $con->query($sql);
	if ($res->num_rows > 0) {
		while($row = $res->fetch_assoc()) {
			$pengaduan = $row;
		}
	}
	return $pengaduan;
}
function get_pengadu($client_id){
	global $con;
	$pengadu = array();
	$sql = "SELECT * FROM client WHERE client_id = '$client_id'";
	$res = $con->query($sql);
	if ($res->num_rows > 0) {
		while($row = $res->fetch_assoc()) {
			$pengadu = $row;
		}
	}
	return $pengadu;
}
function get_latest_response($client_id, $grup, $last_timestamp){
	global $con;
	$pengaduan = array();
	$sql = "SELECT * FROM pengaduan WHERE client_id = '$client_id' AND grup = $grup AND date > $last_timestamp AND type = 0 ORDER BY `date` ASC";
	//echo $sql;
	$res = $con->query($sql);
	if ($res->num_rows > 0) {
		while($row = $res->fetch_assoc()) {
			$date = new DateTime("@$row[date]");
			$temp = $date->format('H:i');
			$row['time'] = $temp;
                        //tambahan
                        $row['timeServer'] = time();
			$pengaduan[] = $row;
		}
	}
	return $pengaduan;

}
function mark_read($client_id, $grup){
	global $con;
	$sql = "UPDATE pengaduan SET status = 0 WHERE grup = $grup AND `client_id` = '$client_id'";
	$res = $con->query($sql);
}
function upload_image($files){
	$target_dir = "upload/images/";
	$target_file = $target_dir . time() . '_' . basename($_FILES["image"]["name"]);
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	// Check if image file is a actual image or fake image
	$check = getimagesize($_FILES["image"]["tmp_name"]);
	if($check !== false) {
		 if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
		 	return $target_file;
		} else {
			return false;
		}
	} else {
		return false;
	}

}



function login($username,$password){
	global $con;
	$sql = "SELECT * FROM username= '$username' WHERE password = '$password'";
	$res = $con->query($sql);
	
}



 ?>
