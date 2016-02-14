<?php
require 'utilities.php'; session_start();

/* Find Adjudications
 * This module checks the student scores
 * to determine whether adjudication is
 * required. If so, it performs the adjudication
 * and stores the data to the adjudicatedscores table.
 * Otherwise, it simply copies the scores
 * from the two raters into the adjudicatedscores table.
 */

require_once('student_fns.php');

$cxn = connect_to_db();
$user = "root";
$password = "gewbgttl";
$dbase = "mydb";

$cxn = mysqli_connect ($host,$user,$password,$dbase)
	or die ("couldn't connect to database");

$getnumcomps = "SELECT CompetencyID FROM rubriccontent WHERE RubricID = '".$rubricid."'";
$result4 = mysqli_query($cxn,$getnumcomps)
		or die (mysqli_error());
$numcomps = mysqli_num_rows($result4);


$getratingsession = "SELECT RatingSessionID FROM ratingsession WHERE MAX(RatingSessionID)";
$result = mysqli_query($cxn,$getratingsession)
		or die ("Couldn't retrieve current rating session.");
$temp = mysqli_fetch_row($result);
$sessionid = $temp[0];


$getstudentlist = "SELECT Student_StudentID FROM studenttoberated WHERE RatingSessionID = '".$sessionid."'";
$result1 = mysqli_query($cxn,$getstudentlist)
		or die ("Couldn't retrieve list of students being rated.");
$numstudents = mysqli_num_rows($result1);

while ($temp1 = mysqli_fetch_assoc($result1,MYSQLI_NUM))
{
	$studentarray[]=$temp1;
}


for ($i = 0; $i < $numstudents; $i++)
{
	$studentid = $studentarray[$i];

	$getratingid = "SELECT PortfolioRatingID, UserID, IsAdjudicator FROM portfoliorating 
			WHERE StudentID = '".$studentarray[$i]."'";
	$result2 = mysqli_query($cxn,$getratingid)
			or die ("Couldn't retrieve rating session id.");
	$numratings = mysqli_num_rows($result2);

	for ($j = 0; $j < $numratings; $j++)
	{
		$temp2 = mysqli_fetch_row($result2);
		$ratingid[$j] = $temp2[0];
		$rater[$j] = $temp2[1];
		$adjudicated[$j] = $temp2[2];

		for ($k = 0; $k < $numcomps; $k++)
		{

			$getscores = "SELECT CompetencyID, Score FROM sessionscoring
				WHERE PortfolioRatingID = '".$ratingid[$j]."'"
				ORDER by CompetencyID;

			$result3 = mysqli_query($cxn,$getscores)
			$temp3 = mysqli_fetch_array($result3, MYSQLI_NUM);
			$comp[$k] = $temp3[0];
			$score[$k] = $temp3[1];

			$ratings = array();
			$ratings[$k] = array
				(
				'$studentid'=>array
					(
					'$rater'=>array
						(
						'$comp','$score'
						)
					)
				);

		}

	}
	for ($k=0; $k < $numcomps, $k++)
	{
		foreach($ratings['$studentid']['$rater']['$comp']['$score'] as $temp)
		{
			
		











}












$first_name = $_POST['FirstName'];
$last_name = $_POST['LastName'];
$njit_id = $_POST['NJITStudentID'];
$section = $_POST['Section'];

/* Check to make sure the rater
 * did not enter any dashes or other characters
 * in the NJIT student id
 */


$string_to_be_stripped = $njit_id;
$njit_id = ereg_replace("[^A-Za-z0-9]", "", $string_to_be_stripped);
$timestamp = date("Y-m-d");


$cxn = connect_to_db();
$user = "root";
$password = "gewbgttl";
$dbase = "mydb";

$cxn = mysqli_connect ($host,$user,$password,$dbase)
	or die ("couldn't connect to database");


$getstudentid = "SELECT StudentID FROM student WHERE
        NJITstudentID = '".$njit_id."'";

$result1 = mysqli_query($cxn,$getstudentid)
	or die ("couldn't execute query");
$temp1 = mysqli_fetch_row($result1);
$studentid = $temp1[0];


$getratingsession = "SELECT RatingSessionID FROM ratingsession WHERE
	RatingDate = CURDATE()";

$result2 = mysqli_query($cxn,$getratingsession)
	or die ("couldn't execute query");
$temp2 = mysqli_fetch_row($result2);
$sessionid = $temp2[0];

$getrubric="SELECT RubricID FROM ratingsession
		WHERE RatingSessionID = '".$sessionid."'";

$result3 = mysqli_query($cxn,$getrubric)
	or die ("couldn't execute query");
$temp3 = mysqli_fetch_row($result3);
$rubric = $temp3[0];
	
is_rated($studentid,$sessionid);
       

if (isset($studentID))
{
	$insertstudentid = "INSERT INTO studenttoberated VALUES
		('$studentid', '$sessionid')";
	$result = mysqli_query($cxn,$insertstudentid);
}
else
{

	$createstudent = "INSERT INTO student (NJITStudentID, Section, FirstName, LastName, CreateTime) VALUES
        ('$njit_id', '$section', '$first_name', '$last_name', CURDATE())";
	$result4 = mysqli_query($cxn,$createstudent);
	if (!$result4)
	{
		echo "Student information could not be written to the database.<br />";
		echo "Please notify the session administrator.";
		echo "<h4>Error: ".mysqli_error($cxn)."</h4>";

	}
	else
	{
		$getnewstudentid = "SELECT StudentID FROM student WHERE
			NJITStudentID = '".$njit_id."'";

		$result5 = mysqli_query($cxn,$getnewstudentid);
		$temp5 = mysqli_fetch_row($result5);
		$studentid = $temp5[0];
	}
}
?>

<html>
	<body>
	<form method="POST" action="build_rubric.php">
	<input type="hidden" name="rubric" value="<?php echo $rubric?>">
	<input type="hidden" name="student" value="<?php echo $student?>">
	</form>
	</body>
</html>
