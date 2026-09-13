<?php

function saveTextFile($filePath, $text)
{
	$retval = true;
	$fh = fopen($filePath, "w");
	fwrite($fh, $text);
	fclose($fh);
	return $retval;
}

function loadTextFile($filePath)
{
	$text = file_get_contents($filePath);
	return $text;
}
