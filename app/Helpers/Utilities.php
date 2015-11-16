<?php
/**
 * Created by PhpStorm.
 * User: mnvoh
 * Date: 11/16/15
 * Time: 6:03 PM
 */

namespace App\Helpers;


use App\Topic;

class Utilities
{
	public static function updateTopicFromWikipedia($topic)
	{
		if(!$topic)
			return;
		$topic = str_replace(" ", "_", $topic);
		$url = "https://en.wikipedia.org/w/api.php?format=json&action=parse&page={$topic}&prop=text&section=0";
		$ch = curl_init($url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_USERAGENT, "WIDAT | Wiki Abstract Translation Application");
		$c = curl_exec($ch);

		$json = json_decode($c);

		try {
			$content = $json->{'parse'}->{'text'}->{'*'};
		}
		catch(\Exception $ex) { return; }

		// pattern for first match of a paragraph
		$pattern = '#<p>(.*)</p>#Us'; // http://www.phpbuilder.com/board/showthread.php?t=10352690
		$abstract = "";
		if(preg_match($pattern, $content, $matches))
		{
			// print $matches[0]; // content of the first paragraph (including wrapping <p> tag)
			$abstract = strip_tags($matches[1]); // Content of the first paragraph without the HTML tags.
		}

		Topic::where('topic', $topic)->update(['abstract' => $abstract]);
	}
}