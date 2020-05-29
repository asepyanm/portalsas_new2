<?php
$ora = new clsMysql;
$ora->logon($bit_app["user_db"], $bit_app["pass_db"]);
?>
<table align="center" class="table">
	<tr>
		<td align="center">
			<br>
			<br>
				<br>
			<br>
			<h1>Selamat Datang di Panel Administrasi</h1>
			<br>
			<br>
			<br>
			<br>

		</td>
	</tr>
	<tr>
		<td><?php author_publisher() ?></td>
	</tr>
</table>