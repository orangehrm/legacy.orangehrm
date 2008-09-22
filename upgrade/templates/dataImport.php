<?php
if (!isset($_SESSION['authorized']) || $_SESSION['authorized'] !='Yes') {
	header('location:../');
}
?>

<htm>
<head>
<title>Data Import from Current Database</title>
<link type="text/css" rel="stylesheet" href="upgraderStyle.css" />
<script language="javascript" type="text/javascript" src="templates/dataImport-ajax.js"></script>
<style type="text/css">
<!--
#progressBar {
	background-color: #FFCC00;
	display: block;
	height: 10px;
}

#btnSubmit {
	display:none;
}
-->
</style>

</head>
<body>
<table width="400" border="0" cellspacing="20" cellpadding="5" align="center">
  <tr>
    <td><h1>Data Import from Current Database</h1></td>
  </tr>
  <tr>
    <td><p id="message">Upgrader successfully created database tables for new installation. Now it's importing data from current database.</p>
	  <div id="divProgressBarContainer" class="statusValue">
		<span style="width:200px; display: block; float: left; height: 10px; border: solid 1px #000000;">
			<span id="progressBar" style="width: 0%;">&nbsp;</span>
		</span>
		&nbsp;
		<span id="spanProgressPercentage">0%</span>
	</div>
    </td>
  </tr>
  <tr>
    <td align="center">
	<form name="frmDataImport" method="post" action="UpgradeController.php">
	<input type="hidden" name="hdnState" value="oldConstraints" />
	<input type="submit" name="btnSubmit" value="Continue"  size="40" id="btnSubmit" />
	</form>
	</td>
  </tr>
</table>
<script language="javascript" type="text/javascript">
var tArray = new Array();
<?php
$count = count($tablesArray);
$i = 0;
for ($i; $i < $count; $i++) {
    echo "tArray[$i] = \"".$tablesArray[$i]."\"; ";
}
?>
var tCount = <?php echo $count; ?>;
setData(tArray, tCount);
dataImport();
</script>
</body>
</html>
