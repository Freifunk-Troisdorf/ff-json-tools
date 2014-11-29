<?php
/*
 * alfred_filter.php
 * by Kemal Soyguder <kemal@freifunk-troisdorf.de>

 * This work is licensed under the Creative Commons //
 * Attribution-NonCommercial-ShareAlike 4.0 International License. //
 * To view a copy of this license, visit //
 * http://creativecommons.org/licenses/by-nc-sa/4.0/. 
*/

/*
 * configuration
 * you can either decide to save the filtered data into the new file $alfred_local or save & print it
*/

$print_filtered = true;

/*
 * configuration end
*/

$alfred_src = "http://ffmap.freifunk-rheinland.net/alfred_merged.json";
$alfred_local = "./alfred.json";
$filter = array("Troisdorf-", "Troisdorf");

$alfred_json = file_get_contents($alfred_src);
$alfred_array = json_decode($alfred_json, true);
$wanted_nodes = array();


foreach ($alfred_array as $node_array) {
	$node = new stdClass();
	$node = json_decode(json_encode($node_array), false);
	foreach ($filter as $current_filter) {
		if (strpos($node->hostname, $current_filter) !== false) {
			$wanted_nodes[$node->network->mac] = array();
			array_push($wanted_nodes[$node->network->mac], $node);
		}
	}
}

if ($print_filtered) {
	header('Content-type: application/json');
	print_r(json_encode($wanted_nodes, JSON_PRETTY_PRINT));
}
file_put_contents($alfred_local, json_encode($wanted_nodes));

?>