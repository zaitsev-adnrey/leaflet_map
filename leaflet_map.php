<?php
/**
 * Plugin Name:     Leaflet Map
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     Plugin Leaflet Maps
 * Author:          az
 * Author URI:      YOUR SITE HERE
 * Text Domain:     leaflet_map
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Leaflet_map
 */
function leaflet_map_upload_script_admin() {
	wp_enqueue_style( 'leaflet-css', plugin_dir_url( __FILE__ ) .'leaflet/leaflet.css');
	wp_enqueue_script( 'leaflet-js', plugin_dir_url( __FILE__ ) .'leaflet/leaflet.js');
	//wp_enqueue_script( 'editpost-js', plugin_dir_url( __FILE__ ) .'js/edit.js');
}
add_action( 'admin_enqueue_scripts', 'leaflet_map_upload_script_admin' );

function leaflet_map_upload_script() {
	wp_enqueue_style( 'leaflet-css', plugin_dir_url( __FILE__ ) .'leaflet/leaflet.css');
	wp_enqueue_script( 'leaflet-js', plugin_dir_url( __FILE__ ) .'leaflet/leaflet.js');
}
//add_action( 'wp_enqueue_scripts', 'leaflet_map_upload_script' );

add_action('admin_menu', 'add_plugin_page');
function add_plugin_page(){
	add_options_page( 'Настройки Leaflet Map', 'Leaflet Map', 'manage_options', 'leaflet_map', 'leaflet_map_options_page_output' );
}
include 'post-types/leaflet_map.php';
include 'metaboxs/meta.php';
function leaflet_map_options_page_output(){
	?>
<div style="height:100px;"></div>
<div id="mapdict" style="height:500px;"></div>
<div style="margin:20px;">Mark

	<input type="button" value="Clear marks" id="ClearMark">
	<input type="button" value="return marks" id="returnMark" style="display:none;">	
	<input type="button" value="Create Shortcode" id="CreateShortcode">
</div>	
<div style="display:none; margin:20px;">Center
	<input id="X">
	<input id="Y">
	<input id="Xcenter">
	<input id="Ycenter">
</div>
<div style="display:none; margin:20px;">Zoom
<input id="zoom">
</div>	
<div style="margin:20px;">
<input disabled id="shortcode" style="width: 100%;">
<textarea style="width: 100%; margin-top:35px;"  id="markerText"></textarea>
</div>
	  
		  <script>
			    var geoMarkers;
			    var textsMarkers=[];
					jQuery('#ClearMark').click(function(){
							geoMarkers = markers.toMultiPoint();
							markers.clearLayers();
						});
					jQuery('#returnMark').click(function(){
						var arr = geoMarkers.geometry.coordinates;
						arr.forEach(function(item, i, arr) {
						   markers.addLayer(L.marker([item[1],item[0]]));
						});
					});	
					jQuery('#CreateShortcode').click(function(){
						var pointX=[];
						var pointY=[];
						var Points = markers.toMultiPoint();
						var arr = Points.geometry.coordinates;
						arr.forEach(function(item, i, arr) {
						   pointX.push(item[0]);
						   pointY.push(item[1]); 
						});
						var center = dictOptinosMap.getCenter();
						var zoom = dictOptinosMap.getZoom();
						console.log(pointX);
						console.log(pointY);
						jQuery('#shortcode').val('[dictMap centerX="'+center.lng+'" centerY="'+center.lat+'" zoom="'+zoom+'" pointsX="'+pointX.toString()+'" pointsY="'+pointY.toString()+'" pointsText="'+textsMarkers.toString()+'"]')
					});		

				var mapCenter = L.latLng(55.148273, 61.413059);
				var zoom = 12;
				var dictOptinosMap = L.map('mapdict').setView(mapCenter, zoom);
			    var mplinkupload='https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
		        //var mplinkupload='https://tile.susu.ru/susu-campus-en/{z}/{x}/{y}{r}.png';
		        //var mplinkupload= 'https://{s}.tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png';	
				L.tileLayer(mplinkupload, {
					attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
				}).addTo(dictOptinosMap);
				var markers = L.layerGroup().addTo(dictOptinosMap);
				dictOptinosMap.on('click', function(e) {
					// Обновляем маркер
					var markerstring = jQuery('#markerText').val();
					markers.addLayer(L.marker([e.latlng.lat, e.latlng.lng]).bindPopup(markerstring));
					// Отправляем данные AJAX
					// ...
					//alert(e.latlng.lat + ' ' + e.latlng.lng);
					jQuery('#X').val(e.latlng.lat);
					jQuery('#Y').val(e.latlng.lng);
					textsMarkers.push(jQuery('#markerText').val());
					jQuery('#markerText').val('');
				});
				dictOptinosMap.on('zoom', function(e) {
					// Обновляем маркер
					// Отправляем данные AJAX
					// ...
					//alert(e.latlng.lat + ' ' + e.latlng.lng);
					jQuery('#zoom').val(dictOptinosMap.getZoom());
					
				});
				dictOptinosMap.on('move', function(e) {
					// Обновляем маркер
					// Отправляем данные AJAX
					// ...
					//alert(e.latlng.lat + ' ' + e.latlng.lng);
					var GetLatCenterMap = dictOptinosMap.getCenter();
					jQuery('#Xcenter').val(GetLatCenterMap.lat);
					jQuery('#Ycenter').val(GetLatCenterMap.lng);
					
				});
			//$()
		  </script>
	<?php
}

add_shortcode( 'dictMap', 'leaflet_map_shortcode' );

function leaflet_map_shortcode( $atts ){
	 ob_start();
	  $postID = $atts['id'];
	  $points = get_post_meta( $postID, 'points', true );
	  if(isset($points[0])){$pointsx = $points[0];}
	  if(isset($points[1])){$pointsy = $points[1];}
  	  if(isset($points[2])){$pointstext = $points[2];}
	  $xcenter = get_post_meta( $postID, 'xcenter', true );
	  $ycenter = get_post_meta( $postID, 'ycenter', true );
	  $zoom = get_post_meta( $postID, 'zoom', true );

	  ?>
	  <div id="mapdict<?php echo $postID; ?>" style="width: 100%; height:500px;"></div>
	  <script>
		var mapCenter = L.latLng(<?php echo $xcenter;?>, <?php echo $ycenter;?>);
		var zoom = <?php echo $zoom;?>;
		var elemsHtml = document.querySelector("html");
		var valueLang= elemsHtml.getAttribute('lang').split('-',2);
		//var mplinkupload='https://tile.susu.ru/susu-campus-'+valueLang[0]+'/{z}/{x}/{y}{r}.png';
		var mplinkupload= 'https://tiles.stadiamaps.com/tiles/osm_bright/{z}/{x}/{y}{r}.png';
		var dictMap<?php echo $postID; ?> = L.map('mapdict<?php echo $postID; ?>', {
			center: [parseFloat(<?php echo $xcenter;?>), parseFloat(<?php echo $ycenter;?>)],
			zoom: <?php echo $zoom;?>,
			
		});
		
		L.tileLayer(mplinkupload, {
					attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
				}).addTo(dictMap<?php echo $postID; ?>);
				//var layer<?php echo $postID; ?> = L.tileLayer(
			 //mplinkupload , {
				//attribution: 'Map data © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors',
				//tms: true,
				//minZoom:  8,
				//maxZoom: 18
			//}
		//);
		
		<?php 
		$bug = '"';
		if (isset($pointstext[0])){
		for ($i = 0; $i < count($pointsx); $i++) {
			$pointstextClean = preg_replace("/\r\n/", "", $pointstext[$i]);
			//echo 'var pointstext = "'.$pointstextClean.'"';Popup bindTooltipstring $string
			echo 'L.marker(['.$pointsx[$i].','.$pointsy[$i].']).addTo(dictMap'.$postID.').bindPopup('.$bug .addslashes($pointstextClean).$bug.');'.PHP_EOL;
			
		}
	}
		?>
		L.control.fullscreen().addTo(dictMap<?php echo $postID; ?>);
		dictMap<?php echo $postID; ?>.on('resize', function(ev) {
			console.log('yeaaaaaa');
		});
			const resizeObserver = new ResizeObserver(() => {
			  dictMap<?php echo $postID; ?>.invalidateSize();
			});

			resizeObserver.observe(document.getElementById('mapdict<?php echo $postID; ?>'));
		</script>
	  <?php
			
			
			 ?>
	  <?php
	 $output = ob_get_contents(); // всё, что вывели, окажется внутри $output
	 ob_end_clean();
	 return $output;
	}
