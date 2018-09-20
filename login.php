<?php 
session_start();

require 'lib/koneksi.php';
?>

<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<script src="Js/typed.js"></script>
	<script src="Js/typed.min.js"></script>
	<link rel="stylesheet" type="text/css" href="Js/sweetalert2.css">
</head>
<body>
<form method="post">
<table style="border-spacing: 20px">
	<tr>
		<td><input type="text" name="id" id="Username"  autocomplete="off"></td>
	</tr>
	<tr>
		<td><input type="password" name="password" id="Password" autocomplete="off"></td>
	</tr>
	<tr>
		<td><button type="submit" name="login">Login</button></td>
	</tr>
</table>
</form>
</body>
</html>
<script src="Js/sweetalert2.js"></script>
<script src="Js/sweetalert2.min.js"></script>
<script type="text/javascript">
 var typed2 = new Typed('#Username', {
    strings: ['Masukkan Nama'],
    typeSpeed: 100,
    backSpeed: 0,
    attr: 'placeholder',
    bindInputFocusEvents: true,
    loop: true
  });
 var typed3 = new Typed('#Password', {
    strings: ['Masukkan Password'],
    typeSpeed: 75,
    backSpeed: 0,
    attr: 'placeholder',
    bindInputFocusEvents: true,
    loop: true
  });
</script>
<?php
if (isset($_POST["login"])) 
{	
	$id = mysqli_real_escape_string($koneksi,$_POST["id"]);
	$pass = mysqli_real_escape_string($koneksi,$_POST["password"]);
	$ip = $_SERVER['REMOTE_ADDR'];
	$com = gethostbyaddr($_SERVER['REMOTE_ADDR']);				
	$tgl = date('Y-m-d');

	$query = mysqli_query($koneksi, "SELECT * FROM user WHERE id = '$id'");
	if (mysqli_num_rows($query) === 1) 
	{		
		$result = mysqli_fetch_array($query);
		if($result["status"] == 'A')
		{			
			if (password_verify($pass, $result["password"]))
			{
				if($result["statuslogin"] == 0)
				{
					$benar = mysqli_query($koneksi,"UPDATE user SET salahpass = 0,statuslogin = 1 where id = '$id'");
					$_SESSION["login"] = true;
					$_SESSION["id"] = $result["id"];			
					header('location:index.php');
					exit;
				}
				else
				{
					echo "<script>
					sweetAlert('Ooops...!!','Dobel login gan','error');																
					</script>";
				}
			}
			else
			{
				$salah = mysqli_query($koneksi,"UPDATE user SET salahpass = salahpass + 1 where id = '$id'");
				$cek = mysqli_query($koneksi,"SELECT salahpass FROM user WHERE id = '$id'");
				$hasil = mysqli_fetch_array($cek);					

				if($hasil["salahpass"] < 3)
				{
					$alert = "Password Salah ".$hasil["salahpass"]."x";				
					echo "<script>
					sweetAlert('Ooops...!!','$alert','error');													
					</script>";
				}
				else
				{
					$blokir = mysqli_query($koneksi,"UPDATE user SET status = 'B' where id = '$id'");
					echo "<script>
					sweetAlert('Ooops...!!','User terblokir gan!','error');								
					</script>";					
				}
			}
		}
		else
		{
			echo "
			<script>			
			sweetAlert('Ooops...!!','User terblokir gan!','error');			
			document.location.href='login.php';
			</script>";
		}
	}
	else
	{
		echo "<script>
			sweetAlert('Ooops...!!','User ga ada gan!','error');			
			</script>";
	}
	$error = true;
}

 ?>
