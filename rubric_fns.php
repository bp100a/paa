<?php
require 'utilities.php'; session_start();

function select_rubric()
{
	$cxn = connect_to_db();
	$rubric = $_POST['rubric'];

	$sql="SELECT RubricID, RubricName FROM rubric";

	$result = mysqli_query($cxn,$sql)
		or die ("couldn't execute query");

/* This function lets the administrator
 * create a rating session record for this
 * particular portfolio rating event.
 * The administrator must provide a name
 * for the rating session and must
 * select a rubric that will be used
 * throughout the session.
 */



	if($result == false)
	{
		echo "<h4>Error: ".mysqli_error($cxn)."</h4>";
	}
	else
	{
		if(mysqli_num_rows($result) < 1)
		{
			echo mysqli_num_rows($result);
		}
		else
		{

			while($row = mysqli_fetch_row($result))
			{
				extract($row);
				$rubricid=$row[0];
				$rubricname=$row[1];

				echo "<form method=\"POST\" action=\"create_rating_session.php\">";
				echo "<table border=\"0\" width=\"95%\" cellpadding=\"20\">";
 
				echo "  <tr>";
				echo "    <td width=\"20%\" align=\"center\">";
 				echo "   </td>";
				echo "    <td width=\"45%\" align=\"left\" type=\"text/css\"
				     style=\"background-color: #669966; color: white; font-weight: bolder;\">";
 				echo "    <input type=\"radio\" name=\"option1\" value=$rubricid>$rubricname";
				echo "</td>";
				echo "    <td width=\"30%\">";
				echo "    </td>";
				echo "  </tr>";
				echo "</table>";

			}


			echo "<table border=\"0\" width=\"95%\">";
			echo "  <tr>";
			echo "    <td colspan=\"2\" height=\"20\">";
			echo "    </td>";
			echo "  </tr>";
			echo "    <td width=\"45%\" height=\"50\" align=\"right\" valign=\"top\">";
			echo "Please enter a name for the new rating session:<br />
			      For example, Spring 2010 Humanities 101";
			echo "    </td> ";
			echo "    <td width=\"40%\" align=\"left\" valign=\"top\" height=\"50\">";
			echo "    <input type=\"text\" name=\"SessionName\">";
			echo "    </td>";
			echo "  </tr>";
			echo "  <tr>";
			echo "    <td width=\"45%\" height=\"50\" align=\"right\" valign=\"top\">";
			echo "Please specify a threshold for this rating session:<br />
				The threshold determines how many points<br />
				a student's scores can differ before<br />
				adjudication is required. The default is 1.";
			echo "    </td> ";
			echo "    <td width=\"40%\" align=\"left\" valign=\"top\" height=\"50\">";
			echo "    <input type=\"text\" name=\"Threshold\" value=\"1\">";
			echo "    </td>";
			echo "  </tr>";
			echo "  <tr>";
			echo "    <td colspan=\"2\" height=\"20\">";
			echo "    </td>";
			echo "  </tr>";
			echo "<tr>";
			echo "    <td colspan=\"2\" align=\"center\" height=\"20\" valign=\"bottom\">";
			echo "<input type=\"submit\" value=\"Create rating session\">";  					
			echo "</td>";
			echo "  </tr>";
			echo "</table>";
			echo "</form>";


	
		}
	}
}
?>
			

	