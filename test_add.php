<HTML>
<HEAD>
 <TITLE> Add/Remove dynamic rows in HTML table </TITLE>
 <SCRIPT language="javascript">
  function addRow(tableID) {

   var table = document.getElementById(tableID);

   var rowCount = table.rows.length;
   var row = table.insertRow(rowCount);

   var colCount = table.rows[0].cells.length;

   for(var i=0; i<colCount; i++) {

    var newcell = row.insertCell(i);

    newcell.innerHTML = table.rows[0].cells[i].innerHTML;
    //alert(newcell.childNodes);
    switch(newcell.childNodes[0].type) {
     case "text":
       newcell.childNodes[0].value = "";
       break;
     case "checkbox":
       newcell.childNodes[0].checked = false;
       break;
     case "select-one":
       newcell.childNodes[0].selectedIndex = 0;
       break;
    }
   }
  }

  function deleteRow(tableID) {
   try {
   var table = document.getElementById(tableID);
   var rowCount = table.rows.length;

   for(var i=0; i<rowCount; i++) {
    var row = table.rows[i];
    var chkbox = row.cells[0].childNodes[0];
    if(null != chkbox && true == chkbox.checked) {
     if(rowCount <= 1) {
      alert("Cannot delete all the rows.");
      break;
     }
     table.deleteRow(i);
     rowCount--;
     i--;
    }


   }
   }catch(e) {
    alert(e);
   }
  }

 </SCRIPT>
</HEAD>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
<BODY>

 <INPUT type="button" value="Add Row" onclick="addRow('dataTable')" />

 <INPUT type="button" value="Delete Row" onclick="deleteRow('dataTable')" />

 <form width="350px" border="1" action="index.php" method="get" class="w3-row-padding">
  <table id="dataTable">
  <TR>
   <TD><INPUT type="checkbox" name="chk"/></TD>
   <TD><INPUT type="text" name="txt[]" value="<?php echo 'adfas!'?>"/></TD>
  </TR>
  </table>
  <input class="w3-btn w3-green" type="submit" name="test" value="lol">
 </form>
<?php
if(isset($_GET['test'])){
	$cnt=count($_GET['txt']);
	for($i=0; $i<$cnt;$i++)
		echo $_GET['txt'][$i];

}
?>
</BODY>
</HTML>
