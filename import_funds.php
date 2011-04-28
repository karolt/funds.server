<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head> 
<?php

require_once './phpQuery/phpQuery.php';

$url = 'http://www.mbank.pl/indywidualny/inwestycje/sfi/notowania/tab3.pl';
//$url = 'http://www.goldenline.pl/';
$localFile = './funds.html.log';

if (file_exists($localFile)) {
	echo "using local file...";
	$content = file_get_contents($localFile);
} else {
	echo "downloading...";
	$content = file_get_contents($url);
	
	file_put_contents($localFile, $content);	
}

$document	= phpQuery::newDocument($content);
$rows = pq('tr.pr,tr.npr');
//var_dump($rows);
echo "<table>";
$funds = array();
foreach ($rows as $tr) {
	$fund = new Fund();
	 
	$tr = pq($tr);
	$valueNode = $tr->find("td.ar:first");
	$changeNode = $valueNode->next();
	
	$fund->name			= $tr->find("td.tna1")->text();
	$fund->currentValue	= $valueNode->text(); 
	$fund->change		= $changeNode->text();
	$fund->lastUpdate	= $tr->find("td:last")->text();  
	$funds[] = $fund;
	
	//var_dump($fund);
	echo "<tr>";
	echo "<td>".$fund->name."</td>";
	echo "<td>".$fund->currentValue."</td>";
	echo "<td>".$fund->change."</td>";
	echo "<td>".$fund->lastUpdate."</td>";
	echo "</tr>";
}
echo "</table>";

class Fund
{
	public $name;
	public $currentValue;
	public $change;
	public $lastUpdate;
}
?>

</html>