<script src="<?= $this->current_plugin_path ?>/ea.js"></script>
<script>
	document.addEventListener('DOMContentLoaded', function(){
		window.$ = window.jQuery;

		window.App = {
			get_from: document,
			json_data: <?= ($this->data_file_json) ?: '{}' ?>,
			$id: function(id){
				var val = this.get_from.getElementById(id);
				this.reset_get_from();
				return val;
			},
			$name: function(name){
				var val = this.get_from.getElementsByName(name);
				this.reset_get_from();
				return val;
			},
			$get: function(sel){
				return this.$gets(sel)[0];
			},
			get: function(sel){
				return this.$get(sel);
			},
			$gets: function(sel){
				var val = this.get_from.querySelectorAll(sel);
				this.reset_get_from();
				return val;
			},
			gets: function(sel){
				return this.$gets(sel);
			},
			is_str: function(str){
				return typeof str === typeof '';
			},
			save_json: function(){
				$.ajax({
					type: 'POST',
					data: {json_data: App.json_data},
					dataType: 'json',
					success: function(e){
						App.dbg('Success: ');
						App.dbg(e);
					}
				}).done(function(e){
					App.dbg('Done: ');
					App.dbg(e);
				}).fail(function(e){
					App.dbg('Fail: ');
					App.dbg(e);
				}).always(function(e){
					App.dbg('Always: ');
					App.dbg(e);
				});
			},
			insertBefore: function(to, str){
				$(str).insertBefore(to);
				App.reset_event();
			},
			reset_event: function(){
				$('.u-wrap-theme *').off('click');
				$('.u-wrap-theme *').off('submit');
				App.event();
				App.event_dev();
			},
			reset_get_from: function(){
				this.get_from = document;
			},
			dbg: function(mes){
				window.console.log(mes);
				window.dbg = mes;
			},
			init: function(){
				this.event();
				this.event_dev();
			},
			toogle_modal: function(select){
				var item = $(select);
				if(item.css('opacity') == '0')
					item.css({'opacity':'1','visibility':'visible'});
				else
					item.css({'opacity':'0','visibility':'hidden'});
			},
			event: function(){
				var format_indices_to_name = function(str){
					var data, indices_to_name = '<?= $this->theme_option_name ?>';
					if(App.is_str(str)) data = str.split(',');
					else data = str;
					for (var i = 0; i < data.length; i++) {
						var prop = data[i];
						indices_to_name += '[' + prop + ']';
					}

					return indices_to_name;
				};
				$('.modal .bg-modal').click(function(e){
					App.toogle_modal(e.target.parentNode);
				});
				$('.u_sidebar_item .title').click(function(){
					$('.u_sidebar_item').removeClass("active");
					var the = $(this).parent();
					the.addClass("active");

					$('.u_content_item').hide();
					$('.' + the.attr('to')).show();
				});

				$('.show_blocks > .title').click(function(){
					$($(this).parent().children('ul')).slideToggle();
				});

				$('.u-global-title').keyup(function(){
					var val = $(this).val();
					var u_change = $(this).attr('u-change');
					if(u_change)
						$('.' + u_change).text(val);

					$(this).parents('.u_content_item').find('.title h2 span').text(val);
				});

				$('.u-tab').click(function(){
					$(this).toggleClass('active');
					// var elem = '.' + $(this).attr('class').split(' ').join('.') + ' + .' + $(this).attr('to');
					$(this).parent().find('>.the_tab_wrap').slideToggle();
				});

				$('.the_item h4').click(function(){
					$(this).parent('.the_item').find('>input, >textarea, >.uploader').slideToggle();
				});

				$('.delete-all-options').click(function(e){
					if(confirm("Вы уверенны?")){
						$.ajax({
							type: 'POST',
							data: {delete_options: true},
						}).done(function(e) {
							location.reload();
						}).fail(function(e){
							// App.dbg('Fail: ' + e);
						});
					}
					e.preventDefault();
				});

				$('#modal-success').click(function(){
					App.toogle_modal(this);
				});

				$('.u-form-theme-option').submit(function(e){
					e.preventDefault();
					App.toogle_modal('#modal-processiog');
					$(this).find('input').each(function(index, el) {
						var the = $(el), indices;
						if(the.parent().hasClass('uploader'))
							indices = the.parent().parent().attr('indices');
						if(indices != undefined && !the.hasClass('photo_media_id')){
							the.attr('name', format_indices_to_name(indices));
						}
					});
					$.ajax({
						type: 'POST',
						url: $(this).attr('action'),
						data: $(this).serialize(),
					}).done(function(e) {
						App.toogle_modal('#modal-processiog');
						App.toogle_modal('#modal-success');
					}).always(function(e){
						App.dbg('Always: ');
						App.dbg(e);
					});
				});

				$('.uploader').click(function(e) {
					e.preventDefault();
					var ths = $(this);
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
						$(ths.find('.photo_media_image')).attr('src', attachment.url);
						$(ths.find('.photo_media_url')).val(attachment.url);
						$(ths.find('.photo_media_id')).val(attachment.id);
					})
					.open();
				});
			},
			event_dev: function(){
				var add_input = function(indices, name, default_val){
					var inp = $('[to_clone] .the_item[type="input"]').clone();
					inp.find('.the_title').text(name);
					inp.find('input').attr({name: '<?= $this->theme_option_name ?>' + indices, value: default_val});
					App.insertBefore(App.here_add_field.parent(), inp);
				}
				var add_img = function(indices, name, default_val){
					var img = $('[to_clone] .the_item[type="img"]').clone();
					img.find('.the_title').text(name);
					img.find('img').attr({src: default_val});
					img.find('input.photo_media_url').attr({name: '<?= $this->theme_option_name ?>' + indices, value: default_val});
					App.insertBefore(App.here_add_field.parent(), img);
				}
				var add_tab = function(indices, name){
					var tab = $('[to_clone] .the_tab[type="tab"]').clone();
					tab.find('>.u-tab').text(name);
					tab.find('.u_add_field').attr({indices: indices});
					App.insertBefore(App.here_add_field.parent(), tab);
				}
				var add_textarea = function(indices, name, default_val){
					var textarea = $('[to_clone] .the_item[type="textarea"]').clone();
					textarea.find('.the_title').text(name);
					textarea.find('textarea').attr({name: '<?= $this->theme_option_name ?>' + indices}).val(default_val);
					App.insertBefore(App.here_add_field.parent(), textarea);
				}
				var add_wp_editor = function(indices, name, default_val){
					var textarea = $('[to_clone] .the_item[type="wp_editor"]').clone();
					textarea.find('.the_title').text(name);
					textarea.find('textarea').attr({name: '<?= $this->theme_option_name ?>' + indices}).val(default_val);
					App.insertBefore(App.here_add_field.parent(), textarea);
				}
				var add_h = function(h_t,indices, name){
					var h = $('[to_clone] .the_item[type="'+h_t+'"]').clone();
					h.find(h_t).text(name);
					h.attr('indices', indices);
					App.insertBefore(App.here_add_field.parent(), h);
				}
				var add_array = function(indices, name, arr_type){
					var arr = $('[to_clone] .the_item[type="array"]').clone();
					arr.find('>.title').text(name);
					arr.find('.u_add_field').attr({
						indices: indices,
						type: arr_type,

					});
					App.insertBefore(App.here_add_field.parent(), arr);
				}

				var format_indices = function (str, call_func){
					var data, the_section, the_data,
							indices_to_eval = '',
							indices_to_eval_del = '',
							indices_to_name = '<?= $this->theme_option_name ?>', indices_to_tab = '';
					if(App.is_str(str)) data = str.split(',');
					else data = str;

					the_data = App.json_data;

					for (var i = 0; i < data.length; i++) {
						var prop = data[i];

						indices_to_name += '[' + prop + ']';

						indices_to_tab += prop;
						if(i+1 != data.length)
							indices_to_tab += ',';

						the_section = the_data[prop].hasOwnProperty('sections');

						indices_to_eval += '["' + prop + '"]';
						indices_to_eval_del = indices_to_eval;

						if(the_section) indices_to_eval += '.sections';
						else if(the_data[prop].hasOwnProperty('fields')) indices_to_eval += '.fields';

						if(i+1 != data.length) indices_to_eval_del = indices_to_eval;

						the_data = (the_section == true ? the_data[prop].sections : the_data[prop].fields);

						if(call_func !== undefined) call_func(prop, i);
					}

					return {
						to_eval_del: indices_to_eval_del,
						to_eval: indices_to_eval,
						to_tab: indices_to_tab,
						to_name: indices_to_name,
					};
				}

				$('.u_add_section').click(function(event) {
					App.here_add_section = $(this);
					App.toogle_modal($('#modal-add-section'));
				});

				var add_section = function(alias, indices, name){
					App.insertBefore(App.here_add_section,
						'<li class="u_sidebar_item u-truncate" to="_content_'+alias+'_" indices="'+indices+'">\
						<span class="title">'+name+'</span>\
						<span class="icon edit-sect fa fa-pencil"></span><span class="icon delete-sect fa fa-close"></span>\
						</li>'
					);
					$('.u_content').append(
						'<div class="u_content_item _content_'+alias+'_" style="display: none;">\
							<div class="title">\
								<h2>'+name+'</h2>\
							</div>\
							<div class="the_item">\
								<h4 class="u_add_field" indices="'+indices+'">+ Добавить элемент</h4>\
							</div>\
						</div>'
					);
					App.reset_event();
				}
				var add_sub_section = function(parents, name){
					App.insertBefore(App.here_add_section,
						'<li class="show_blocks u_sidebar_show" indices="'+parents+'">\
							<span class="title">'+name+'</span> \
							<span class="icon edit-sect fa fa-pencil"></span><span class="icon delete-sect fa fa-close"></span>\
						<ul class="u_blocks">\
						<li class="u_add_section" parents="'+parents+'"><span>+ Добавить</span></li></ul></li>'
					);
				}
				$('#modal-add-section').submit(function(event) {
					event.preventDefault();
					var f_alias = event.target.alias;
					var f_name = event.target.name;
					var f_type = event.target.type;
					var alias = f_alias.value.trim();
					var name = f_name.value.trim();
					var type = f_type.value.trim();

					if (alias == '' || name == '') return;
					var parents = App.here_add_section.attr('parents').split(',');

					if(parents[0] != ''){
						var parents_to_eval = '';
						var parents_to_add = '';
						var parents_to_content = '';
						for (var i = 0; i < parents.length; i++) {
							prop = parents[i];
							if(prop != ''){
								parents_to_eval += '["' + prop + '"].sections';
								parents_to_add += prop;
								parents_to_content += prop + '_';
								if(i+1 != parents.length) parents_to_add += ',';
							}
						}

						parents_to_add += ',' + alias;
						parents_to_content +=  alias;

						if (!eval('App.json_data' + parents_to_eval + '.hasOwnProperty("' + alias + '")')) {
							if(type == 'sub-section'){
								eval('App.json_data' + parents_to_eval + '["' + alias + '"] = {label: name, sections: {}}')
								add_sub_section(parents_to_add, name);
							} else {
								eval('App.json_data' + parents_to_eval + '["' + alias + '"] = {label: name, fields: {}}')
								add_section(parents_to_content, parents_to_add, name);
							}
							App.save_json();
						} else {
							$(f_alias).css('border-color', 'red');
						}
					} else {
						if(!App.json_data.hasOwnProperty(alias)){
							if(type == 'sub-section'){
								App.json_data[alias] = {label: name, sections: {}};
								add_sub_section(alias, name);
							} else {
								App.json_data[alias] = {label: name, fields: {}};
								add_section(alias, alias, name);
							}
							App.save_json();
						} else {
							$(f_alias).css('border-color', 'red');
						}
					}

					App.toogle_modal($(this));
					$(this).find('form')[0].reset();
				});
				$('.u_add_field').click(function(event) {
					App.here_add_field = $(this);
					App.toogle_modal($('#modal-add-field'));
				});
				$('#modal-add-field form select[name="type"]').change(function(event) {
					if(event.target.value != 'array'){
						$('#modal-add-field form select[name="array-type"]').parent().addClass('d:n');
					} else {
						$('#modal-add-field form select[name="array-type"]').parent().removeClass('d:n');
					}
					if(event.target.value == 'textarea' || event.target.value == 'wp_editor'){
						$('#modal-add-field form input[name="default"]').addClass('d:n');
						$('#modal-add-field form textarea[name="default"]').removeClass('d:n');
					} else {
						$('#modal-add-field form input[name="default"]').removeClass('d:n');
						$('#modal-add-field form textarea[name="default"]').addClass('d:n');
					}
				});
				$('#modal-add-field').submit(function(event) {
					event.preventDefault();
					var the_arr = App.here_add_field.hasClass('add-array');
					var f_alias, alias, name, type, arr_type, default_value,
							indices,indices_name,indices_to_eval,the_data,indices_tab,the_section;
					if(!the_arr){
						f_alias = event.target.elements.alias;
						alias = f_alias.value.trim();
						type = event.target.elements.type.value.trim();
					} else {
						alias = parseInt(App.here_add_field.attr('last')) + 1;
						type = App.here_add_field.attr('type');
					}
					default_value = (
						(event.target.elements['default'][0].className == 'd:n') ?
						event.target.elements['default'][1].value.trim() :
						event.target.elements['default'][0].value.trim()
					);
					name = event.target.elements.name.value.trim();

					if (alias == '' || name == '') return;

					indices_name = '';
					indices_to_eval = '';
					the_data = App.json_data;
					indices_tab = '';

					format_indices(App.here_add_field.attr('indices'), function(prop, i){
						indices_name += '[' + prop + ']';
						indices_tab += prop + ',';

						the_section = the_data[prop].hasOwnProperty('sections');
						the_data = (the_section == true ? the_data[prop].sections : the_data[prop].fields);

						indices_to_eval += '["' + prop + '"].';
						indices_to_eval += the_section ? 'sections' : 'fields';
					});

					indices_name += '[' + alias + ']';
					eval('if(App.json_data' + indices_to_eval + ' == undefined) App.json_data' + indices_to_eval + ' = {}');
					indices_to_eval += '["' + alias + '"]';
					indices_tab += alias;

					if(eval('App.$get(\'[name="<?= $this->theme_option_name ?>' + indices_name + '"]\')') == undefined){
						if(type == 'input'){
							add_input(indices_name, name, default_value);
							eval('App.json_data' + indices_to_eval + ' = {label: "'+name+'", type: "input", default: "'+default_value+'"}');
							App.save_json();
						} else if(type == 'img') {
							add_img(indices_name, name, default_value);
							eval('App.json_data' + indices_to_eval + ' = {label: "'+name+'", type: "img", default: "'+default_value+'"}');
							App.save_json();
						} else if(type == 'textarea') {
							add_textarea(indices_name, name, default_value);
							eval('App.json_data' + indices_to_eval + ' = {label: "'+name+'", type: "textarea", default: "'+default_value+'"}');
							App.save_json();
						} else if(type == 'wp_editor') {
							add_wp_editor(indices_name, name, default_value);
							eval('App.json_data' + indices_to_eval + ' = {label: "'+name+'", type: "wp_editor", default: "'+default_value+'"}');
							App.save_json();
						} else if(type == 'tab') {
							add_tab(indices_tab, name);
							eval('App.json_data' + indices_to_eval + ' = {label: "'+name+'", type: "tab", fields: {}}');
							App.save_json();
						} else if(type == 'array') {
							arr_type = event.target.elements['array-type'].value;
							add_array(indices_tab, name, arr_type);
							eval('App.json_data' + indices_to_eval + ' = {label: "'+name+'", type: "array", fields: {}}');
							App.save_json();
						} else if(type == 'h2' || type == 'h3') {
							var h_t = type;
							add_h(h_t, indices_tab, name);
							eval('App.json_data' + indices_to_eval + ' = {label: "'+name+'", type: "'+h_t+'"}');
							App.save_json();
						}
					} else {
						$(f_alias).css('border-color', 'red');  
					}
					
					App.toogle_modal($(this));
					$(this).find('form')[0].reset();
				});

				$('.u_add_array').click(function(event) {
					App.here_add_arr_item = $(this);
					App.toogle_modal($('#modal-add-array'));
				});
				$('#modal-add-array').submit(function(event) {
					event.preventDefault();
					var prev_alias, alias, name;
					alias = parseInt(App.here_add_arr_item.attr('last').replace('a_','')) + 1;
					prev_alias = 'a_' + (alias - 1);
					alias = 'a_' + alias;

					name = event.target.elements.name.value.trim();
					if (alias == '' || name == '') return;

					var indices, new_indices, indices_to_eval,
							new_obj, the_last_arr_el, arr_wrap;

					arr_wrap = App.here_add_arr_item.parent().parent();
					the_last_arr_el = arr_wrap.find('>[the_last_arr_el]');
					if(the_last_arr_el.length == 0){
						the_last_arr_el = $(arr_wrap.children()[0]);
					}
					new_obj = the_last_arr_el.clone();
					the_last_arr_el.removeAttr('the_last_arr_el');
					new_obj.attr('the_last_arr_el', '');

					indices = App.here_add_arr_item.attr('indices').split(',');
					new_indices = indices.slice();
					new_indices.push(alias);
					new_obj.attr('indices', new_indices);
					new_obj.find('>.the_name_item').text(name);

					var f_i = format_indices(indices);

					indices_to_eval = f_i.to_eval;
					var field = eval("Object.assign({}, App.json_data" + indices_to_eval + '["' + (prev_alias)  + '"])');
					field.label = name;
					eval("App.json_data" + indices_to_eval + '["' + alias + '"] = field');

					App.insertBefore(App.here_add_arr_item.parent(), new_obj);
					App.dbg();
					App.save_json();

					App.here_add_arr_item.attr('last', alias);
				});

				$('.delete-sect').click(function(event) {
					var the = $(this).parent();
					var indices = the.attr('indices').split(',');
					if(confirm('Are you sure?')){
					// if(true){
						var indices_to_eval = '';
						for (var i = 0; i < indices.length; i++) {
							prop = indices[i];
							if(prop != ''){
								indices_to_eval += '["' + prop + '"]';
								if(i+1 != indices.length)
									indices_to_eval += '.sections';
							}
						}
						eval('delete App.json_data' + indices_to_eval);
						the.remove();
						App.save_json();
					}
				});
				$('.delete-field').click(function(event) {
					var the = $(this).parent();
					// if(confirm('Are you sure?')){
					if(1){
						var indices_to_eval = format_indices(the.attr('indices').split(','));
						App.dbg(indices_to_eval);
						if(the.parent().hasClass('the_array_wrap')){
							var new_last_i = parseInt(the.attr('index')) - 1;
							var new_last = the.parent().find('>[index="'+new_last_i+'"]');
							App.dbg(new_last);
						}
						// eval('delete App.json_data' + indices_to_eval);
						// the.remove();
						// App.save_json();
					}
				});
			},
		};

		App.init();
	});
</script>
