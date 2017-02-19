#!/usr/bin/php -q
<?php
####################
# This Script is intended to use with ems-collector (see https://github.com/maniac103/ems-collector), 
# specificly the mySQL Database that you could configure with it.
#
# Put this script somewhere you want and create a cron entry for it:
# crontab -e
# * * * * * /path/to/ems_to_openhab.php >/dev/null 2>&1
# this executes the script every minute. feel free to change it.
#
# the variables $url and $shcommand needs to be adapted to your setup
# also, you might want to change the $ItemName to diffrent names of your desire.
# You can get a list of sensor ids and thier names looking in the "sensors" table in the "ems_data" database
####################
# adapt this to your mysql setup
$mysql_host = "localhost";
$mysql_user = "emsusername";
$mysql_pass = "emspassword";
$mysql_db = "ems_data";

# est. connection to mysql DB
global $mysql_host, $mysql_user, $mysql_pass;
$con=mysqli_connect($mysql_host, $mysql_user, $mysql_pass, $mysql_db)
# error check
 or die("no connection to database possible: " . mysql_error());

# Select last Value from boolean_data table by choosen Sensor ID
$result = mysqli_query($con,"SELECT value FROM boolean_data WHERE sensor = '106' ORDER BY `id` DESC LIMIT 0,1");
while($row = mysqli_fetch_array($result)) {
	$ItemName = "HEIZUNG_3WEGE_VENTIL";
	$ItemValue=$row['value'];
	doPostRequest($ItemName, $ItemValue);
}
$result = mysqli_query($con,"SELECT value FROM numeric_data WHERE sensor = '15' ORDER BY `id` DESC LIMIT 0,1");
while($row = mysqli_fetch_array($result)) {
	$ItemName = "HEIZUNG_AKT_LEISTUNG";
	$ItemValue=$row['value'];
	doPostRequest($ItemName, $ItemValue);
}
$result = mysqli_query($con,"SELECT value FROM boolean_data WHERE sensor = '101' ORDER BY `id` DESC LIMIT 0,1");
while($row = mysqli_fetch_array($result)) {
	$ItemName = "HEIZUNG_BRENNER";
	$ItemValue=$row['value'];
	doPostRequest($ItemName, $ItemValue);
}
$result = mysqli_query($con,"SELECT value FROM boolean_data WHERE sensor = '100' ORDER BY `id` DESC LIMIT 0,1");
while($row = mysqli_fetch_array($result)) {
	$ItemName = "HEIZUNG_FLAMME";
	$ItemValue=$row['value'];
	doPostRequest($ItemName, $ItemValue);
}
$result = mysqli_query($con,"SELECT value FROM numeric_data WHERE sensor = '17' ORDER BY `id` DESC LIMIT 0,1");
while($row = mysqli_fetch_array($result)) {
	$ItemName = "HEIZUNG_FLAMMENSTROM";
	$ItemValue=$row['value'];
	doPostRequest($ItemName, $ItemValue);
}
$result = mysqli_query($con,"SELECT value FROM numeric_data WHERE sensor = '2' ORDER BY `id` DESC LIMIT 0,1");
while($row = mysqli_fetch_array($result)) {
	$ItemName = "HEIZUNG_KESSEL_IST_TEMP";
	$ItemValue=$row['value'];
	doPostRequest($ItemName, $ItemValue);
}	
$result = mysqli_query($con,"SELECT value FROM numeric_data WHERE sensor = '24' ORDER BY `id` DESC LIMIT 0,1");
while($row = mysqli_fetch_array($result)) {
	$ItemName = "HEIZUNG_KESSEL_PUMP_MOD";
	$ItemValue=$row['value'];
	doPostRequest($ItemName, $ItemValue);
}
$result = mysqli_query($con,"SELECT value FROM numeric_data WHERE sensor = '1' ORDER BY `id` DESC LIMIT 0,1");
while($row = mysqli_fetch_array($result)) {
	$ItemName = "HEIZUNG_KESSEL_SOLL_TEMP";
	$ItemValue=$row['value'];
	doPostRequest($ItemName, $ItemValue);
}
$result = mysqli_query($con,"SELECT value FROM numeric_data WHERE sensor = '22' ORDER BY `id` DESC LIMIT 0,1");
while($row = mysqli_fetch_array($result)) {
	$ItemName = "HEIZUNG_WW_BEREITUNGEN";
	$ItemValue=$row['value'];
	doPostRequest($ItemName, $ItemValue);
}
$result = mysqli_query($con,"SELECT value FROM boolean_data WHERE sensor = '110' ORDER BY `id` DESC LIMIT 0,1");
while($row = mysqli_fetch_array($result)) {
	$ItemName = "HEIZUNG_WW_BEREIT_STATUS";
	$ItemValue=$row['value'];
	doPostRequest($ItemName, $ItemValue);
}
$result = mysqli_query($con,"SELECT value FROM numeric_data WHERE sensor = '21' ORDER BY `id` DESC LIMIT 0,1");
while($row = mysqli_fetch_array($result)) {
	$ItemName = "HEIZUNG_WW_BEREITZEIT";
	$ItemValue=$row['value'];
	doPostRequest($ItemName, $ItemValue);
}
$result = mysqli_query($con,"SELECT value FROM numeric_data WHERE sensor = '4' ORDER BY `id` DESC LIMIT 0,1");
while($row = mysqli_fetch_array($result)) {
	$ItemName = "HEIZUNG_WW_IST_TEMP";
	$ItemValue=$row['value'];
	doPostRequest($ItemName, $ItemValue);
}
$result = mysqli_query($con,"SELECT value FROM numeric_data WHERE sensor = '3' ORDER BY `id` DESC LIMIT 0,1");
while($row = mysqli_fetch_array($result)) {
	$ItemName = "HEIZUNG_WW_SOLL_TEMP";
	$ItemValue=$row['value'];
	doPostRequest($ItemName, $ItemValue);
}
$result = mysqli_query($con,"SELECT value FROM numeric_data WHERE sensor = '19' ORDER BY `id` DESC LIMIT 0,1");
while($row = mysqli_fetch_array($result)) {
	$ItemName = "HEIZUNG_BETRIEBSZEIT";
	$ItemValue=$row['value'];
	doPostRequest($ItemName, $ItemValue);
}
$result = mysqli_query($con,"SELECT value FROM boolean_data WHERE sensor = '107' ORDER BY `id` DESC LIMIT 0,1");
while($row = mysqli_fetch_array($result)) {
	$ItemName = "HEIZUNG_ZIRKULATION";
	$ItemValue=$row['value'];
	doPostRequest($ItemName, $ItemValue);
}
$result = mysqli_query($con,"SELECT value FROM boolean_data WHERE sensor = '102' ORDER BY `id` DESC LIMIT 0,1");
while($row = mysqli_fetch_array($result)) {
	$ItemName = "HEIZUNG_ZUENDUNG";
	$ItemValue=$row['value'];
	doPostRequest($ItemName, $ItemValue);
}
# Close Connection
mysqli_close($con);

# This function pushes the data to openhab via REST API an curl
function doPostRequest($OHitem, $OHdata) {
	$url = "http://192.168.0.100:8080/rest/items/" . $OHitem . "/state";
	$shcommand = ("curl --silent -X PUT --header \"Content-Type: text/plain\" --header \"Accept: application/json\" -u myohuser:myohuserpassword  -d " . $OHdata . " " . $url);
	# uncomment below for debug output on console
	#Echo $shcommand;
	$output = shell_exec($shcommand);
	return;
}
?>