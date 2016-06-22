<!DOCTYPE HTML>
<!--
	Ion by TEMPLATED
	templated.co @templatedco
	Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
-->
<html>
	<head>
		<title>Bruce Lab - Publications</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<!--[if lte IE 8]><script src="js/html5shiv.js"></script><![endif]-->
		<script src="js/jquery.min.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/skel-layers.min.js"></script>
		<script src="js/init.js"></script>
		<noscript>
			<link rel="stylesheet" href="css/skel.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-xlarge.css" />
		</noscript>
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-31449146-1', 'auto');
		  ga('send', 'pageview');

		</script>
	</head>
	<body id="top">

<!-- Header -->
			<header id="header" class="skel-layers-fixed">	
				<nav id="nav">	
					<ul>
						<a href="index.html" class="image left"><img src="images/logo_header.png" alt="" /></a>
						<li><a href="members.html">Members</a></li>
						<li><a href="research.html">Research</a></li>						
						<li><a href="publications.php">Publications</a></li>
						<li><a href="jobs.html">Jobs</a></li>
						<li><a href="software.html">Software</a></li>
						<li><a href="internal/">Internal</a></li>
						<li><a href="contact.html">Contact</a></li>
						<li><a href="http://xlinkdb.gs.washington.edu/xlinkdb" class="button special">XLINKDB</a></li>
					</ul>
				</nav>
			</header>

		<!-- Main -->
			<section id="main" class="wrapper style1">
				<header class="major">
					<h2>Publications</h2>
					<img src="images/alejandroescamilla-book_small.jpg" alt="" width=50% />
				</header>
				<div class="container">
					<section>
						<ul class="alt">
							<p>A complete list of Bruce Lab Publications can be found on <a href="http://www.ncbi.nlm.nih.gov/pubmed/?term=Bruce+JE%5BAuthor%5D">PubMed.</a></p>
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
						</ul>
					</section>
				</div>
			</section>
<!-- Footer -->
			<footer id="footer">
				<div class="container">
					<div class="row double">
						<div class="6u">
							<div class="row collapse-at-2">
								<div class="6u">
									<h3>Bruce Lab</h3>
									<ul class="alt">
										<li><a href="index.html">Home</a></li>
										<li><a href="publications.html">Publications</a></li>
										<li><a href="jobs.html">Jobs</a></li>
										<li><a href="software.html">Software</a></li>
										<li><a href="contact.html">Contact</a></li>
									</ul>
								</div>
								<div class="6u">
									<h3>University of Washington</h3>
									<ul class="alt">
										<li><a href="http://www.washington.edu/">UW Home</a></li>
										<li><a href="http://www.gs.washington.edu/">Department of Genome Sciences</a></li>
										<li><a href="https://proteomicsresource.washington.edu/">University of Washington Proteomics Resource</a></li>
										<li><a href="http://depts.washington.edu/somslu/">UW Medicine South Lake Union</a></li>
									</ul>
								</div>
							</div>
						</div>
						<div class="6u">
							<h2>Additional information.</h2>
							<p>Thank you for visiting the Bruce Lab website.</p>
							<ul class="icons">
								<li><a href="http://brucelab.gs.washington.edu/xlinkdb/" class="button special">XLINKDB</a></li>
								<li><a href="https://twitter.com/Bruce_Lab_UW" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
								<li><a href="https://www.facebook.com/BruceLaboratory/" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
								<!--<li><a href="#" class="icon fa-instagram"><span class="label">Instagram</span></a></li>
								<li><a href="#" class="icon fa-linkedin"><span class="label">LinkedIn</span></a></li>
								<li><a href="#" class="icon fa-pinterest"><span class="label">Pinterest</span></a></li>-->
							</ul>
						</div>
					</div>
					<ul class="copyright">
						<li>&copy; Bruce Lab. All rights reserved.</li>
						<li>Design: <a href="http://templated.co">TEMPLATED</a></li>
						<li>Images: <a href="http://unsplash.com">Unsplash</a></li>
					</ul>
				</div>
			</footer>

	</body>
</html>