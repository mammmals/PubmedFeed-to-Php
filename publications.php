<?php
//based on: http://stackoverflow.com/questions/10163675/merge-xml-files-in-php
$savedXML = new DOMDocument; //original xml document
$savedXML->preserveWhiteSpace = false;
$savedXML->load('publications/merged.xml');

$pubmedSearch='http://www.ncbi.nlm.nih.gov/entrez/eutils/erss.cgi?rss_guid=1levKdK_NRD5DPdpOB_de21M8EuIKhyq52iQycdfLjG9bC2szn';//new pubmed searches xml document for "bruce je[auth]"
//echo(file_get_contents($pubmedSearch));
$newXML= new DOMDocument();
$newXML->loadXML(file_get_contents($pubmedSearch));

// get 'res' element of document 1
$res1 = $savedXML->getElementsByTagName('item')->item(0); //edited res - items

// iterate over 'item' elements of document 2
$items2 = $newXML->getElementsByTagName('item');
for ($i = 0; $i < $items2->length; $i ++) {
	$item2 = $items2->item($i);

	// import/copy item from document 2 to document 1
	$item1 = $savedXML->importNode($item2, true);

	// append imported item to document 1 'res' element
	$res1->appendChild($item1);

}
$savedXML->save('publications/merged.xml'); //edited -added saving into xml file
$newXML->save('publications/new.xml'); //edited -added saving into xml file

$merged = new DOMDocument; //new merged xml document
$merged->preserveWhiteSpace = false;
$merged->load('publications/merged.xml');

//from http://bavotasan.com/2010/display-rss-feed-with-php/
$feed = array();
foreach ($merged->getElementsByTagName('item') as $node) {
	$item = array ( 
		'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
		'description' => $node->getElementsByTagName('description')->item(0)->nodeValue,
		'author' => $node->getElementsByTagName('author')->item(0)->nodeValue,
		'journal' => $node->getElementsByTagName('category')->item(0)->nodeValue,
		'pubmedid' => $node->getElementsByTagName('guid')->item(0)->nodeValue,
		'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
		);
	array_push($feed, $item);
}

$limit = 93; //how many articles displayed on site...
for($x=0;$x<$limit;$x++) {
	$title = str_replace(' & ', ' &amp; ', $feed[$x]['title']);
	$link = $feed[$x]['link'];
	$description = $feed[$x]['description'];
	$desc = preg_split('/<p[^>]*>/i', $description);//split CDATA to find date of publication and journal
	$date = current(array_slice($desc,2,1)); //slice to find date of publication and journal (has </p> at the end...)
	$auth = $feed[$x]['author'];
	$jrnl = $feed[$x]['journal'];
	$pmid = $feed[$x]['pubmedid'];
	echo '<li><strong><a href="'.$link.'" title="'.$title.'">'.$title.'</a></strong><br>';
	echo $auth.'<br>';
	//echo $pmid.'<br>';
	//echo $description.'<br>'; //to display the full description in the site.
	echo $date.'</li>';
}
?>