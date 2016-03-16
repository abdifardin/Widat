<?php



$topic = $_GET['t'];
		$topic = str_replace(" ", "_", $topic);
		$url = "https://en.wikipedia.org/w/api.php?format=json&action=parse&page={$topic}&prop=text&section=0";
		$ch = curl_init($url);
		
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_USERAGENT, "WIDAT | Wiki Abstract Translation Application");
		$c = curl_exec($ch);

		$json = json_decode($c);

echo $url;
$pattern = '#<p>(.*)</p>#Us';
		$abstract = "";
		if(preg_match_all($pattern, $content, $matches))
		{
			// print $matches[0]; // content of the first paragraph (including wrapping <p> tag)
			//$abstract = strip_tags($matches[1]); // Content of the first paragraph without the HTML tags.
			foreach($matches[1] as $p) {
				$abstract .= strip_tags($p) . "\n";
				echo strip_tags($p);
			}
		}
print_r($abstract);


?>