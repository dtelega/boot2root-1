<?php

/*
This script will open each file from /home/lmezard/ft_fun/
and concatenate the content to build a C main.c file.
*/

$files = scandir('.');
$content = [];

foreach ($files as $file)
{
	if (!in_array($file, ['.', '..', 'sort.php', 'main.c']))
	{
		$fd = fopen($file, 'r');
		$lines = [];
		$str = "";

		while (($lines[] = fgets($fd)));
		$last = $lines[count($lines) - 2];
		$index = substr($last, 6, strlen($last));
		for ($i = 0 ; $i < count($lines) - 2 ; $i++)
			$str .= $lines[$i];
		$content[$index] = $str;
		
		fclose($fd);
	}
}

ksort($content);
$str = "";
foreach ($content as $c)
	$str .= $c;
$fd = fopen("main.c", "w+");
fwrite($fd, $str);
fclose($fd);

?>
