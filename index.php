<?php 
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="Homepage for HighSide - The Motorcycle Q&A Website">
<title>HighSide - Motorcycle Experience Sharing Platform</title>
<link rel="stylesheet" type="text/css" href="./style.css" media="all">
</head>
<body>
	<header>
		<h1> HighSide - Motorcycle Experience Sharing Platform </h1>
	</header>

	<?php 
	if ($_SESSION["UserID"] == "")
	{
		echo "We realized that you are riding these streets with no license plate. Please "; 
		echo '<a href="./login.php">login here!</a>';
	}
	else
		echo "session is " . $_SESSION["UserID"];
	?>

	<div class="topMenu" style="padding:1px; border-top-width:0px">
		<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
			<tbody>
				<tr align="center">
					<td class="topMenuItem">
						<a href="newpost.php"> Ask a Question! </a>
					</td>
					<td class="topMenuItem">
						
					</td>
			</tbody>
		</table>
	</div>
</body>
</html>