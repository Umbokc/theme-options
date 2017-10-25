<?php 

/**
* Author: umbokc
* Author: github.com/Umbokc
* ThemeOptions
*/
class ThemeOptions {

	private $version = '1.0.1';

	private $my_options = array();
	private $options_db = array();

	private $u_theme_option_name = '';
	private $u_theme_option_group = '';

	private static $section;
	private static $section_2;
	private static $section_3;

	private static $tab;

	private static $field;

	private static $attr_name = array();

	function __construct(array $data, string $option_name, string $option_group) {
		$this->my_options = $data;
		$this->u_theme_option_name = $option_name;
		$this->u_theme_option_group = $option_group;
	}

	public function run(){
		$this->get_body();
		$this->get_style();
		$this->get_script();
	}

	private function get_body(){
		?>
		<div class="wrap">
			<?php echo "<h2>" .  __( 'Настройка темы: ', 'sampletheme' ) . get_current_theme() . "</h2>"; ?>
			<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
				<div class="updated fade"><p><strong><?php _e( 'Сохранение прошло успешно', 'sampletheme' ); ?></strong></p></div>
			<?php endif; ?>

			<form method="post" class="u-form-theme-option" action="options.php">
				<?php
					settings_fields( $this->u_theme_option_group );
					$this->options_db = get_option( $this->u_theme_option_name );
					if(!$this->options_db) $this->options_db = array();
				?>
				<p class="submit">
					<input type="submit" class="u-btn-primary" value="<?php _e( 'Сохранить настройки', 'sampletheme' ); ?>" />
				</p>

				<?php $this->get_sidebar() ?>
				<?php $this->get_content() ?>

				<p class="submit">
					<input type="submit" class="u-btn-primary" value="<?php _e( 'Сохранить настройки', 'sampletheme' ); ?>" />
					<input type="button" class="u-btn-danger delete-all-options" value="<?php _e( 'Восстановить настройки', 'sampletheme' ); ?>" />
				</p>
			</form>
		</div>
		<div  id="modal-success" class="modal">
			<div class="modal__box">
				<p>Сохранение прошло успешно</p>
			</div>
		</div>
		<?php
	}

	private function get_sidebar(){
		?>
		<div class="u_sidebar">
			<div class="logo">
				<a href="https://github.com/Umbokc" target="_blank">
					The uWeb
					<p>Theme</p>
				</a>
			</div>
			<ul>
				<?php $i = 0; foreach($this->my_options as $key => $value):
						if($this->get_isset($value['type']) == 'sub-section'): ?>

						<!-- Секция второго уровня -->
						<li class="show_blocks" to="__<?= $key ?>"><?= $value['label'] ?></li>

						<ul class="u_blocks __<?= $key ?> <?= $this->get_isset($value['class']) ?>">
							<?php foreach($value['sections'] as $s_key => $s_value):
									if($this->get_isset($s_value['type']) == 'sub-section'): ?>

									<!-- Секция третьего уровня -->
									<li class="show_blocks" to="<?= $this->class_sitebar_show_blocks_label($key, $s_key) ?>"><?= $s_value['label'] ?></li>

									<ul class="u_blocks <?= $this->class_sitebar_show_blocks_label($key, $s_key) ?> <?= $this->get_isset($s_value['class']) ?>">
										<?php foreach($s_value['sections'] as $ss_key => $ss_value):
										?>

										<!-- Элементы секции 3 уровня -->
										<li class="u_sidebar_item u-truncate <?= ($i == 0) ? 'active' : '' ?>" to="<?= $this->generate_u_class_content($key, $s_key, $ss_key) ?>"><?= $ss_value['label'] ?></li>

										<?php $i++; endforeach ?>
									</ul>

								<?php else: ?>

									<!-- Элементы секции 2 уровня -->
									<li class="u_sidebar_item u-truncate <?= ($i == 0) ? 'active' : '' ?>" to="<?= $this->generate_u_class_content($key, $s_key) ?>"><?= $s_value['label'] ?></li>

								<?php endif ?>
							<?php $i++; endforeach ?>
						</ul>
					<?php else: ?>
						<li class="u_sidebar_item u-truncate <?= ($i == 0) ? 'active' : '' ?>" to="<?= $this->generate_u_class_content($key) ?>"><?= $value['label'] ?></li>
					<?php endif ?>
				<?php $i++; endforeach ?>
			</ul>
		</div>
		<?php
	}

	private function get_content(){
		echo '<div class="u_content">';
		foreach($this->my_options as $key_opt => $val_opt){
			self::$section = $key_opt;
			self::add_attr_name(self::$section);

			// секция второго уровня
			if($this->get_isset($val_opt['type']) == 'sub-section'){

				foreach($val_opt['sections'] as $s_key_opt => $s_val_opt){
					self::$section_2 = $s_key_opt;
					self::add_attr_name(self::$section_2);
					// секция третьего уровня
					if($this->get_isset($s_val_opt['type']) == 'sub-section'){

						foreach($s_val_opt['sections'] as $ss_key_opt => $ss_val_opt){
							self::$section_3 = $ss_key_opt;
							self::add_attr_name(self::$section_3);
							$ss_val_opt['label'] = $s_val_opt['label'] . ': ' . $ss_val_opt['label'];
							$this->get_content_body($ss_val_opt);
							self::pop_attr_name();
							self::$section_3 = '';
						}

					} else {
						$this->get_content_body($s_val_opt);
					}
					self::pop_attr_name();
					self::$section_2 = '';
				}

			} else {
				$this->get_content_body($val_opt);
			}
			self::pop_attr_name();
			self::$section = '';
		}
		echo '</div>';
	}

	private function get_content_body($val_opt){
		?>
			<div class="u_content_item <?= $this->generate_u_class_content(self::$section, self::$section_2, self::$section_3) ?>">
				<div class="title">
					<h2><?php _e( $val_opt['label'], 'sampletheme' ); ?></h2>
				</div>
				<?php $this->get_fields($val_opt) ?>
			</div>
		<?php
	}

	private function get_fields($val_opt){

		foreach($val_opt['fields'] as $opt_key => $opt_val){

			if($this->get_isset($opt_val['type']) == 'tab'){
				self::$tab = $opt_key;
				self::add_attr_name(self::$tab);
				$this->get_fields_tab($opt_val);
				self::pop_attr_name();
				self::$tab = '';
			} else {
				self::$field = $opt_key;
				self::add_attr_name(self::$field);
				$this->get_item($opt_val);
				self::pop_attr_name();
				self::$field = '';
			}
		}
	}

	private function get_fields_tab($opt_val){
		?>
		<h4 class="u-tab" to="<?= self::$tab ?>"><?php _e( $opt_val['label'], 'sampletheme' );?></h4>
		<div class="u_b_c_p u_b_c_n <?= self::$tab ?> <?= ($this->get_isset($opt_val['class'])) ?>">
			<?php
			foreach($opt_val['fields'] as $opt_down_key => $opt_down){
				self::$field = $opt_down_key;
				self::add_attr_name(self::$field);
				$this->get_item($opt_down);
				self::pop_attr_name();
				self::$field = '';
			}
			?>
		</div>
		<?php 
	}

	private function get_item($opt){
		if($opt['type'] == 'tab'){
			self::$tab = self::$field;
			$this->get_fields_tab($opt);
			self::$tab = '';
			return;
		}

		$name_attr = $this->generate_name_attr();
		$val = $this->get_data_value(
			$this->generate_var_name(), $opt['default']
		);

		?>
		<div class="the_item">
			<?php if($opt['type'] != 'h3'): ?>
				<h4><?php _e( $opt['label'], 'sampletheme' );?></h4>
			<?php endif ?>

			<?php if($opt['type'] == 'input'): ?>
				<input autocomplete="off" class="regular-text" type="text" name="<?= $name_attr ?>" value="<?php esc_attr_e( $val ); ?>" />
				
			<?php elseif($opt['type'] == 'textarea'): ?>
				
				<textarea name="<?= $name_attr ?>" style="<?= (isset($opt['height'])) ? 'min-height:' . $opt['height'] : '' ?>"><?= $val ?></textarea>
			
			<?php elseif($opt['type'] == 'h3'): ?>

				<h3><?php _e( $opt['label'], 'sampletheme' );?></h3>

			<?php elseif($opt['type'] == 'wp_editor'): ?>
				<?php
					$settings = array(
						'wpautop' => true,
						'media_buttons' =>  (isset($opt['media_buttons'])) ? $opt['media_buttons'] : false,
						'textarea_name' => $name_attr,
						'textarea_rows' => get_option('default_post_edit_rows', 1),
						'teeny' => true,
						'editor_class' => (isset($opt['cols'])) ? 'wp-editor-cols-' . $opt['cols'] : '', 
						'dfw' => true,
						'tinymce' => array(
							'theme_advanced_buttons1' => 'bold,italic,underline' 
						),
						'quicktags' => (isset($opt['quicktags'])) ? $opt['quicktags'] : false
					);
					wp_editor( $val, $name_attr, $settings );
				?>
				
			<?php elseif($opt['type'] == 'img'): ?>
			
				<div class="uploader photo_media_upload">
					<img class="photo_media_image" src="<?= $val ?>" style="max-width:100px; display:block;" />
					<input autocomplete="off" class="photo_media_url" type="hidden" name="<?= $name_attr ?>" value="<?= $val ?>" style="margin-bottom:10px; clear:right;">
					<input type="hidden" class="photo_media_id" name="photo_media_id">
					<!-- <a href="#" class="button u-btn-upload">Загрузить</a> -->
				</div>
			
			<?php endif ?>

		</div>
		<?php
	}

	private function get_style(){
		?>
		<style>
			.logo,.modal__box{text-align:center}.u-tab,.u_sidebar li{cursor:pointer}.modal{z-index: 1;opacity:0;visibility:hidden;display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-align:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.7);-webkit-transition:.3s ease-in-out;transition:.3s ease-in-out}.modal__box{padding:1em;background:#fff;box-shadow:0 0 10px 0 rgba(0,0,0,.2);-webkit-transition:all .3s cubic-bezier(.2,.9,.3,1.5);transition:all .3s cubic-bezier(.2,.9,.3,1.5);border-top:5px solid #3dda58;border-bottom:5px solid #ddd}.u-truncate{max-width:100%;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.u_b_c_p{padding-left:10px;border-bottom:1px solid #999;padding-bottom:10px}.u_b_c_m{margin-left:10px}.logo,.logo a p,.title h2,.u_sidebar li,h5{margin:0}.u_b_c_n{display:none}.u-tab:after{content:"\25bc";-webkit-transform:rotate(90deg);transform:rotate(90deg);position:relative;display:inline-block;left:5px;top:1px}.u-tab.active:after{-webkit-transform:rotate(0);transform:rotate(0)}.u-form-theme-option>*,h1,h2,h3,h4,h5,h6{color:#777}.logo{font-weight:400;background:#19a0c5;padding:20px 0}.logo a{line-height:100%;font-size:2em;color:#fff;text-decoration:none}.u_sidebar{width:20%;float:left;background:#34393d}.submit{clear:both}.u_sidebar li{background-color:#34393d;padding:20px 10px 20px 10px;margin-left: 10px;display:block;color:#999;font-weight:700;text-shadow:1px 1px 2px rgba(0,0,0,.3)}.u_sidebar li.active,.u_sidebar li:hover{color:#c1c1c1;background-color:#40464b}.u_content{float:left;padding:10px 30px;background:#fff;width:70%}input,textarea{font-family:sans-serif;font-size:100%;line-height:1.15;margin:0}.u_content .u_content_item input[type=text],.u_content .u_content_item textarea{width:100%;border-radius:4px;border:1px solid #ddd;padding:10px;box-sizing:border-box;outline:0;box-shadow:none}textarea{width:100%;min-height:150px}.u_content_item{display:none}.u_content_item:first-child{display:block}.list_content{padding-left:20px}.title h2{padding:20px;font-weight:400;border-bottom:1px solid #DDD;font-size:3em;line-height:100%}.u-btn-primary,.uploader .u-btn-upload{display:block;color:#fff;text-shadow:none;padding:10px;font-weight:400;background:#44c4e7;text-align:center;border-radius:4px;border:none;outline:0;cursor:pointer}.u_content .u_content_item .uploader input{width:50%}.uploader .u-btn-upload{display:inline-block;height:auto;line-height:100%}.u_blocks{margin-left:10px}.u_content h4{margin-bottom:5px}
			h3{padding-top: 20px;}
			.u-btn-danger{margin-top: 10px;}
			.the_item h4{cursor: pointer;}
			li.sub-section:hover{color: #999;background-color: #34393D;}
			.wp-editor-area{min-height: auto;} .wp-editor-cols-2{ min-height: 60px; } .wp-editor-cols-3{ min-height: 80px; } .wp-editor-cols-3{ min-height: 100px; } .wp-editor-cols-4{ min-height: 120px; } .wp-editor-cols-5{ min-height: 140px; } .wp-editor-cols-6{ min-height: 160px; } .wp-editor-cols-7{ min-height: 180px; } .wp-editor-cols-8{ min-height: 200px; }
			.u\:dn{display: none;}
			.u_blocks {margin-left: 10px; position: relative;}
			.u_sidebar li{padding: 15px 10px 15px 10px;}
			ul.u_blocks::before {content: "";position: absolute;left: 10px;height: calc(100%);top: 0px;width: 1px; background: #b9b5b5;}
		</style>
		<?php
	}

	private function get_script(){
		?>
			<script>

				jQuery('.u_sidebar_item').click(function(){
					jQuery('.u_sidebar_item').removeClass("active");
					jQuery(this).addClass("active");

					jQuery('.u_content_item').hide();
					jQuery('.' + jQuery(this).attr('to')).show();
				});

				jQuery('.show_blocks').click(function(){
					jQuery('.' + jQuery(this).attr('to')).slideToggle();
				});

				jQuery('.u-global-title').keyup(function(){
					var val = jQuery(this).val();
					var u_change = jQuery(this).attr('u-change');
					if(u_change)
						jQuery('.' + u_change).text(val);
					
					jQuery(this).parents('.u_content_item').find('.title h2 span').text(val);
				});

				jQuery('.u-tab').click(function(){
					jQuery(this).toggleClass('active');
					// jQuery(this).parent().find('.' + jQuery(this).attr('to')).slideToggle();
					var elem = '.' + jQuery(this).attr('class').split(' ').join('.') + ' + .' + jQuery(this).attr('to');
					jQuery(this).parent().find(elem).slideToggle();
				});

				jQuery('.the_item h4').click(function(){
					jQuery(this).parent('.the_item').find('input, textarea, .uploader').slideToggle();
				});

				jQuery('.delete-all-options').click(function(){
					if(confirm("Вы уверенны?")){
						jQuery.ajax({
							type: 'POST',
							// url: ,
							data: {delete_options: true}
						}).done(function(e) {
							location.reload();
						});
					}
					e.preventDefault();
				});

				jQuery('#modal-success').click(function(){
					jQuery(this).css({
						'opacity':'0',
						'visibility':'hidden'
					});
				});

				jQuery('.u-form-theme-option').submit(function(e){
					e.preventDefault();
					jQuery.ajax({
						type: 'POST',
						url: jQuery(this).attr('action'),
						data: jQuery(this).serialize()
					}).done(function(e) {
						// console.log(e);
						jQuery('#modal-success').css({
							'opacity':'1',
							'visibility':'visible'
						});
					});
				});

				jQuery('.uploader').click(function(e) {
					e.preventDefault();
					var ths = jQuery(this);
					var photo_uploader = wp.media({
						title: 'Photo',
						button: {
							text: 'Upload'
						},
						multiple: false  // Set this to true to allow multiple files to be selected
					})
					.on('select', function() {
						var attachment = photo_uploader.state().get('selection').first().toJSON();
						console.log(attachment);
						jQuery(ths.find('.photo_media_image')).attr('src', attachment.url);
						jQuery(ths.find('.photo_media_url')).val(attachment.url);
						jQuery(ths.find('.photo_media_id')).val(attachment.id);
					})
					.open();
				});
			</script>
		<?php
	}
	
	private function get_isset($data){
		return isset($data) ? $data : '';
	}

	private function class_sitebar_show_blocks_label($key, $s_key){
		return "__$key---$s_key";
	}

	private function generate_u_class_content($key, $s_key = null,  $ss_key = null){
		if($ss_key != null && $ss_key != '')
			return $key . '_-_' . $s_key . '_-_' . $ss_key;

		if($s_key != null && $s_key != '')
			return $key . '_-_' . $s_key;

		return $key;
	}

	private static function add_attr_name($value='') {
			self::$attr_name[] = $value;
		// array_push(self::$attr_name, $value);
	}

	private static function pop_attr_name() {
		array_pop(self::$attr_name);
	}

	private function generate_name_attr(){
		$name = "$this->u_theme_option_name[";
		for ($i=0; $i < count(self::$attr_name); $i++) { 
			$value = self::$attr_name[$i];
			$name .= "$value]";
			if($i < count(self::$attr_name)-1){
				$name .= "[";
			}
		}
		return $name;
	}

	private function generate_var_name(){
		$name = '[';
		for ($i=0; $i < count(self::$attr_name); $i++) { 
			$value = self::$attr_name[$i];
			$name .= "\"$value\"]";
			if($i < count(self::$attr_name)-1){
				$name .= "[";
			}
		}
		return $name;
	}

	private function get_data_value($string, $default) {
		
		// $string = 'this->options_db["u_panda_theme_option"]["u_pto_sevices"]["site"]["first_section"]["under-title"]';

		$found_matches = preg_match_all('/\[\"([A-z_-]+)\"\]/', $string, $matches);
		// echo '<pre>'; print_r($string);
		// echo "\n\n\n";
		// echo '<pre>'; print_r($matches);die();
		if (!$found_matches) {
			return $default;
		}


		$current_data = $this->options_db;

		foreach ($matches[1] as $name) {
			if (key_exists($name, $current_data)) {
				$current_data = $current_data[$name];
			} else {
				// print_r($name);die();
				return $default;
			}
		}

		return $current_data;
	}
}