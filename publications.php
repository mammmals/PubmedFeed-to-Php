<?php
//based on: http://stackoverflow.com/questions/10163675/merge-xml-files-in-php
$doc1 = new DOMDocument; //original xml document
$doc1->preserveWhiteSpace = false;
$doc1->load('publications/merged.xml');

$doc2='http://www.ncbi.nlm.nih.gov/entrez/eutils/erss.cgi?rss_guid=1levKdK_NRD5DPdpOB_de21M8EuIKhyq52iQycdfLjG9bC2szn';//new pubmed searches xml document for "bruce je[auth]"
$doc2= new DOMDocument(file_get_contents($doc2));

// get 'res' element of document 1
$res1 = $doc1->getElementsByTagName('item')->item(0); //edited res - items

// iterate over 'item' elements of document 2
$items2 = $doc2->getElementsByTagName('item');
for ($i = 0; $i < $items2->length; $i ++) {
	$item2 = $items2->item($i);

	// import/copy item from document 2 to document 1
	$item1 = $doc1->importNode($item2, true);

	// append imported item to document 1 'res' element
	$res1->appendChild($item1);

}
$doc1->save('publications/merged.xml'); //edited -added saving into xml file

$merged = new DOMDocument; //new merged xml document
$merged->preserveWhiteSpace = false;
$merged->load('publications/merged.xml');

//$merged->formatOutput = true;//display nerged xml
//echo $merged->saveXml();

//from http://bavotasan.com/2010/display-rss-feed-with-php/
$feed = array();
foreach ($merged->getElementsByTagName('item') as $node) {
	$item = array ( 
		'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
		'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
		'author' => $node->getElementsByTagName('author')->item(0)->nodeValue,
		'journal' => $node->getElementsByTagName('category')->item(0)->nodeValue,
		'pubmedid' => $node->getElementsByTagName('guid')->item(0)->nodeValue,
		'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
		'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
		);
	array_push($feed, $item);
}
$limit = 90; //how many articles displayed on site...
for($x=0;$x<$limit;$x++) {
	$title = str_replace(' & ', ' &amp; ', $feed[$x]['title']);
	$link = $feed[$x]['link'];
	$description = $feed[$x]['desc'];
	$auth = $feed[$x]['author'];
	$jrnl = $feed[$x]['journal'];
	$pmid = $feed[$x]['pubmedid'];
	$date = date('l F d, Y', strtotime($feed[$x]['date']));
	echo '<li><strong><a href="'.$link.'" title="'.$title.'">'.$title.'</a></strong><br>';
	//echo '<small><em>Posted on '.$date.'</em></small></p>';
	echo $auth.'<br>';
	echo '<i>'.$jrnl.'</i>. '.$pmid.'</li>';
}
?>