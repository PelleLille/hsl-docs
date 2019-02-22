<?php

if (!isset($_SERVER['argc']))
	die("This file can only be run from the command line \r\n");

if (!isset($argv[1]))
	die("Missing argument(s) \r\n");

// Variables
if (isset($argv[1]) and $argv[1] === 'variables') {
	$inputPath = dirname(__FILE__).'/../dist/xml/';
	$outputPath = dirname(__FILE__).'/../dist/json/';
	$outputFile = $outputPath.'variables.json';
	$files = ['connect', 'helo', 'auth', 'mailfrom', 'rcptto', 'eodonce', 'eodrcpt', 'predelivery', 'postdelivery', 'api', 'firewall'];
	$result = ['core' => []];
	foreach ($files as $file) {
		$result[$file] = [];
		if (file_exists($inputPath.$file.'.xml')) {
			$xml = simplexml_load_file($inputPath.$file.'.xml');
			$variables = $xml->xpath('//*[@ids="pre-defined-variables"]')[0];
			$i = 0;
			$tables = $variables->xpath('.//table');
			foreach ($tables as $table) {
				$rows = $table->tgroup->tbody->row;
				foreach ($rows as $row) {
					$name = (string) $row->entry[0]->paragraph;
					$type = (string) $row->entry[1]->paragraph;
					if ($name[0] === '$') {
						$result[$file][$i]['name'] = $name;
						$result[$file][$i]['type'] = $type;
						if ($name === '$context') {
							$result[$file][$i]['detail'] = $type.' '.$name;
							$result[$file][$i]['documentation'] = (string) $row->entry[2]->paragraph;
						}
						else {
							$result[$file][$i]['detail'] = $type.' readonly '.$name;
							$result[$file][$i]['documentation'] = (string) $row->entry[3]->paragraph;
							$result[$file][$i]['example'] = (string) $row->entry[2]->paragraph;
						}
						$i += 1;	
					}
				}
			}
		} else {
			throw new Exception('File not found.');
		}
	}
	if (!is_dir($outputPath)) mkdir($outputPath);
	file_put_contents($outputFile, json_encode($result, JSON_PRETTY_PRINT)."\n");
}

// Functions & Classes
if (isset($argv[1]) and $argv[1] === 'functions' || $argv[1] === 'classes') {
	$inputPath = dirname(__FILE__).'/../dist/xml/';
	$outputPath = dirname(__FILE__).'/../dist/json/';
	if ($argv[1] === 'functions') {
		$outputFile = $outputPath.'functions.json';
	} else if ($argv[1] === 'classes') {
		$outputFile = $outputPath.'classes.json';
	}
	$files = ['functions', 'connect', 'helo', 'auth', 'mailfrom', 'rcptto', 'eodonce', 'eodrcpt', 'predelivery', 'postdelivery', 'api', 'firewall'];
	$result = [];
	foreach ($files as $file) {
		$result[$file === 'functions' ? 'core': $file] = [];
		if (file_exists($inputPath.$file.'.xml')) {
			$xml = simplexml_load_file($inputPath.$file.'.xml');
			if ($argv[1] === 'functions') {
				$functions = $xml->xpath('//*[@desctype="function"]');
			} else if ($argv[1] === 'classes') {
				$functions = $xml->xpath('//*[@desctype="class"]');
			}
			$i = 0;
			foreach ($functions as $function) {
				// Name
				$name = (string) $function->desc_signature['fullname'];
				if (!$name) {
					$name = (string) $function->desc_signature->desc_name;
				}

				// Skip echo since it's not a function
				if ($argv[1] === 'functions' && $name === 'echo')
					continue;

				// Fields
				$fields = $function->desc_content->field_list;

				// Parameters
				$parameters = ['required' => [], 'optional' => []];
				$paramRequired = (array) $function->desc_signature->desc_parameterlist->desc_parameter;
				$paramOptionalPath = $function->desc_signature->desc_parameterlist->xpath('.//desc_optional');
				$paramOptional = [];
				foreach ($paramOptionalPath as $path) {
					$paramOptional[] = (string) $path->desc_parameter;
				}
				if (isset($fields->field)) {
					foreach ($fields->field as $field) {
						if ((string) $field->field_name == 'Parameters') {
							if ($field->field_body->paragraph) {
								$paramName = (string) $field->field_body->paragraph->literal_strong;
								$paramType = (string) $field->field_body->paragraph->literal_emphasis;
								foreach ($paramRequired as $p)
									if ($paramName == $p)
										$parameters['required'][] = ['name' => $p, 'type' => $paramType];
								foreach ($paramOptional as $p)
									if ($paramName == strtok($p, ' '))
										$parameters['optional'][] = ['name' => $p, 'type' => $paramType];
							} else if ($field->field_body->bullet_list) {
								foreach ($field->field_body->bullet_list->list_item as $item) {
									$paramName = (string) $item->paragraph->literal_strong;
									$paramType = (string) $item->paragraph->literal_emphasis;
									foreach ($paramRequired as $p)
										if ($paramName == $p)
											$parameters['required'][] = ['name' => $p, 'type' => $paramType];
									foreach ($paramOptional as $p)
										if ($paramName == strtok($p, ' '))
											$parameters['optional'][] = ['name' => $p, 'type' => $paramType];
								}
							}
						}
					}
				}

				// Return type
				$returnType = null;
				if ($argv[1] === 'functions') {
					foreach ($fields->field as $field) {
						if ((string) $field->field_name == 'Return type') {
							$returnType = (string) $field->field_body->paragraph;
						}
					}
				}

				// Detail
				$detail = $name.'(';
				$r = count($parameters['required']);
				if ($r) {
					foreach ($parameters['required'] as $k => $v) {
						if (substr($v['name'], 0, 3) === '...')
							$detail .= $v['type'].' '.substr_replace($v['name'], '$', 3, 0);
						else
							$detail .= $v['type'].' $'.$v['name'];
						if ($k !== ($r - 1))
							$detail .= ', ';
					}
				}
				$o = count($parameters['optional']);
				if ($o) {
					if ($r)
						$detail .= ' ';
					foreach ($parameters['optional'] as $k => $v) {
						$detail .= '[';
						if ($r || ($o > 1 && $k > 0))
							$detail .= ', ';
						if (substr($v['name'], 0, 3) === '...')
							$detail .= $v['type'].' '.substr_replace($v['name'], '$', 3, 0);
						else
							$detail .= $v['type'].' $'.$v['name'];
						if ($k !== ($o - 1))
							$detail .= ' ';
					}
					for($y = 0; $y < $o ; $y++) { 
						$detail .= ']';
					}
				}
				$detail .= ')';
				if (isset($returnType)) {
					$detail .= ': '.$returnType;
				}

				// Value
				$value = $name.'(';
				if ($r || $o)
					$value .= '$0';
				$value .= ')';

				// Documentation
				$documentation = '';
				if ($function->desc_content->paragraph)
					$documentation = $function->desc_content->paragraph->asXML();
				if (!$documentation && $function->desc_content->block_quote->paragraph)
					$documentation = $function->desc_content->block_quote->paragraph->asXML();
				$documentation = strip_tags($documentation);

				// Store result
				if (strpos($name, '.')) {
					[$class, $name] = explode('.', $name, 2);
					$result[$file === 'functions' ? 'core': $file][$i]['class'] = $class;
					$value = explode('.', $value, 2)[1];
				}
				$result[$file === 'functions' ? 'core': $file][$i]['name'] = $name;
				if ($parameters) $result[$file === 'functions' ? 'core': $file][$i]['parameters'] = $parameters;
				if ($returnType) $result[$file === 'functions' ? 'core': $file][$i]['returnType'] = $returnType;
				$result[$file === 'functions' ? 'core': $file][$i]['detail'] = $detail;
				$result[$file === 'functions' ? 'core': $file][$i]['value'] = $value;
				if ($documentation) $result[$file === 'functions' ? 'core': $file][$i]['documentation'] = $documentation;
				$result[$file === 'functions' ? 'core': $file][$i]['link'] = '[Full documentation]({{ docsurl }}'.$file.'.html#'.(isset($class) ? $class.'.'.$name : $name).')';

				$i += 1;
			}
		} else {
			throw new Exception('File not found.');
		}
	}
	if (!is_dir($outputPath)) mkdir($outputPath);
	file_put_contents($outputFile, json_encode($result, JSON_PRETTY_PRINT)."\n");
}
