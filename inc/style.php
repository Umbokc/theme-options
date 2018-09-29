<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<link rel="stylesheet" href="<?= $this->current_plugin_path ?>/ea.css">
<style>
	[ea] [mh\:100px] {min-height: 100px !important;}
	.logo,.modal__box{text-align:center}
	.u-tab,.u_sidebar li span{cursor:pointer}
	.modal{z-index: 1;opacity:0;visibility:hidden;display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-align:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.7);-webkit-transition:.3s ease-in-out;transition:.3s ease-in-out}.modal__box{padding:1em;background:#fff;box-shadow:0 0 10px 0 rgba(0,0,0,.2);-webkit-transition:all .3s cubic-bezier(.2,.9,.3,1.5);transition:all .3s cubic-bezier(.2,.9,.3,1.5);border-top:5px solid #3dda58;border-bottom:5px solid #ddd}
	.u-truncate{max-width:100%;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
	.u_b_c_p{padding-left:10px;border-bottom:1px solid #999;padding-bottom:10px}.u_b_c_m{margin-left:10px}.logo,.logo a p,.title h2,
	.u_sidebar li span,h5{margin:0}.u_b_c_n{display:none}.u-tab:after{content:"\25bc";-webkit-transform:rotate(90deg);transform:rotate(90deg);position:relative;display:inline-block;left:5px;top:1px}.u-tab.active:after{-webkit-transform:rotate(0);transform:rotate(0)}.u-form-theme-option>*,h1,h2,h3,h4,h5,h6{color:#777}.logo{font-weight:400;background:#19a0c5;padding:20px 0}.logo a{line-height:100%;font-size:2em;color:#fff;text-decoration:none}.u_sidebar{width:20%;float:left;background:#34393d}.submit{clear:both}
	.u_sidebar li span{background-color:#34393d;padding:20px 10px 20px 10px;margin-left: 10px;display:block;color:#999;font-weight:700;text-shadow:1px 1px 2px rgba(0,0,0,.3)}
	.u_content{float:left;padding:10px 30px;background:#fff;width:70%}input,textarea{font-family:sans-serif;font-size:100%;line-height:1.15;margin:0}.u_content .u_content_item input[type=text],.u_content .u_content_item textarea{width:100%;border-radius:4px;border:1px solid #ddd;padding:10px;box-sizing:border-box;outline:0;box-shadow:none}textarea{width:100%;min-height:150px}.u_content_item{display:none}.u_content_item:first-child{display:block}.list_content{padding-left:20px}.title h2{padding:20px;font-weight:400;border-bottom:1px solid #DDD;font-size:3em;line-height:100%}.u-btn-primary,.uploader .u-btn-upload{display:block;color:#fff;text-shadow:none;padding:10px;font-weight:400;background:#44c4e7;text-align:center;border-radius:4px;border:none;outline:0;cursor:pointer}.u_content .u_content_item .uploader input{width:50%}.uploader .u-btn-upload{display:inline-block;height:auto;line-height:100%}.u_blocks{margin-left:10px}.u_content h4{margin-bottom:5px}
	.u_sidebar li.active span,.u_sidebar li span:hover{color:#c1c1c1;background-color:#40464b}
	.u-btn-danger{margin-top: 10px;}
	.the_item h4{cursor: pointer;}
	li.sub-section:hover span{color: #999;background-color: #34393D;}
	.wp-editor-area{min-height: auto;} .wp-editor-cols-2{ min-height: 60px; } .wp-editor-cols-3{ min-height: 80px; } .wp-editor-cols-3{ min-height: 100px; } .wp-editor-cols-4{ min-height: 120px; } .wp-editor-cols-5{ min-height: 140px; } .wp-editor-cols-6{ min-height: 160px; } .wp-editor-cols-7{ min-height: 180px; } .wp-editor-cols-8{ min-height: 200px; }
	.u\:dn{display: none;}
	.u_blocks {margin-left: 10px; position: relative;}
	.u_sidebar li span{padding: 15px 10px 15px 10px; margin-left: 11px;}
	ul.u_blocks::before {content: "";position: absolute;left: 10px;height: calc(100%);top: 0px;width: 1px; background: #b9b5b5;}
	.modal form {width: 250px; padding: 3em;}
	.modal form label{display: block;text-align: left;padding-top: 10px;padding-bottom: 2px;}
	.modal form select, .modal form input { width: 100%; }
	.modal .bg-modal{ position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; }
	.d\:n{display: none;}
	.u_sidebar_item ,.u_sidebar_show {position: relative;}
	.u_sidebar_item span.icon,.u_sidebar_show span.icon{position: absolute;right: 0;background: transparent !important;top: 0;padding: 4px;margin: 0;}
	.u_sidebar_item span.icon.edit-sect, .u_sidebar_show span.icon.edit-sect{right: 15px;}
	.u_sidebar_show > .title:before{content: "\25bc";-webkit-transform: rotate(270deg);transform: rotate(270deg);position: relative;display: inline-block;left: -7px;top: 0px;}
	span.icon.edit-sect, span.icon.edit-field{display: none;}
	.the_item,.the_tab {position: relative;}
	.the_item .icon.delete-field, .the_tab .icon.delete-field{position: absolute;left: -12px;top: 1px; cursor: pointer;}
	h1{font-size: 1.5em;}
	.modal.show{visibility: visible !important; opacity: 1 !important;}
	<?php if (!$this->dev): ?>
		.edit-sect, .delete-sect, .edit-field, .delete-field
		{display: none !important; visibility: hidden !important; opacity: 0 !important;}
		/*[the_last_arr_el] .edit-sect,*/
		[the_last_arr_el] .delete-sect,
		/*[the_last_arr_el] .edit-field,*/
		[the_last_arr_el] .delete-field
		{display: block !important; visibility: visible !important; opacity: 1 !important;}
	<?php endif ?>
</style>