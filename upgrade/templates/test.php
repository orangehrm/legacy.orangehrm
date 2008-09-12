<?php
$tables[0] = 'test';
$tables[1] = 'test1';

?>
<html>
<head>
<title>Data Import from Current Database</title>
<link type="text/css" rel="stylesheet" href="upgraderStyle.css" />
</head>
<style type="text/css">
<!--
	.roundbox {
		margin-top: 10px;
		margin-left: 0px;
		width: 700px;
	}

	.roundbox_content {
		padding:15px;
	}

	.statusLabel {
		width: 200px;
		text-align: left;
		float: left;
		font-weight: bold;
	}

	.statusData {
		width: 150px;
		text-align: left;
		font-weight: normal;
		float: left;
	}

	#progressBar {
		background-color: #FFCC00;
		display: block;
		height: 10px;
	}
-->
</style>
<body>
<table width="400" border="0" cellspacing="20" cellpadding="5" align="center">
  <tr>
    <td><h1>Data Import from Current Database</h1></td>
  </tr>
  <tr>
    <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="left" valign="middle"><p>Upgrader successfully created database tables for new installation. Now it's importing data from current database.</p></td>
      </tr>
      <tr>
        <td align="left" valign="middle">&nbsp;</td>
      </tr>
      <tr>
        <td align="left" valign="middle">
	<div id="divProgressBarContainer" class="statusValue">
		<span style="width:200px; display: block; float: left; height: 10px; border: solid 1px #000000;">
			<span id="progressBar" style="width: 0%;">&nbsp;</span>
		</span>
		&nbsp;
		<span id="spanProgressPercentage">0%</span>
	</div>
	<br />
	</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center">
	<form name="frmDataImport" method="post" action="UpgradeController.php">
	<input type="hidden" name="hdnState" value="newDbChnages" />
	<input type="submit" name="btnSubmit" value="Continue"  size="40" />
	</form>
	</td>
  </tr>
</table>

<script language="javascript">

var requestTables= new Array();
var fileIndex = 0;
var sueessTable = 0;
var requestLinkPrefix = "test1.php?table=";

var allTables = <?php echo count($tables) ?> ;
<?php
		$i = 0;
		foreach($tables as $key=>$value) {
?>
requestTables[<?php echo $i++; ?>] = "<?php echo  $value ?>";
<?php
		}
?>


function $($id) {
	return document.getElementById($id);
}

function initImport(index) {

	    xmlHTTPObject = null;

		try {
  			xmlHTTPObject = new XMLHttpRequest();
		} catch (e) {
			try {
			    xmlHTTPObject = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				xmlHTTPObject = new ActiveXObject("Microsoft.XMLHTTP");
			}
		}

		if (xmlHTTPObject == null)
			alert("Your browser does not support AJAX!");

	        xmlHTTPObject.onreadystatechange = function() {

        	if (xmlHTTPObject.readyState == 4){
				 
        		response = xmlHTTPObject.responseText;
				 
				  
				if(response){
					sueessTable++;
					progressPercentage = Math.ceil((sueessTable / allTables) * 100);
					changeProgressBar(progressPercentage);
				}
				
				if (fileIndex < allTables - 1) {
        				fileIndex++;
        		 		initImport(fileIndex);
	        	} else {
					//showFinalResults();
				}	
        	}
		}	 
		
		 
		
		xmlHTTPObject.open('GET', requestLinkPrefix + requestTables[index], true);
		xmlHTTPObject.send(null);


	}
	
	
	function changeProgressBar(pecentage) {
		$('progressBar').style.width = pecentage + '%';
		$('spanProgressPercentage').innerHTML = pecentage + '%';
	}
	
initImport(fileIndex);
</script>
</body>
</html>
