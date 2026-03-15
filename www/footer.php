<?php include "vars.php"; ?>
<p>
<?php
echo "<table style=\"width: 100%;\" bgcolor=\"" . trim($tBG) . "\">";
?>
	<colgroup>
		<col span='1' style='width: 90%;'>
		<col span='1' style='width: 10%;'>
	</colgroup>
	<tr>
		<td></td>
		<?php 
			echo "<td><small><a href=\"mailto:" . htmlspecialchars($my_email) . "\" style=\"color:" . trim($fBG) . "\">";
			echo "<i>" . htmlspecialchars($my_name) . "</i></a></small></td>";
		?>
	</tr>
	<tr>
		<td></td>
		<?php
			echo "<td><small><i><span style=\"color:" . trim($fBG) . "\">Copyright &copy ";
			echo htmlspecialchars($copyright) . "</span></i></small></td>";
		?>
	</tr>
</table>
</body>
</html>

