<?php

require_once 'utilities.php'; session_start();

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
$password = "gewbgttl";
$dbase = "mydb";

$cxn = mysqli_connect ($host,$user,$password,$dbase)
	or die ("couldn't connect to database");

if (isset($_SESSION['ratingsession']))
{
	$sessionid = $_SESSION['ratingsession'];
}
else
{

	
	$getsessionid = "SELECT MAX(RatingSessionID) FROM ratingsession";
	$dogetsessionid = mysqli_query($cxn,$getsessionid)
			or die (mysql_error());
	$setsessionid = mysqli_fetch_row($dogetsessionid);
	$sessionid = $setsessionid[0];
}

if (isset($_SESSION['rubricid']))
{
	$rubricid = $_SESSION['rubricid'];
}
else
{

	$getrubricid = "SELECT RubricID FROM ratingsession WHERE RatingSessionID = '".$sessionid."'";
	$dogetrubric = mysqli_query($cxn,$getrubricid)
		or die(Mysql_error());
	$setrubricid = mysqli_fetch_row($dogetrubric);
	$rubricid = $setrubricid[0];
}

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


/* First we will clear the current contents
 * out of the results table.
 */

$cleartable = "TRUNCATE TABLE results";
$docleartable = mysqli_query($cxn,$cleartable)
	or die(mysqli_error($cxn));


/* Get the number of competencies for
 * the rubric being used in this
 * rating session.
 */

$getnumcomps = "SELECT CompetencyID FROM rubriccontent WHERE RubricID = '".$rubricid."'
		ORDER BY cmporder ASC";
$result4 = mysqli_query($cxn,$getnumcomps)
		or die (mysqli_error());
$numcomps = mysqli_num_rows($result4);


/* First we will build an array of competencies
 * so we can compare when doing the
 * adjudicated scores.
 */


$i=0;
while ($setcompname = mysqli_fetch_row($result4))
{

	$compid = $setcompname[0];
	$competencies[$i] = $compid;
	$i++;


}




/* Get the list of students who
 * have been rated during this
 * rating session. We are going to 
 * build a table of scores for each
 * student and see if adjudication
 * is required. The new table will
 * make exporting the data to
 * Microsoft Excel much easier.
 *
 * First we get an array of all students
 * who have been rated thus far. Note
 * that we need to check that they have
 * been fully rated.
 */


$getstudentlist = "SELECT DISTINCT StudentID FROM studenttoberated WHERE RatingSessionID = '".$sessionid."'";
$result1 = mysqli_query($cxn,$getstudentlist)
		or die ("Couldn't retrieve list of students who have been rated.");
$numstudents = mysqli_num_rows($result1);


a:
while ($setstudent = mysqli_fetch_row($result1))
{
	$studentid = $setstudent[0];

	if ($studentid == 0)
	{
		goto a;
	}

	$getNJITid = "SELECT NJITStudentID, Section, URL FROM student WHERE StudentID = '".$studentid."'";
	$result5 = mysqli_query($cxn,$getNJITid)
			or die ("Couldn't get NJIT ID for student.");
	$temp5 = mysqli_fetch_row($result5);
	$njit_id = $temp5[0];
	$section = $temp5[1];
	$url = $temp5[2];

	$getfullname = "SELECT FirstName, LastName FROM student WHERE studentid = '".$studentid."'";
	$dogetfullname = mysqli_query($cxn,$getfullname)
			or die ("couldn't retrieve student name.");
	$setfullname = mysqli_fetch_row($dogetfullname);
	$firstname = $setfullname[0];
	$lastname = $setfullname[1];
	
	$writeinfo = "INSERT INTO results (id, NJITid, Section, FirstName, LastName, RatingSessionID, URL)
			VALUES ('$studentid', '$njit_id', '$section', '$firstname', '$lastname', 
			'$sessionid', '$url')";
	$dowriteinfo = mysqli_query($cxn,$writeinfo)
			or die (mysqli_error($cxn));



/* Now for each student we get a list of all their
 * portfolio ratings, which tells us which users
 * have rated them.
 */





/* First we get the userids of the users who
 * rated the student so we can assign one
 * as the first scorer and one as the second
 * scorer and one as the adjudicator.
 */


	$getraterids = "SELECT DISTINCT s.portfolioratingid, p.userid, p.isadjudicator
			FROM sessionscoring s, portfoliorating p, rubriccontent r
			WHERE p.studentid = '".$studentid."'
			AND p.ratingsessionid = '".$sessionid."'
			AND p.portfolioratingid = s.portfolioratingid
			AND r.rubricid = '".$rubricid."'
			GROUP BY p.userid
			ORDER BY p.isadjudicator ASC";
	$result2 = mysqli_query($cxn,$getraterids)
			or die ("Couldn't retrieve scores for this student.");
	$numratings = mysqli_num_rows($result2);



	switch ($numratings)
	{
	case 1:

		$temp2 = mysqli_fetch_row($result2);
		$ratingid = $temp2[0];
		$rater = $temp2[1];
		$adjudicated = $temp2[2];

		$getscores = "SELECT DISTINCT sessionscoring.competencyid, sessionscoring.score 
			FROM rubriccontent LEFT JOIN sessionscoring 
			ON sessionscoring.competencyid = rubriccontent.competencyid  
			WHERE sessionscoring.portfolioratingid = '".$ratingid."' ORDER BY rubriccontent.cmporder ASC";
		$result3 = mysqli_query($cxn,$getscores);
		$numscores = mysqli_num_rows($result3);

			
		$k = 1;
		$m = 1;
		$n = 2;
		
		while ($temp3 = mysqli_fetch_row($result3))
		{
			$comp = $temp3[0];
			$score = $temp3[1];
			$compcol = "comp".$k."score".$m;
			$adjcol = "comp".$k."adj".$m;
			$compcol2 = "comp".$k."score".$n;
			$adjcol2 = "comp".$k."adj".$n;


			$updaterow = "UPDATE results SET $compcol = $score, 
				$adjcol = $score, $compcol2 = $score,
				$adjcol2 = $score WHERE id = '".$studentid."'";	
			$result6 = mysqli_query($cxn,$updaterow)
				or die (mysqli_error($cxn));
			$k++;

		}

		break;
	case 2:


		$temp2 = mysqli_fetch_row($result2);
		$ratingid1 = $temp2[0];
		$rater1 = $temp2[1];
		$adjudicated1 = $temp2[2];

		$temp3 = mysqli_fetch_row($result2);
		$ratingid2 = $temp3[0];
		$rater2 = $temp3[1];
		$adjudicated2 = $temp3[2];

		
		$getallscores = "SELECT DISTINCT s.competencyid, r.cmporder, s.score, s.portfolioratingid,
				p.userid, p.isadjudicator
				FROM sessionscoring s, portfoliorating p, rubriccontent r
				WHERE p.studentid = '".$studentid."'
				AND p.ratingsessionid = '".$sessionid."'
				AND p.portfolioratingid = s.portfolioratingid
				AND r.competencyid = s.competencyid
				AND r.rubricid = '".$rubricid."'
				ORDER BY s.portfolioratingid";
		$dogetall = mysqli_query($cxn,$getallscores)
				or die ("Could not get scores for this student");
		
		while ($temp4 = mysqli_fetch_row($dogetall))
		{
			



			$compid = $temp4[0];
			$compnum = $temp4[1];
			$score = $temp4[2];
			$pfid = $temp4[3];
			$user = $temp4[4];
			$isadj = $temp4[5];

			if ($user == $rater1)
			{
				$m = 1;
			}
			else if ($user === $rater2)
			{
				$m = 2;
			}


			$compcol = "comp".$compnum."score".$m;
			$adjcol = "comp".$compnum."adj".$m;

			$updaterow = "UPDATE results SET $compcol = $score, 
				$adjcol = $score 
				WHERE id = '".$studentid."'";	
			$result6 = mysqli_query($cxn,$updaterow)
				or die (mysqli_error($cxn));
		}

		break;


	case 3:
		$temp2 = mysqli_fetch_row($result2);
		$ratingid1 = $temp2[0];
		$rater1 = $temp2[1];
		$adjudicated1 = $temp2[2];

		$temp3 = mysqli_fetch_row($result2);
		$ratingid2 = $temp3[0];
		$rater2 = $temp3[1];
		$adjudicated2 = $temp3[2];

		$temp5 = mysqli_fetch_row($result2);
		$ratingid3 = $temp5[0];
		$rater3 = $temp5[1];
		
		if ($temp5[2] == 1)
		{
			$isadj = 1;
		}


		
		$getallscores = "SELECT DISTINCT s.competencyid, r.cmporder, s.score, s.portfolioratingid,
				p.userid, p.isadjudicator
				FROM sessionscoring s, portfoliorating p, rubriccontent r
				WHERE p.studentid = '".$studentid."'
				AND p.ratingsessionid = '".$sessionid."'
				AND p.portfolioratingid = s.portfolioratingid
				AND r.competencyid = s.competencyid
				AND r.rubricid = '".$rubricid."'
				ORDER BY s.portfolioratingid";
		$dogetall = mysqli_query($cxn,$getallscores)
				or die ("Could not get scores for this student");
		
		while ($temp4 = mysqli_fetch_row($dogetall))
		{
			



			$compid = $temp4[0];
			$compnum = $temp4[1];
			$score = $temp4[2];
			$pfid = $temp4[3];
			$user = $temp4[4];
			$isadj = $temp4[5];

			if ($user == $rater1)
			{
				$m = 1;
				$n = 1;
			}
			else if ($user == $rater2)
			{
				$m = 2;
				$n = 2;
			}
			else if ($isadj == 1)
			{
				$m = 3;
				$n = 2;
			}


			$compcol = "comp".$compnum."score".$m;
			$adjcol = "comp".$compnum."adj".$n;

			$updaterow = "UPDATE results SET $compcol = $score, 
				$adjcol = $score 
				WHERE id = '".$studentid."'";	
			$result6 = mysqli_query($cxn,$updaterow)
				or die (mysqli_error($cxn));
		}

		break;
	default:
		echo "Error - number of readings is not 1, 2, or 3";
		break;

	}




/* Here we're going to calculate the
 * adjudicated scores.  We need to know
 * the threshold set by the admin. The default
 * threshold is 1 - in other words, scores
 * that are more than 1 point different 
 * will require adjudication. If there are
 * a lot of portfolios to score and
 * the adjudication is taking a lot of time,
 * the admin might want to set the threshold
 * to a higher value.  However, if the threshold
 * is set too high, there will be no reliability
 * in the data.
 */

	for ($k = 1; $k <= $numcomps; $k++)
	{
		$compcol1 = "comp".$k."score1";
		$compcol2 = "comp".$k."score2";
		$compcol3 = "comp".$k."score3";

		$getcompscores = "SELECT $compcol1, $compcol2 FROM results
			WHERE ID = '".$studentid."'";
		$result7 = mysqli_query($cxn,$getcompscores)
			or die (mysqli_error($cxn));
		$temp7 = mysqli_fetch_row($result7);
		$score1 = $temp7[0];
		$score2 = $temp7[1];


		$getadjscore = "SELECT $compcol3 FROM results
				WHERE ID = '".$studentid."'";
		$dogetadj = mysqli_query($cxn,$getadjscore)
			or die (mysqli_error($cxn));
		$setadj = mysqli_fetch_row($dogetadj);
		$score3 = $setadj[0];




		if ($score3 != NULL)
		{


		

			$delta1 = ABS($score1 - $score3);
			$delta2 = ABS($score2 - $score3);

			if ($delta1 < $delta2)
			{
				$newvalue = "comp".$k."adj1";
				$updatevalue = "UPDATE results SET $newvalue = $score1 
					WHERE ID = '".$studentid."'";
				$update = mysqli_query($cxn,$updatevalue)
					or die(mysqli_error($cxn));

			}
			elseif ($delta1 > $delta2)
			{
				$newvalue = "comp".$k."adj1";
				$updatevalue = "UPDATE results SET $newvalue = $score2
					WHERE ID = '".$studentid."'";
				$update = mysqli_query($cxn,$updatevalue)
					or die(mysqli_error($cxn));

			}
			elseif ($delta1 = $delta2)
			{
				$newvalue = "comp".$k."adj1";
				$higherscore = MAX($score1, $score2);
				$updatevalue = "UPDATE results SET $newvalue = $higherscore 
					WHERE ID = '".$studentid."'";
				$update = mysqli_query($cxn,$updatevalue)
					or die(mysqli_error($cxn));

			}
		}					
	
		$adj1col = "comp".$k."adj1";
		$adj2col = "comp".$k."adj2";
		$adjtotcol = "comp".$k."adjtotal";

		$totalscores = "SELECT $adj1col, $adj2col FROM results 
				WHERE ID = '".$studentid."'";
		$result8 = mysqli_query($cxn,$totalscores)
				or die(mysqli_error($cxn));
		$temp8 = mysqli_fetch_row($result8);
		$adjscore1 = $temp8[0];
		$adjscore2 = $temp8[1];

		$adjtotal = $adjscore1 + $adjscore2;
		$writetotal = "UPDATE results SET $adjtotcol = $adjtotal WHERE 
			ID = '".$studentid."'";
		$result = mysqli_query($cxn,$writetotal)
			or die(mysqli_error($cxn));


	}
}

?>


<form method="POST" action="logout.php">
<table border="0" width="95%">
  <tr>
    <td colspan="2" width="95%" height="50" align="center" valign="bottom">
    The data has been written to the database Results table<br />
    You may now log out of the application.
    </td>
  </tr>
  <tr>
    <td width="95%" align="center" height="20">
    <input type="submit" value="Logout">
    </td>
  </tr>
</table>
</form>
</body>
</html>