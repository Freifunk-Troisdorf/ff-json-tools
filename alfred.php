<?php
/*
 * alfred.php
 * by Kemal Soyguder <kemal@freifunk-troisdorf.de>

 * This work is licensed under the Creative Commons
 * Attribution-NonCommercial-ShareAlike 4.0 International License.
 * To view a copy of this license, visit
 * http://creativecommons.org/licenses/by-nc-sa/4.0/
*/

  require_once("alfred_filter.php");
  $alfred_src = "./alfred.json";
  $data_json = file_get_contents($alfred_src);

  $data_array = json_decode($data_json, true);
  $data_keys = array_keys($data_array);

  $node_keys = array();

  foreach ($data_keys as $key) {
    if (preg_match("/^([0-9a-fA-F][0-9a-fA-F]:){5}([0-9a-fA-F][0-9a-fA-F])$/", $key) == 1) {
      array_push($node_keys, $key);
    }
  }
?>
<!DOCTYPE html>
<html lang="de">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
		<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	</head>
	<body>
		<header>
		</header>
		<main>
			<div>	
			<table class="table table-striped">
  				<tbody>
  					<tr>
  						<th>Name</th>
  						<th>Router-Modell</th>
  						<th>Firmware</th>
              			<th>Autoupdate</th>
  						<th>Online seit</th>
  						<th>Traffic</th>
  						<th>Clients</th>
  					</tr>
            <?php
              $i = 1;
              foreach ($node_keys as $node) {
                foreach ($data_array[$node] as $router) {
                  $router = json_decode(json_encode($router));
                  echo "<tr>";
                  echo "<td>".$router->hostname."</td>";
                  echo "<td>".$router->hardware->model."</td>";
                  echo "<td>".$router->software->firmware->base." (".$router->software->firmware->release.")</td>";
                  if ($router->software->autoupdater->enabled == 1) {
                    echo "<td>enabled ";
                  }
                  else {
                    echo "<td>disabled ";
                  }
                  switch($router->software->autoupdater->branch) {
                    case 'stable';
                      echo "<span class=\"label label-default\">stable</span>";
                    break;
                    case 'beta';
                      echo "<span class=\"label label-info\">beta</span>";
                    break;
                  }
                  echo "<td>".round($router->uptime/3600,2)." h</td>";
                  echo "<td><span class=\"label label-success\"><span class=\"glyphicon glyphicon-arrow-down\"></span> ".round(($router->traffic->tx->bytes/1024/1024),2)." MiB</span> <span class=\"label label-danger\"><span class=\"glyphicon glyphicon-arrow-up\"></span> ".round(($router->traffic->rx->bytes/1024/1024),2)." MiB</span></td>";
                  echo "<td>0</td>";
                  echo "</tr>";
                }
              }
            ?>
  				</tbody>
			</table>
			</div>
		</main>
		<footer>
		</footer>
	</body>
</html>
