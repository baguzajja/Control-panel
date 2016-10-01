<?php
require_once("bootstrap.php");
require_once("includes/config.php");
require_once("system.php");
$total[0] = get_total_pengaduan(0);
$total[1] = get_total_pengaduan(1);
$total[2] = get_total_pengaduan(2);
$total_new[0] = get_new_total_pengaduan(0);
$total_new[1] = get_new_total_pengaduan(1);
$total_new[2] = get_new_total_pengaduan(2);
$last_timestamp = 0;
$temp = get_latest_help(0);
$last_help = @$temp['date'];
if(!$last_help) $last_help = 0;
$id = empty($_GET['id']) ? -1 : $_GET['id'];
if($id !== -1) mark_read($id, 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Polresta Malang</title>

	<!-- Bootstrap -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	<script type="text/javascript">
		var count_help = <?=$total_new[0];?>, count_kritik = <?=$total_new[1];?>, count_laporan = <?=$total_new[2];?>, client_id = '<?=$id;?>', grup = 2;
	</script>
</head>
<body>
	<!-- Fixed navbar -->
	<nav class="navbar navbar-default navbar-fixed-top">
		<!-- <div class="container"> -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">
			<img src="img/icon-app.png" height="28px" width="auto">
			<p>Bantuan Polisi</br>Malang Kota</p>
			</a>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li class=""><a href="index.php">Help! <?php if($total_new[0] > 0) echo '<span class="badge danger">'.$total_new[0].'</span>'; ?></a></li>
				<li><a href="kritiksaran.php">Kritik &amp; Saran <?php if($total_new[1] > 0) echo '<span class="badge">'.$total_new[1].'</span>'; ?></a></li>
				<li class="active"><a href="laporan.php" class="last-child">Laporan <?php if($total_new[2] > 0) echo '<span class="badge">'.$total_new[2].'</span>'; ?></a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li>
					<a href="#" class="user-menu">
						<img src="img/user-default.png" class="user-image" alt="User Image">
						<span class="hidden-xs">Administrator</span>
					</a>
				</li>
				<li><a href="logout.php" title="Sign Out"><span class="glyphicon glyphicon-off" aria-hidden="true"></span></a></li>
			</ul>
		</div><!--/.nav-collapse -->
		<!-- </div> -->
	</nav>
	<div id="content-help" class="content-wrapper">
		<!-- <div class="container"> -->
			<div class="content-header">
			</div>
			<div class="row">
			<div class="content-body">
				<div class="sidebar">
					<h1>Laporan</h1>
					<!-- <form action="#" method="get" class="sidebar-form">
						<div class="input-group">
							<input type="text" name="q" class="form-control" placeholder="Search...">
							<i class="fa fa-search"></i>
						</div>
					</form> -->
					<span class="total"><?=$total[2] ?> Total laporan (<?=$total_new[2] ?> Laporan baru)</span>
					<ul class="sidebar-help">
					<?php
					$pengaduan = get_list_pengaduan(2);

					foreach ($pengaduan as $key => $value) {
						$preview = $value['message'];
						if (strlen($preview) > 30) $preview = substr($preview, 0, 27) . '...';
						if(strpos($preview,'<a href=') !== false)	$preview = "Image";
						$date = new DateTime("@$value[date]");
						$date->setTimezone(new DateTimeZone('Asia/Jakarta'));
						$temp = $date->format('Y/m/d H:i:s');

						$li_status = $value['status'] == 1 ? 'new' : '';
						if($id === $value['client_id']) $li_status = 'active';
					?>
						<li id="room-<?=$value['client_id']?>" class="<?=$li_status ?>">
							<a href="?id=<?=$value['client_id'] ?>">
								<span class="preview"><?=$preview ?></span>
								<span class="author"><?=$value['nama'] . ' - ' . $temp; ?></span>
								<?php echo $li_status == 'new' ? '<span class="alert-btn">new</span>' : ''; ?>
							</a>
						</li>
					<?php
					}
					?>
					</ul>
				</div>
			</div>
			<div class="content content-help">
				<div class="row">
					<!-- <h1>Help! <small>Lorem Ipsum</small></h1> -->
					<div class="help-messages col-md-9">
						<div class="message-wrapper">
							<?php
							$lat = "";
							$lng = "";
							if($id !== -1){ ?>
							<ul>

								<?php
								foreach (get_pengaduan($id, 2) as $key => $value) {
									$msg_type = $value['type'] == 1 ? 'message' : 'message reply';
									$date = new DateTime("@$value[date]");
									$date->setTimezone(new DateTimeZone('Asia/Jakarta'));
									$temp = $date->format('H:i');
									$last_timestamp = $value['date'];
									if($value['type'] == 1){
										$lat = $value['lat'];
										$lng = $value['lng'];
									}
								?>
								<li class="msg">
									<div class="<?=$msg_type?>">
										<p><?=$value['message']?></p>
										<span class="date"><?=$temp?></span>
									</div>
									<br class="clear">
								</li>
								<?php
								}

								?>
							</ul>
							<?php
							} else {
							?>
							<h3>Silahkan pilih laporan terlebih dahulu</h3>
							<?php
							}
							?>
						</div>
						<div class="reply-wrapper">
							<form>
								<div class="form-group">
									<!-- <textarea id="response" name="help-reply" class="form-control" placeholder="Tanggapi..."></textarea> -->
									<!-- <input type="text" name="q" class="form-control " placeholder="Tanggapi"> -->
									<input type="text" name="response" id="response" class="form-control" placeholder="Tanggapi">
									<button id="btn-help-reply" class="btn"><i class="fa fa-send"></i> Kirim</button>
									<br class="clear">
								</div>
							</form>
						</div>
					</div>
					<div class="help-client col-md-3">
						<?php
							if($id != -1){
							$pengadu = get_pengadu($id);
						?>
						<h2>Data Pengadu</h2>
						<ul>
							<li>
								<label>Nama</label>
								<span class="value"><?=$pengadu['nama'] ?></span>
							</li>
							<li>
								<label>No. KTP</label>
								<span class="value"><?=$pengadu['noktp'] ?></span>
							</li>
							<li>
								<label>Alamat</label>
								<span class="value"><?=$pengadu['alamat'] ?></span>
							</li>
							<li>
								<label>No HP</label>
								<span class="value"><?=$pengadu['nohp'] ?></span>
							</li>
							<li>
								<label>Lokasi Saat Ini</label>
								<span>
									<?php if($lat !== '' && $lng !== '') { ?>
									<a target="_blank" href="https://www.google.co.id/maps?q=loc:<?=$lat?>,<?=$lng?>">lokasi</a>
									<?php } else { echo 'Lokasi tidak tersedia'; } ?>
									<!-- <img src="img/location.jpg"> -->
								</span>
							</li>
						</ul>
						<?php } ?>
					</div>
				</div>
			</div>
			</div>
		<!-- </div> -->
	</div>
	<embed src="/path/to/your/sound.wav" autostart="true" hidden="true" loop="false">
	<!-- <embed src="misc/alert.mp3" hidden=true autostart=true loop=false> -->
	<audio id="chatAudio"><source src="misc/alert.mp3" type="audio/mpeg"></audio>
	<script type="text/javascript">
		var last_timestamp = <?=$last_timestamp;?>, last_notif = <?=$last_help;?>;
	</script>
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/script.js"></script>
</body>
</html>
