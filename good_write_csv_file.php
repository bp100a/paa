<?php
require 'utilities.php'; session_start();



$cxn = connect_to_db();

$filename = $_POST['filename'];
echo $_POST['filename'];

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/htm14/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset-iso-8859-1">
<link rel="stylesheet" type="text/css" href="mystyles.css" />
<title>Write Scores</title>



</head>
<body>
<table border="0" width="95%">
  <tr>
    <td colspan="2" width="95%" valign="bottom" align="left"
     type="text/css">
     <img src="njit_tag.jpg" width="233" height="60" border="0" />
     </td>
  </tr>
  <tr>
    <td colspan="2" width="95%" align="center" valign="center">
      <h1>Write Scores to CSV File</h1>
    </td>
  </tr>
</table>

<?php


  //
  // execute sql query
  //
  $query = sprintf( 'SELECT * FROM results' );
  $result = mysql_query( $query, $cxn ) or die( mysql_error( $cxn ) );
  //
  // send response headers to the browser
  // following headers instruct the browser to treat the data as a csv file called export.csv
  //
  header( 'Content-Type: text/csv' );
  header( 'Content-Disposition: attachment;filename=export.csv' );
  //
  // output header row (if atleast one row exists)
  //
  $row = mysql_fetch_assoc( $result );
  if ( $row )
  {
    echocsv( array_keys( $row ) );
  }
  //
  // output data rows (if atleast one row exists)
  //
  while ( $row )
  {
    echocsv( $row );
    $row = mysql_fetch_assoc( $result );
  }
  //
  // echocsv function
  //
  // echo the input array as csv data maintaining consistency with most CSV implementations
  // * uses double-quotes as enclosure when necessary
  // * uses double double-quotes to escape double-quotes 
  // * uses CRLF as a line separator
  //
  function echocsv( $fields )
  {
    $separator = '';
    foreach ( $fields as $field )
    {
      if ( preg_match( '/\\r|\\n|,|"/', $field ) )
      {
        $field = '"' . str_replace( '"', '""', $field ) . '"';
      }
      echo $separator . $field;
      $separator = ',';
    }
    echo "\r\n";
  }
?>

<table border="0" width="95%">
  <tr>
    <td colspan="2" width="95%" height="100" align="center" valign="bottom">
    <p>Your CSV file was written to the /mysql/data/mydb directory<br />
    Where this application is stored (typically under xampp/htdocs)</p>
    </td>
  </tr>
  <tr>
    <td width="95%" align="center" height="50">
    <input type="submit" value="Return to Administrator Page">
    </td>
  </tr>
</table>

  
</body>
</html>
?>