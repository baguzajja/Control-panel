<?php
// Auth
session_start();

include "includes/config.php";
function anti_injection($data){
  		$filter = mysql_real_escape_string(stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES))));
  		return $filter;
}
$username = anti_injection($_POST[username]);
$password = anti_injection(md5($_POST[password]));



}
	else{
	$sql = mysql_query("SELECT * FROM admin WHERE username = '$username' AND password = LEFT('$password',20)");
	$data = mysql_fetch_array($sql);
	$jml = mysql_num_rows($sql);
	if ($jml == 1)
	{
		$sqlx = mysql_query("select * from tool where id=1");
		$datax = mysql_fetch_array($sqlx);
		session_start();
		$_SESSION[userid]	 	= $data[username];
		$_SESSION[aksesid]	= $data[akses];
		$_SESSION[nu]			 	= $data[nama];
		$ip      = $_SERVER['REMOTE_ADDR'];
		mysql_query("UPDATE log SET last_login=now(), ip_login='$ip' where username='$username'");

	}
	else if ($jml != 1)
	{
		?>
			<script language="javascript">
		{
				alert("Mohon Maaf, Username atau Password yang Anda Masukan Tidak Terdaftar");
				javascript:history.back();
		}
		</script>
			<?php
	}
	else
	{
		session_start();
		session_unset();
		session_destroy();
		header("Location:login.php");
	}

}

?>
