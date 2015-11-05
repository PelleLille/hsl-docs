<?php
$file = file("functions.rst");
$last = null;

function dump()
{
	global $name, $list;
	if (empty($list)) return;
	echo "* **$name**";
	foreach($list as $l) echo " :func:`$l`";
	echo "\n";
	$list = array();
}

foreach($file as $f)
{
	if ($f[0] == '-') {
		dump();
		$name = trim($last);
	}
	if (preg_match("/function[:][:] (.*?)[(]/", $f, $func))
		$list[] = $func[1];
	$last = $f;
}

dump();
