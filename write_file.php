<?php

require 'utilities.php'; session_start();

$sessionid = $_SESSION['ratingsession'];
$rubricid = $_SESSION['rubricid'];


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/htm14/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset-iso-8859-1">
<link rel="stylesheet" type="text/css" href="mystyles.css" />
<title>Prepare Data for Export</title>


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
      <h1>Prepare Data for Export</h1>
    </td>
  </tr>
</table>




<?php


/* Create Scores Table
 * This module builds a table
 * to get the scores into the
 * right format for exporting to
 * Microsoft Excel. It also
 * calculates the adjudication scores.
 */

require_once('student_fns.php');

$cxn = connect_to_db();
$user = "root";

$getsessionid = "SELECT MAX(RatingSessionID) FROM ratingsession";
$dogetsessionid = mysqli_query($cxn,$getsessionid)
		or die (mysql_error());
$setsessionid = mysqli_fetch_row($dogetsessionid);
$sessionid = $setsessionid[0];


$getrubricid = "SELECT RubricID FROM ratingsession WHERE RatingSessionID = '".$sessionid."'";
$dogetrubric = mysqli_query($cxn,$getrubricid)
	or die(Mysql_error());
$setrubricid = mysqli_fetch_row($dogetrubric);
$rubricid = $setrubricid[0];
echo "At least now we have a rubric id of ".$rubricid."!<br />";


/* Get the number of competencies for
 * the rubric being used in this
 * rating session.
 */

$getnumcomps = "SELECT CompetencyID FROM rubriccontent WHERE RubricID = '".$rubricid."'";
$result4 = mysqli_query($cxn,$getnumcomps)
		or die (mysqli_error());
$numcomps = mysqli_num_rows($result4);
echo "We have ".$numcomps." competencies<br />";

/* First we will build an array of competencies
 * so we can compare when doing the
 * adjudicated scores.
 */


$i=0;
while ($setcompname = mysqli_fetch_row($result4))
{

	$compid = $setcompname[0];
	$competencies[$i] = $compid;
	$getcomp_shrthand = "SELECT CompShorthand FROM competency WHERE CompetencyID = '".$compid."'";
	$doget_shrthand = mysqli_query($cxn,$getcomp_shrthand)
		or die(mysqli_error($cxn));
	$set_shrthand = mysqli_fetch_row($doget_shrthand);
	$shortname = $set_shrthand[0];
	$abbrev[$i] = $shortname;
	$i++;

}

$getnumstudents = "SELECT StudentID FROM studenttoberated WHERE RatingSessionID = '".$sessionid."'";
$dogetnumstudents = mysqli_query($cxn,$getnumstudents)
		or die(mysqli_error($cxn));
$numstudents = mysqli_num_rows($dogetnumstudents);






$tab = "\t";
$cr = "\n";

$i = 0;

$header = "StudentID".$tab."NJIT ID".$tab."First Name".$tab."Last Name".$tab."Rating Session".$tab.
	$abbrev[$i]."-1".$tab.$abbrev[$i]."_2".$tab.$abbrev[$i]."_3"
	.$tab.$abbrev[$i]."_ADJ1".$tab.$abbrev[$i]."_ADJ2".$tab.$abbrev[$i]."_ADJTOTAL".$tab.
	$abbrev[$i+1]."-1".$tab.$abbrev[$i+1]."_2".$tab.$abbrev[$i+1]."_3"
	.$tab.$abbrev[$i+1]."_ADJ1".$tab.$abbrev[$i+1]."_ADJ2".$tab.$abbrev[$i+1]."_ADJTOTAL".$tab.
	$abbrev[$i+2]."-1".$tab.$abbrev[$i+2]."_2".$tab.$abbrev[$i+2]."_3"
	.$tab.$abbrev[$i+2]."_ADJ1".$tab.$abbrev[$i+2]."_ADJ2".$tab.$abbrev[$i+2]."_ADJTOTAL".$tab.
	$abbrev[$i+3]."-1".$tab.$abbrev[$i+3]."_2".$tab.$abbrev[$i+3]."_3"
	.$tab.$abbrev[$i+3]."_ADJ1".$tab.$abbrev[$i+3]."_ADJ2".$tab.$abbrev[$i+3]."_ADJTOTAL".$tab.
	$abbrev[$i+4]."-1".$tab.$abbrev[$i+4]."_2".$tab.$abbrev[$i+4]."_3"
	.$tab.$abbrev[$i+4]."_ADJ1".$tab.$abbrev[$i+4]."_ADJ2".$tab.$abbrev[$i+4]."_ADJTOTAL".$tab.
	$abbrev[$i+5]."-1".$tab.$abbrev[$i+5]."_2".$tab.$abbrev[$i+5]."_3"
	.$tab.$abbrev[$i+5]."_ADJ1".$tab.$abbrev[$i+5]."_ADJ2".$tab.$abbrev[$i+5]."_ADJTOTAL".$tab.
	$abbrev[$i+6]."-1".$tab.$abbrev[$i+6]."_2".$tab.$abbrev[$i+6]."_3"
	.$tab.$abbrev[$i+6]."_ADJ1".$tab.$abbrev[$i+6]."_ADJ2".$tab.$abbrev[$i+6]."_ADJTOTAL".$tab.
	$abbrev[$i+7]."-1".$tab.$abbrev[$i+7]."_2".$tab.$abbrev[$i+7]."_3"
	.$tab.$abbrev[$i+7]."_ADJ1".$tab.$abbrev[$i+7]."_ADJ2".$tab.$abbrev[$i+7]."_ADJTOTAL".$tab.
	$abbrev[$i+8]."-1".$tab.$abbrev[$i+8]."_2".$tab.$abbrev[$i+8]."_3"
	.$tab.$abbrev[$i+8]."_ADJ1".$tab.$abbrev[$i+8]."_ADJ2".$tab.$abbrev[$i+8]."_ADJTOTAL".$tab.
	$abbrev[$i+9]."-1".$tab.$abbrev[$i+9]."_2".$tab.$abbrev[$i+9]."_3"
	.$tab.$abbrev[$i+9]."_ADJ1".$tab.$abbrev[$i+9]."_ADJ2".$tab.$abbrev[$i+9]."_ADJTOTAL".$cr;

for ($i = 0; $i < $numstudents; $i++)
{
$getstudentinfo = "SELECT StudentID FROM studenttoberated WHERE RatingSessionID = '".$sessionid."'";
$dogetstudentinfo = mysqli_query($cxn,$getstudentinfo)
		or die(mysqli_error($cxn));
$setstudentinfo = mysqli_fetch_row($dogetstudentinfo);
$studentid = $setstudentinfo[0];


$getstudentname = "SELECT * FROM results WHERE StudentID = '".$studentid."'";
$dogetname = mysqli_query($cxn,$getstudentname)
		or die(mysqli_error($cxn));
$setstudentname = mysqli_fetch_row($dogetname);
$njitid = $setstudentname[0];
$first = $setstudentname[1];
$last = $setstudentname[2];
$ratingsession = $setstudentname[3];

$comp1_1 = $setstudentname[4];
$comp1_2 = $setstudentname[5];
$comp1_3 = $setstudentname[6];
$comp1_a1 = $setstudentname[7];
$comp1_a2 = $setstudentname[8];
$comp1_t = $setstudentname[9];

$comp2_1 = $setstudentname[10];
$comp2_2 = $setstudentname[11];
$comp2_3 = $setstudentname[12];
$comp2_a1 = $setstudentname[13];
$comp2_a2 = $setstudentname[14];
$comp2_t = $setstudentname[15];

$comp3_1 = $setstudentname[16];
$comp3_2 = $setstudentname[17];
$comp3_3 = $setstudentname[18];
$comp3_a1 = $setstudentname[19];
$comp3_a2 = $setstudentname[20];
$comp3_t = $setstudentname[21];

$comp4_1 = $setstudentname[22];
$comp4_2 = $setstudentname[23];
$comp4_3 = $setstudentname[24];
$comp4_a1 = $setstudentname[25];
$comp4_a2 = $setstudentname[26];
$comp4_t = $setstudentname[27];


$comp5_1 = $setstudentname[28];
$comp5_2 = $setstudentname[29];
$comp5_3 = $setstudentname[30];
$comp5_a1 = $setstudentname[31];
$comp5_a2 = $setstudentname[32];
$comp5_t = $setstudentname[33];

$comp6_1 = $setstudentname[34];
$comp6_2 = $setstudentname[35];
$comp6_3 = $setstudentname[36];
$comp6_a1 = $setstudentname[37];
$comp6_a2 = $setstudentname[38];
$comp6_t = $setstudentname[39];

$comp7_1 = $setstudentname[40];
$comp7_2 = $setstudentname[41];
$comp7_3 = $setstudentname[42];
$comp7_a1 = $setstudentname[43];
$comp7_a2 = $setstudentname[44];
$comp7_t = $setstudentname[45];

$comp8_1 = $setstudentname[46];
$comp8_2 = $setstudentname[47];
$comp8_3 = $setstudentname[48];
$comp8_a1 = $setstudentname[49];
$comp8_a2 = $setstudentname[50];
$comp8_t = $setstudentname[51];

$comp9_1 = $setstudentname[52];
$comp9_2 = $setstudentname[53];
$comp9_3 = $setstudentname[54];
$comp9_a1 = $setstudentname[55];
$comp9_a2 = $setstudentname[56];
$comp1_t = $setstudentname[57];

$comp10_1 = $setstudentname[58];
$comp10_2 = $setstudentname[59];
$comp10_3 = $setstudentname[60];
$comp10_a1 = $setstudentname[61];
$comp10_a2 = $setstudentname[62];
$comp10_t = $setstudentname[63];

$row = $studentid.$tab.$njitid.$tab.$first.$tab.$last.$tab.$ratingsession.$tab.
	$comp1_1.$tab.$comp1_2.$tab.$comp1_3
	.$tab.$comp1_a1.$tab.$comp1_a2.$tab.$comp1_t.$tab.
	$comp2_1.$tab.$comp2_2.$tab.$comp2_3
	.$tab.$comp2_a1.$tab.$comp2_a2.$tab.$comp2_t.$tab.
	$comp3_1.$tab.comp3_2.$tab.comp3_3
	.$tab.comp3_a1.$tab.comp3_a2.$tab.comp3_t.$tab.
	$comp4_1.$tab.$comp4_2.$tab.$comp4_3
	.$tab.$comp4_a1.$tab.$comp4_a2.$tab.$comp4_t.$tab.
	$comp5_1.$tab.$comp5_2.$tab.$comp5_3
	.$tab.$comp5_a1.$tab.$comp5_a2.$tab.$comp5_t.$tab.
	$comp6_1.$tab.$comp6_2.$tab.$comp6_3
	.$tab.$comp6_a1.$tab.$comp6_a2.$tab.$comp6_t.$tab.
	$comp7_1.$tab.$comp7_2.$tab.$comp7_3
	.$tab.$comp7_a1.$tab.$comp7_a2.$tab.$comp7_t.$tab.
	$comp8_1.$tab.$comp8_2.$tab.$comp8_3
	.$tab.$comp8_a1.$tab.$comp8_a2.$tab.$comp8_t.$tab.
	$comp9_1.$tab.$comp9_2.$tab.$comp9_3
	.$tab.$comp9_a1.$tab.$comp9_a2.$tab.$comp9_t.$tab.
	$comp10_1.$tab.$comp10_2.$tab.$comp10_3
	.$tab.$comp10_a1.$tab.$comp10_a2.$tab.$comp10_t.$cr;
}













