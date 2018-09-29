<?php 
/**
* Author: umbokc
* Author: github.com/Umbokc
* ThemeOptions
*/
class ThemeOptions {

	private $version = '2.0.0';

	private $current_plugin_path = '';

	private $current_path = '';
	private $data_file_path = '';

	private $mode_php = false;
	private $dev = !!true;
	private $the_last_arr_el = false;

	public $data = array();
	public $data_db = array();

	public $theme_option_name = '';
	public $theme_option_group = '';

	private $sidebar_sections = array();
	private $attr_name = array();

	function __construct(string $option_name, string $option_group) {
		$this->mode_php = UCTO_MODE_PHP;
		$this->current_path = dirname(__FILE__);
		$this->current_plugin_path = get_template_directory_uri() . '/plugins/ThemeOptions';
		$this->data_file_path = $this->current_path.'/data.' . (($this->mode_php) ? 'php' : 'json');
		$this->theme_option_name = $option_name;
		$this->theme_option_group = $option_group;
		$this->init();
	}

	final private function remove_last_char($string, $separator = ',', $new_separator = ''){
		return strrev(implode(strrev($new_separator), explode(strrev( $separator), strrev($string), 2)));
	}

	public function init(){

		if(isset($_POST['delete_options'])){
			delete_option( $this->theme_option_name );
		}
		if(isset($_POST['json_data'])){
			$data = $_POST['json_data'];
			$this->save(json_encode($data));
			echo json_encode('ok');
			die;
		}

		if(function_exists( 'wp_enqueue_media' )){
			wp_enqueue_media();
		}else{
			wp_enqueue_style('thickbox');
			wp_enqueue_script('media-upload');
			wp_enqueue_script('thickbox');
		}
	}

	public function run(){
		if ($this->mode_php)
			$this->data_file_json = json_encode((include_once $this->data_file_path), true);
		else
			$this->data_file_json = file_get_contents($this->data_file_path);

		$this->data = json_decode($this->data_file_json, true);
		$this->get_body();
		$this->get_style();
		$this->get_script();
	}

	public function save($data_json){
		if ($this->mode_php)
			file_put_contents($this->data_file_path,  '<?php return ' . var_export(json_decode($data_json, true), true) . ';');
		else
			file_put_contents($this->data_file_path, $data_json);
	}

	public function get_data($the_form = false){
		if($the_form)
			settings_fields( $this->theme_option_group );
		$this->data_db = get_option( $this->theme_option_name );
		if(!$this->data_db) $this->data_db = array();
		return $this->data_db;
	}

	private function get_body(){
		?>
		<div class="wrap u-wrap-theme" ea>
			<?= "<h2>" . 'Настройка темы: ' . get_current_theme() . "</h2>"; ?>
			<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
				<div class="updated fade"><p><strong><?=  'Сохранение прошло успешно'; ?></strong></p></div>
			<?php endif; ?>

			<form method="post" class="u-form-theme-option" action="options.php">
				<?php
				$this->get_data(true);
				?>
				<p class="submit">
					<input type="submit" class="u-btn-primary" value="<?=  'Сохранить настройки'; ?>" />
				</p>

				<?php $this->get_sidebar() ?>
				<?php $this->get_content() ?>

				<p class="submit">
					<input type="submit" class="u-btn-primary" value="<?=  'Сохранить настройки'; ?>" />
					<input type="button" class="u-btn-danger delete-all-options" value="<?=  'Восстановить настройки'; ?>" />
				</p>
			</form>
			<?php $this->get_modal_windows() ?>
			<div d:n v:h to_clone>
				<?php $this->get_items_to_clone() ?>
			</div>
		</div>
		<?php
	}

	private function get_sidebar(){
		?>
		<div class="u_sidebar">
			<div class="logo">
				<a href="https://umbokc.github.io" target="_blank">Umbokc<p>Theme</p></a>
			</div>
			<ul>
				<?php
				$i = 0;
				foreach($this->data as $key => $value){
					$this->add_side_sect($key);
					$this->get_item_sidebar($i, $key, $value);
					$this->pop_side_sect();
					$i++;
				}
				if ($this->dev) echo "<li class=\"u_add_section\" parents=\"\"><span>+ Добавить</span></li>";
				?>
			</ul>
		</div>
		<?php
	}

	private function get_item_sidebar($i, $key, $value){
		?>
		<?php if(isset($value['sections'])): ?>
			<?php // $class_sidebar = $this->get_side_sect(); ?>
			<li class="show_blocks u_sidebar_show" indices="<?= $this->get_content_indices() ?>">
				<span class="title"><?= $value['label'] ?></span>
				<span class="icon edit-sect fa fa-pencil"></span><span class="icon delete-sect fa fa-close"></span>
				<ul class="u_blocks">
					<?php
					$j = 0;
					if ($i != 0) $j = 1;
					foreach($value['sections'] as $s_key => $s_value){
						$this->add_side_sect($s_key);
						$this->get_item_sidebar($j, $s_key, $s_value);
						$this->pop_side_sect();
						$j++;
					}
					if ($this->dev)
						echo "<li class=\"u_add_section\" parents=\"".$this->get_side_parents()."\"><span>+ Добавить</span></li>";
					?>
				</ul>
			</li>
		<?php else: ?>
			<li class="u_sidebar_item u-truncate <?= ($i == 0) ? 'active' : '' ?>"
				to="<?= $this->get_side_sect_content(); ?>" indices="<?= $this->get_content_indices() ?>">
				<span class="title"><?= $value['label'] ?></span>
				<span class="icon edit-sect fa fa-pencil"></span><span class="icon delete-sect fa fa-close"></span>
			</li>
		<?php endif ?>
		<?php
	}

	private function get_content(){
		echo '<div class="u_content">';
		foreach($this->data as $key => $value){
			$this->add_side_sect($key);
			$this->get_item_content($key, $value);
			$this->pop_side_sect();
		}
		echo '</div>';
	}

	private function get_item_content($key, $value){
		$class_sidebar = $this->get_side_sect_content();
		if(isset($value['sections'])):
			foreach($value['sections'] as $s_key => $s_value){
				$this->add_side_sect($s_key);
				$this->get_item_content($s_key, $s_value);
				$this->pop_side_sect();
			}
			?>
		<?php else: ?>
			<div class="u_content_item <?= $class_sidebar ?>">
				<div class="title">
					<h2><?= $value['label']; ?></h2>
				</div>
				<?php $this->get_fields($value) ?>
				<?php if ($this->dev): ?>
					<div class="the_item">
						<h4 class="u_add_field" indices="<?= $this->get_content_indices() ?>">+ Добавить элемент</h4>
					</div>
				<?php endif ?>
			</div>
		<?php endif ?>
		<?php
	}

	private function get_fields($value){
		if (!$value['fields'])
			return;
		foreach($value['fields'] as $key => $value){
			$this->add_attr_name($key);
			$this->get_item_field($key, $value);
			$this->pop_attr_name();
		}
	}

	private function get_item_field($key, $data){
		$tab = $this->get_attr_name_tab();
		if(false and $this->get_isset($data['type']) == 'tab') { ?>
			<div class="the_tab" indices="<?= $this->get_content_indices() ?>">
				<span class="icon edit-field fa fa-pencil"></span><span class="icon delete-field fa fa-close"></span>
				<h4 class="u-tab the_name_item"><?=  $data['label'];?></h4>
				<div class="u_b_c_p u_b_c_n the_tab_wrap">
					<?php
					foreach ($data['fields'] as $s_key => $s_value) {
						$this->add_attr_name($s_key);
						$this->get_item_field($s_key, $s_value);
						$this->pop_attr_name();
					}
					?>
					<?php if ($this->dev): ?>
						<div class="the_item">
							<h4 class="u_add_field" indices="<?= $this->get_content_indices() ?>">+ Добавить элемент</h4>
						</div>
					<?php endif ?>
				</div>
			</div>
			<?php
		} else{
			$this->get_item($data);
		}
	}

	private function get_items_array($data){
		$size = count($data);
		$i = 0;
		foreach ($data as $s_key => $s_value) {
			if($i+1 == count($data) and $i+1 != 1) $this->the_last_arr_el = true;
			$this->add_attr_name($s_key);
			$this->get_item_field($s_key, $s_value);
			$this->pop_attr_name();
			if($i+1 == count($data) and $i+1 != 1) $this->the_last_arr_el = false;
			$i++;
		}
		?>
		<div class="the_item">
			<h4 class="u_add_array" last='<?= $s_key ?>' indices="<?= $this->get_content_indices() ?>">+ Добавить элемент</h4>
		</div>
		<?php
	}

	private function get_item($opt){
		$name_attr = $this->theme_option_name . $this->get_attr_name();
		$val = $this->get_data_value($opt['default']);
		?>
		<div class="the_item <?= ($opt['type'] == 'tab') ? 'the_tab' : '' ?>" <?= $this->the_last_arr_el==true ? 'the_last_arr_el' : '' ?> indices="<?= $this->get_content_indices() ?>" index="<?= $this->get_content_index() ?>">
			<span class="icon edit-field fa fa-pencil"></span><span class="icon delete-field fa fa-close"></span>

			<?php if($opt['type'] != 'h2' and $opt['type'] != 'h3' and $opt['type'] != 'h4' and $opt['type'] != 'array' and $opt['type'] != 'tab'): ?>
				<h4 class="the_title the_name_item"><?= $opt['label'];?></h4>
			<?php endif ?>

			<?php if($opt['type'] == 'input'): ?>
				<input autocomplete="off" class="regular-text" type="text" name="<?= $name_attr ?>" value="<?= esc_attr($val); ?>" />
			<?php elseif($opt['type'] == 'h2'): ?>
				<h2><?= $opt['label'];?></h2>
			<?php elseif($opt['type'] == 'array'): ?>
				<div class="the_tab" indices="<?= $this->get_content_indices() ?>">
					<h4 class="u-tab"><?=  $opt['label'];?></h4>
					<div class="u_b_c_p u_b_c_n the_tab_wrap the_array_wrap">
						<?php $this->get_items_array($opt['fields']); ?>
					</div>
				</div>
			<?php elseif($opt['type'] == 'tab'): ?>
				<h4 class="u-tab the_name_item"><?=  $opt['label'];?></h4>
				<div class="u_b_c_p u_b_c_n the_tab_wrap">
					<?php
					foreach ($opt['fields'] as $s_key => $s_value) {
						$this->add_attr_name($s_key);
						$this->get_item_field($s_key, $s_value);
						$this->pop_attr_name();
					}
					?>
					<?php if ($this->dev): ?>
						<div class="the_item">
							<h4 class="u_add_field" indices="<?= $this->get_content_indices() ?>">+ Добавить элемент</h4>
						</div>
					<?php endif ?>
				</div>
			<?php elseif($opt['type'] == 'h3'): ?>
				<h3><?= $opt['label'];?></h3>
			<?php elseif($opt['type'] == 'textarea'): ?>
				<textarea name="<?= $name_attr ?>" style="<?= (isset($opt['height'])) ? 'min-height:' . $opt['height'] : '' ?>"><?= $val ?></textarea>
			<?php elseif($opt['type'] == 'wp_editor'): ?>
				<?php
				$settings = array(
					'wpautop' => true,
					'media_buttons' =>  (isset($opt['media_buttons'])) ? $opt['media_buttons'] : false,
					'textarea_name' => $name_attr,
					'textarea_rows' => get_option('default_post_edit_rows', 1),
					'teeny' => true,
					'editor_class' => (isset($opt['cols'])) ? 'wp-editor-cols-' . $opt['cols'] : 'wp-editor-cols-3',
					'dfw' => true,
					'tinymce' => array(
						'theme_advanced_buttons' => 'bold,italic,underline' 
					),
					'quicktags' => (isset($opt['quicktags'])) ? $opt['quicktags'] : true
				);
				wp_editor( $val, $name_attr, $settings );
				?>
			<?php elseif($opt['type'] == 'img'): ?>
				<div class="uploader photo_media_upload">
					<img class="photo_media_image" src="<?= $val ?>" style="max-width:100px; display:block;" alt="<?= $val ?>">
					<input autocomplete="off" class="photo_media_url" type="hidden" name="<?= $name_attr ?>" value="<?= $val ?>" style="margin-bottom:10px; clear:right;">
					<input type="hidden" class="photo_media_id" name="photo_media_id">
					<!-- <a href="#" class="button u-btn-upload">Загрузить</a> -->
				</div>
			<?php endif ?>
		</div>
		<?php
	}

	private function get_items_to_clone(){
		?>
		<div class="the_item" type='h2'>
			<span class="icon edit-field fa fa-pencil"></span><span class="icon delete-field fa fa-close"></span>
			<h2></h2>
		</div>
		<div class="the_item" type='h3'>
			<span class="icon edit-field fa fa-pencil"></span><span class="icon delete-field fa fa-close"></span>
			<h3></h3>
		</div>
		<div class="the_tab" type='tab'>
			<span class="icon edit-field fa fa-pencil"></span><span class="icon delete-field fa fa-close"></span>
			<h4 class="u-tab"></h4>
			<div class="u_b_c_p u_b_c_n the_tab_wrap">
				<div class="the_item">
					<h4 class="u_add_field" indices="">+ Добавить элемент</h4>
				</div>
			</div>
		</div>
		<div class="the_item" type='array'>
			<span class="icon edit-field fa fa-pencil"></span><span class="icon delete-field fa fa-close"></span>
			<h4 class="u-tab title"></h4>
			<div class="u_b_c_p u_b_c_n the_tab_wrap">
				<div class="the_item">
					<h4 class="u_add_field " type='' last='0' indices="">+ Добавить элемент</h4>
				</div>
			</div>
		</div>
		<div class="the_item" type='input'>
			<span class="icon edit-field fa fa-pencil"></span><span class="icon delete-field fa fa-close"></span>
			<h4 class="the_title the_name_item"></h4>
			<input autocomplete="off" class="regular-text" type="text" name="" value="" />
		</div>
		<div class="the_item" type='textarea'>
			<span class="icon edit-field fa fa-pencil"></span><span class="icon delete-field fa fa-close"></span>
			<h4 class="the_title the_name_item"></h4>
			<textarea name=""></textarea>
		</div>
		<div class="the_item" type='wp_editor'>
			<span class="icon edit-field fa fa-pencil"></span><span class="icon delete-field fa fa-close"></span>
			<h4 class="the_title the_name_item"></h4>
				<?php
				$settings = array(
					'wpautop' => true,
					'media_buttons' => false,
					'textarea_name' => 'name_attr',
					'textarea_rows' => get_option('default_post_edit_rows', 1),
					'teeny' => true,
					'editor_class' =>  'wp-editor-cols-3', 
					'dfw' => true,
					'tinymce' => array(
						'theme_advanced_buttons' => 'bold,italic,underline'
					),
					'quicktags' => true
				);
				wp_editor( 'val', 'name_attr', $settings );
				?>
		</div>
		<div class="the_item" type='img'>
			<span class="icon edit-field fa fa-pencil"></span><span class="icon delete-field fa fa-close"></span>
			<h4 class="the_title the_name_item"></h4>
			<div class="uploader photo_media_upload">
				<img class="photo_media_image" src="" style="max-width:100px; display:block;" />
				<input autocomplete="off" class="photo_media_url" type="hidden" name="" value="" style="margin-bottom:10px; clear:right;">
				<input type="hidden" class="photo_media_id" name="photo_media_id">
				<!-- <a href="#" class="button u-btn-upload">Загрузить</a> -->
			</div>
		</div>
		<?php
	}

	private function get_modal_windows() {
		include_once 'inc/modal.php';
	}

	private function get_style(){
		include_once 'inc/style.php';
	}

	private function get_script(){
		include_once 'inc/scripts.php';
	}

	private function get_isset($data){
		return isset($data) ? $data : '';
	}

	private function add_side_sect($value='') {
		$this->sidebar_sections[] = $value;
	}

	private function pop_side_sect() {
		array_pop($this->sidebar_sections);
	}

	private function get_side_sect() {
		$result = "_sidebar_";
		foreach ($this->sidebar_sections as $val) {
			$result .= $val;
			$result .= '_';
		}
		return $result;
	}

	private function get_side_parents() {
		$result = "";
		foreach ($this->sidebar_sections as $val) {
			$result .= $val;
			$result .= ',';
		}
		$result = $this->remove_last_char($result, ',');
		return $result;
	}

	private function get_side_sect_content() {
		$result = "_content_";
		foreach ($this->sidebar_sections as $val) {
			$result .= self::help__genFileName($val);
			$result .= '_';
		}
		return $result;
	}

	private function add_attr_name($value='') {
		$this->attr_name[] = $value;
	}

	private function pop_attr_name() {
		array_pop($this->attr_name);
	}

	private function get_attr_name_tab(){
		$name = "";

		foreach ($this->sidebar_sections as $val) $name .= $val . '_';
		foreach ($this->attr_name as $val) $name .= $val . '_';

		return $name;
	}

	private function get_attr_name(){
		$name = "";
		foreach ($this->sidebar_sections as $val) $name .= '[' . $val . ']';
		foreach ($this->attr_name as $val) $name .= '[' . $val . ']';
		return $name;
	}

	private function get_content_indices(){
		$name = "";
		foreach ($this->sidebar_sections as $val) $name .= $val . ',';
		foreach ($this->attr_name as $val) $name .= $val . ',';
		return $this->remove_last_char($name, ',');
	}

	private function get_content_index(){
		$name = [];
		if(count($this->sidebar_sections) > 0) $name = end($this->sidebar_sections);
		if(count($this->attr_name) > 0) $name = end($this->attr_name);
		return $name;
	}

	private function get_data_value($default) {
		$current_data = $this->data_db;

		foreach ($this->sidebar_sections as $name){
			if (key_exists($name, $current_data)){
				$current_data = $current_data[$name];
			} else{
				return $default;
			}
		}
		foreach ($this->attr_name as $name){
			if (key_exists($name, $current_data)){
				$current_data = $current_data[$name];
			} else{
				return $default;
			}
		}
		
		return $current_data;
	}

	private static $help__cyr = [
		'а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п',
		'р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',
		'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П',
		'Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я'
	];

	private static $help__lat = [
		'a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','o','p',
		'r','s','t','u','f','h','ts','ch','sh','sht','a','i','y','e','yu','ya',
		'A','B','V','G','D','E','Io','Zh','Z','I','Y','K','L','M','N','O','P',
		'R','S','T','U','F','H','Ts','Ch','Sh','Sht','A','I','Y','e','Yu','Ya'
	];
	private static function help__cyrToLat($str){
		return str_replace(self::$help__cyr, self::$help__lat, $str);
	}
	private static function help__genFileName($str){
		$str = strtolower(self::help__cyrToLat($str));
		$str = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $str);
		$str = mb_ereg_replace("([\.]{2,})", '', $str);
		$str = strip_tags($str); 
		$str = preg_replace('/[\r\n\t ]+/', ' ', $str);
		$str = preg_replace('/[\"\*\/\:\<\>\?\'\|]+/', ' ', $str);
		$str = strtolower($str);
		$str = html_entity_decode( $str, ENT_QUOTES, "utf-8" );
		$str = htmlentities($str, ENT_QUOTES, "utf-8");
		$str = preg_replace("/(&)([a-z])([a-z]+;)/i", '$2', $str);
		$str = str_replace(' ', '-', $str);
		$str = rawurlencode($str);
		$str = str_replace('%', '-', $str);
		return $str;
	}
}
