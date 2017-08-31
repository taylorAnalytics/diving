<?php
/* The purpose of this script is to display the table with the results of the search.
 */
include('includes/functions.php');
if (isset($_GET['sortBy'])) {
	$course=$_GET['sortBy'];
	if ($_GET['order']=='asc') {
		usort($shopData, 'sortByCourseAsc');
	} elseif ($_GET['order']=='desc') {
		usort($shopData, 'sortByCourseDesc');
	}
}

?>


<table class="result">
<tr><th>Dive shop name</th><th>Currency</th>
<?php
// For each of the dive offer elements, check if the element has been selected in a checkbox & display the heading & the <td> with the data for it
if (isset($_POST['DSDprice'])) {
	echo '<th><a href="diveshopsearch.php?page=view&sortBy=DSDprice&order=';
	if (isset($_GET['order'])) {
		if ($_GET['sortBy']=='DSDprice') {
			if ($_GET['order']=='asc') {
				echo 'desc';
			} elseif ($_GET['order']=='desc') {
				echo 'asc';
			}
		} else {
			echo 'asc';
		}
	} else {
		echo 'asc';
	}
	echo '">DSD price</a></th>';
}
if (isset($_POST['OWDprice'])) {
	echo '<th><a href="diveshopsearch.php?page=view&sortBy=OWDprice&order=';
	if (isset($_GET['order'])) {
		if ($_GET['sortBy']=='OWDprice') {
			if ($_GET['order']=='asc') {
				echo 'desc';
			} elseif ($_GET['order']=='desc') {
				echo 'asc';
			}
		} else {
			echo 'asc';
		}
	} else {
		echo 'asc';
	}
	echo '">OWD price</a></th>';
}
if (isset($_POST['AOWDprice'])) {
	echo '<th><a href="diveshopsearch.php?page=view&sortBy=AOWDprice&order=';
	if (isset($_GET['order'])) {
		if ($_GET['sortBy']=='AOWDprice') {
			if ($_GET['order']=='asc') {
				echo 'desc';
			} elseif ($_GET['order']=='desc') {
				echo 'asc';
			}
		} else {
			echo 'asc';
		}
	} else {
		echo 'asc';
	}
	echo '">AOWD price</a></th>';
}
if (isset($_POST['RESCUEprice'])) {
	echo '<th><a href="diveshopsearch.php?page=view&sortBy=RESCUEprice&order=';
	if (isset($_GET['order'])) {
		if ($_GET['sortBy']=='RESCUEprice') {
			if ($_GET['order']=='asc') {
				echo 'desc';
			} elseif ($_GET['order']=='desc') {
				echo 'asc';
			}
		} else {
			echo 'asc';
		}
	} else {
		echo 'asc';
	}
	echo '">Rescue price</a></th>';
}
if (isset($_POST['DMprice'])) {
	echo '<th><a href="diveshopsearch.php?page=view&sortBy=DMprice&order=';
	if (isset($_GET['order'])) {
		if ($_GET['sortBy']=='DMprice') {
			if ($_GET['order']=='asc') {
				echo 'desc';
			} elseif ($_GET['order']=='desc') {
				echo 'asc';
			}
		} else {
			echo 'asc';
		}
	} else {
		echo 'asc';
	}
	echo '">DM price</a></th>';
}
if (isset($_POST['IDCprice'])) {
	echo '<th><a href="diveshopsearch.php?page=view&sortBy=IDCprice&order=';
	if (isset($_GET['order'])) {
		if ($_GET['sortBy']=='IDCprice') {
			if ($_GET['order']=='asc') {
				echo 'desc';
			} elseif ($_GET['order']=='desc') {
				echo 'asc';
			}
		} else {
			echo 'asc';
		}
	} else {
		echo 'asc';
	}
	echo '">IDC price</a></th>';
}
if (isset($_POST['dayTrip'])) {
	echo '<th>Day trip # of dives</th><th><a href="diveshopsearch.php?page=view&sortBy=daytripPrice&order=';
	if (isset($_GET['order'])) {
		if ($_GET['sortBy']=='daytripPrice') {
			if ($_GET['order']=='asc') {
				echo 'desc';
			} elseif ($_GET['order']=='desc') {
				echo 'asc';
			}
		} else {
			echo 'asc';
		}
	} else {
		echo 'asc';
	}
	echo '">Daytrip price</a></th>
	<th><a href="diveshopsearch.php?page=view&sortBy=daytripPricePerDive&order=';
	if (isset($_GET['order'])) {
		if ($_GET['sortBy']=='daytripPricePerDive') {
			if ($_GET['order']=='asc') {
				echo 'desc';
			} elseif ($_GET['order']=='desc') {
				echo 'asc';
			}
		} else {
			echo 'asc';
		}
	} else {
		echo 'asc';
	}
	echo '">Price per dive in a day trip</a></th>';
}
if (isset($_POST['package'])) {
	echo '<th>Package # of dives</th><th><a href="diveshopsearch.php?page=view&sortBy=packagePrice&order=';
	if (isset($_GET['order'])) {
		if ($_GET['sortBy']=='packagePrice') {
			if ($_GET['order']=='asc') {
				echo 'desc';
			} elseif ($_GET['order']=='desc') {
				echo 'asc';
			}
		} else {
			echo 'asc';
		}
	} else {
		echo 'asc';
	}
	echo '">Package price</a></th>
	<th><a href="diveshopsearch.php?page=view&sortBy=packagePricePerDive&order=';
	if (isset($_GET['order'])) {
		if ($_GET['sortBy']=='packagePricePerDive') {
			if ($_GET['order']=='asc') {
				echo 'desc';
			} elseif ($_GET['order']=='desc') {
				echo 'asc';
			}
		} else {
			echo 'asc';
		}
	} else {
		echo 'asc';
	}
	echo '">Price per dive in a package</a></th>';
}
echo "<th>View profile</th><th>Website</th></tr>";

foreach ($shopData as $key=>$data) {
	echo "<tr><td>{$data['shopName']}</td>";
	switch ($_POST['currency']) {
		case 'local':
			echo "<td>{$data['offerData']['currency']}</td>";
			break;
		case 'USD':
			echo '<td>USD</td>';
			break;
		case 'EUR':
			echo '<td>EUR</td>';
			break;
	}
	if (isset($_POST['DSDprice'])) {
		echo "<td>".number_format($data['offerData']['DSDprice'],0,'.',' ')."</td>";
	}
	if (isset($_POST['OWDprice'])) {
		echo "<td>".number_format($data['offerData']['OWDprice'],0,'.',' ')."</td>";
	}
	if (isset($_POST['AOWDprice'])) {
		echo "<td>".number_format($data['offerData']['AOWDprice'],0,'.',' ')."</td>";
	}
	if (isset($_POST['RESCUEprice'])) {
		echo "<td>".number_format($data['offerData']['RESCUEprice'],0,'.',' ')."</td>";
	}
	if (isset($_POST['DMprice'])) {
		echo "<td>".number_format($data['offerData']['DMprice'],0,'.',' ')."</td>";
	}
	if (isset($_POST['IDCprice'])) {
		echo "<td>".number_format($data['offerData']['IDCprice'],0,'.',' ')."</td>";
	}
	if (isset($_POST['dayTrip'])) {
		echo "<td>{$data['offerData']['daytripDives']}</td>";
		echo "<td>".number_format($data['offerData']['daytripPrice'],0,'.',' ')."</td>";
		echo "<td>".number_format($data['offerData']['daytripPricePerDive'],0,'.',' ')."</td>";
	}
	if (isset($_POST['package'])) {
		echo "<td>{$data['offerData']['packageDives']}</td>";
		echo "<td>".number_format($data['offerData']['packagePrice'],0,'.',' ')."</td>";
		echo "<td>".number_format($data['offerData']['packagePricePerDive'],0,'.',' ')."</td>";
	}
	echo "<td><a href=\"showdiveshop.php?email={$data['email']}&page=show\">View</a></td>";
	echo "<td><a href=\"{$data['website']}\" target=\"_blank\">".substr($data['website'],7)."</a></td></tr>";
}
?>
</table>
