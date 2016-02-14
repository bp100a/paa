<?php
require 'utilities.php'; session_start();

$sessionid = $_SESSION['ratingsession'];
$rubricid = $_SESSION['rubricid'];
$userid = $_SESSION['thisuser'];




$variable = $_POST['compid'];

$cxn = connect_to_db();

$getcompname = "SELECT CompName FROM competency WHERE CompetencyID = '".$variable."'";
$dogetcompname = mysqli_query($cxn,$getcompname);
$result = mysqli_fetch_row($dogetcompname);
$compname = $result[0];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/htm14/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset-iso-8859-1">
<link rel="stylesheet" type="text/css" href="mystyles.css" />
<title>View Variable Scores</title>

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
    <td colspan="2" width="95%" align="center" valign="center" height="100">
      <h1>Scores for <?php echo $compname;?></h1>
    </td>
  </tr>
  <tr>
    <td colspan="2" width="95%" valign="center" align="center" height="100">
    The bar chart below shows the disbursement of scores for<br />
    this particular competency during this rating session.<br />
    The fraction at the end of each bar indicates the number of<br />
    scores for each response and the total number of scores for this<br />
    competency or variable during the rating session.
    </td>
  </tr>
</table>



<?php

/* First we will initialize our variables to zero.
 */

$ones = 0;
$twos = 0;
$threes = 0;
$fours = 0;
$fives = 0;
$sixes = 0;


$getpfid = "SELECT PortfolioRatingID FROM portfoliorating 
			WHERE RatingSessionID = '".$sessionid."'";
$dogetpfid = mysqli_query($cxn,$getpfid)
		or die ("Couldn't execute query");

while ($pfids = mysqli_fetch_row($dogetpfid))
{
	$pfid = $pfids[0];

	$getscore = "SELECT DISTINCT Score FROM sessionscoring WHERE CompetencyID = '".$variable."'
			AND portfolioratingID = '".$pfid."'";
	$dogetscore = mysqli_query($cxn,$getscore)
		or die ("couldn't execute query");
	$getscoreresult = mysqli_fetch_row($dogetscore);
	$score = $getscoreresult[0];

	switch($score)
	{
		case 1:
			$ones++;
			break;
		case 2:
			$twos++;
			break;
		case 3:
			$threes++;
			break;
		case 4:
			$fours++;
			break;
		case 5:
			$fives++;
			break;
		case 6:
			$sixes++;
			break;
	}
}

$total = $ones + $twos + $threes + $fours + $fives + $sixes;

$pcones = ($ones/$total)*100/2;
$pctwos = ($twos/$total)*100/2;
$pcthrees = ($threes/$total)*100/2;
$pcfours = ($fours/$total)*100/2;
$pcfives = ($fives/$total)*100/2;
$pcsixes = ($sixes/$total)*100/2;

echo "<table height=\"30\" width=\"95%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\">";
echo "<tr>";
echo "<td width=\"15%\" height=\"30\">";
echo "Very Strongly Agree";
echo "</td>";

if ($pcsixes == 0)
{
	echo "<td width=\"1%\" style =\"background=color: #009999;\">";\
}
else
{
	echo "<td width=\"".$pcsixes."%\" style="background-color: #009999;\">";
}
echo "</td>";
echo "<td width=\"".(50-$pcsixes)."%\">";
echo $sixes." out of ".$total." portfolios scored thus far";
echo "</td>";
echo "</tr>";
echo "</table>";

echo "<table height=\"30\" width=\"95%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\">";
echo "<tr>";
echo "<td width=\"15%\" height=\"30\">";
echo "Strongly Agree";
echo "</td>";

if ($pcfives == 0)
{
	echo "<td width=\"1%\" style =\"background=color: #009999;\">";\
}
else
{
	echo "<td width=\"".$pcfives."%\" style="background-color: #009999;\">";
}
echo "</td>";
echo "<td width=\"".(50-$pcfives)."%\">";
echo $fives." out of ".$total." portfolios scored thus far";
echo "</td>";
echo "</tr>";
echo "</table>";

echo "<table height=\"30\" width=\"95%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\">";
echo "<tr>";
echo "<td width=\"15%\" height=\"30\">";
echo "Agree";
echo "</td>";

if ($pcfours == 0)
{
	echo "<td width=\"1%\" style =\"background=color: #009999;\">";\
}
else
{
	echo "<td width=\"".$pcfours."%\" style="background-color: #009999;\">";
}
echo "</td>";
echo "<td width=\"".(50-$pcfours)."%\">";
echo $fours." out of ".$total." portfolios scored thus far";
echo "</td>";
echo "</tr>";
echo "</table>";

echo "<table height=\"30\" width=\"95%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\">";
echo "<tr>";
echo "<td width=\"15%\" height=\"30\">";
echo "Disagree";
echo "</td>";

if ($pcthrees == 0)
{
	echo "<td width=\"1%\" style =\"background=color: #009999;\">";\
}
else
{
	echo "<td width=\"".$pcthrees."%\" style="background-color: #009999;\">";
}
echo "</td>";
echo "<td width=\"".(50-$pcthrees)."%\">";
echo $threes." out of ".$total." portfolios scored thus far";
echo "</td>";
echo "</tr>";
echo "</table>";

echo "<table height=\"30\" width=\"95%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\">";
echo "<tr>";
echo "<td width=\"15%\" height=\"30\">";
echo "Strongly Disagree";
echo "</td>";

if ($pctwos == 0)
{
	echo "<td width=\"1%\" style =\"background=color: #009999;\">";\
}
else
{
	echo "<td width=\"".$pctwos."%\" style="background-color: #009999;\">";
}
echo "</td>";
echo "<td width=\"".(50-$pctwos)."%\">";
echo $twos." out of ".$total." portfolios scored thus far";
echo "</td>";
echo "</tr>";
echo "</table>";

echo "<table height=\"30\" width=\"95%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\">";
echo "<tr>";
echo "<td width=\"15%\" height=\"30\">";
echo "Very Strongly Disagree";
echo "</td>";

if ($pcones == 0)
{
	echo "<td width=\"1%\" style =\"background=color: #009999;\">";\
}
else
{
	echo "<td width=\"".$pcones."%\" style="background-color: #009999;\">";
}
echo "</td>";
echo "<td width=\"".(50-$pcones)."%\">";
echo $ones." out of ".$total." portfolios scored thus far";
echo "</td>";
echo "</tr>";
echo "</table>";




?>

</body>
</html>

