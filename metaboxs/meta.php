<?php
new Metaboxes_mapLeaflet;

class Metaboxes_mapLeaflet{

	public $post_type = 'leaflet_map';
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
		add_action( 'save_post_' . $this->post_type, array( $this, 'save_metabox' ) );
		add_action( 'admin_print_footer_scripts', array( $this, 'show_assets' ), 10, 999 );
	}

	## Добавляет мeтабоксы
	public function add_metabox() {
		add_meta_box( 'leaflet_map', 'Map', array( $this, 'render_metabox' ), $this->post_type, 'advanced', 'high' );
	}

	## Отображает метабокс на странице редактирования поста
	public function render_metabox( $post ) {
		//$options = get_post_meta($post->ID, 'options', 1);
		$xcenter = get_post_meta( $post->ID, 'xcenter', true );
		$ycenter = get_post_meta( $post->ID, 'ycenter', true );
		$zoom = get_post_meta( $post->ID, 'zoom', true );
		?>
			<div id="metamap" style="height:500px;"></div>
			
			<div style="display:none; margin:20px;">Center
				<input id="X">
				<input id="Y">
				<input name="xcenter" id="Xcenter">
				<input name="ycenter" id="Ycenter">
			</div>
			<div style="display:none; margin:20px;">Zoom
				<input name="zoom" id="zoom">
			</div>	
					<script>
			    var geoMarkers;
			    var textsMarkers=[];
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
						
					});		
				<?php if(empty($xcenter)){
						
						echo 'var mapCenter = L.latLng(55.148273, 61.413059);
						var zoom = 12;';
						
					}else{
				echo '		
				var mapCenter = L.latLng('.$xcenter.','.$ycenter.');
				var zoom =  '.$zoom.'; ';
				}
				?>
				var dictOptinosMap = L.map('metamap').setView(mapCenter, zoom);
			    var mplinkupload='https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';				
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
					//if (firstclick==false){
						//jQuery('.point').remove();
						//firstclick=true;
						//}
					jQuery('.point-list').append('<li class="point"><input class="pointx" name="pointsx[]" value="'+e.latlng.lat+'"><input class="pointy" name="pointsy[]" value="'+e.latlng.lng+'"><textarea class="point-text" name="pointstext[]"></textarea><a class="remove-point"><span class="dashicons dashicons-trash"></span></a></li>');
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
				var GetLatCenterMap = dictOptinosMap.getCenter();
				jQuery('#Xcenter').val(GetLatCenterMap.lat);
				jQuery('#Ycenter').val(GetLatCenterMap.lng);
				jQuery('#zoom').val(dictOptinosMap.getZoom());
		</script>
				<ul class="point-list">	
					<?php
					$input = '
					<li class="point">
						<input class="pointx" name="pointsx[]" value="%s">
						<input class="pointy" name="pointsy[]" value="%s">
						<textarea class="point-text" name="pointstext[]">%s</textarea>
						<a class="remove-point"><span class="dashicons dashicons-trash"></span></a>
					</li>
					';
					$points = get_post_meta( $post->ID, 'points', true );
					if ( is_array( $points ) ) {
						$pointsx = $points[0];
						$pointsy = $points[1];
						$pointstext = $points[2];
						for ($i = 0; $i < count($pointsx); $i++) {
							printf( $input,esc_attr($pointsx[$i]),esc_attr($pointsy[$i]), esc_attr( $pointstext[$i]));
							?>
							<script>
							L.marker([<?php echo $pointsx[$i]; ?>,<?php echo $pointsy[$i];?>]).addTo(dictOptinosMap);
							</script><?php
						}
					} else {
						//printf( $input,'' , '' , '' );
					}
					?>
				</ul>
				<input disabled value="[dictMap id='<?php echo $post->ID; ?>']">
		<?php
	}

	## Очищает и сохраняет значения полей
	public function save_metabox( $post_id ) {

		// Check if it's not an autosave.
		//if ( wp_is_post_autosave( $post_id ) )
			//return;

		if ( isset( $_POST['pointsx'] ) && is_array( $_POST['pointsx'] ) ) {
			$pointsx = $_POST['pointsx'];
			$pointsy  = $_POST['pointsy'];
			$pointstext = $_POST['pointstext'];
			$points = [$pointsx,$pointsy,$pointstext];
			$xcenter = $_POST['xcenter'];
			$ycenter = $_POST['ycenter'];
			$zoom = $_POST['zoom'];
			if ( $points ){ 
				update_post_meta( $post_id, 'points',$points );
				update_post_meta( $post_id, 'xcenter',$xcenter );
				update_post_meta( $post_id, 'ycenter',$ycenter );
				update_post_meta( $post_id, 'zoom',$zoom );
			}
			else {
				delete_post_meta( $post_id, 'points' );
			}

		}
	}
		## Подключает скрипты и стили
	public function show_assets() {
		if ( is_admin() && get_current_screen()->id == $this->post_type ) {
			$this->show_scripts();
		}
	}
	public function show_scripts() {
		?>
		<script src="<?php echo plugin_dir_url( __FILE__ ) .'js/edit.js'; ?>"></script>
		<style>.point-text{
				width:90%;
			}</style>
	<?php
	}
}
