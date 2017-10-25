<?php 
return array(
	'u_section_1' => array(
		'label' => 'Секция первого уровня',
		'type' => 'sub-section',
		'sections' => array(
			'site' => array(
				'label' => 'Секция второго уровня',
				'type' => 'sub-section',
				'sections' => array(
					'first_section' => array(
						'label' => 'Секция третьего уровня',
						'fields' => array(
							'title' => array(
								'label' => 'Заголовок',
								'type' => 'input',
								'default' => 'Разработка сайтов'
							),
							'description' => array(
								'label' => 'Описание',
								'type' => 'wp_editor',
								'cols' => '6',
								'default' => 'пусто'
							),
						)
					),
					'second_section' => array(
						'label' => 'Секция третьего уровня 2',
						'fields' => array(
							'title' => array(
								'label' => 'Заголовок',
								'type' => 'input',
								'default' => 'Какие сайты мы делаем?'
							),
							'h3_1' => array(
								'label' => 'Блоки',
								'type' => 'h3',
								'default' => 'Разработка сайтов'
							),
							'block-1' => array(
								'label' => 'Первый блок',
								'type' => 'tab',
								'fields' => array(
									'icon' => array(
										'label' => 'Иконка',
										'type' => 'input',
										'default' => 'icon-basic-lightbulb'
									),
									'title' => array(
										'label' => 'Заголовок',
										'type' => 'input',
										'default' => 'Промо-страница'
									),
									'sub-title' => array(
										'label' => 'Под заголовок',
										'type' => 'input',
										'default' => 'от 25 000 руб'
									),
									'text' => array(
										'label' => 'Текст',
										'type' => 'wp_editor',
										'cols' => '3',
										'default' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quis cum impedit, necessitatibus quam et ipsum voluptatem saepe id non quia, excepturi sed quaerat aperiam dolorem obcaecati eveniet est dignissimos quibusdam!'
									),
								),
							),
							'block-2' => array(
								'label' => 'Второй блок',
								'type' => 'tab',
								'fields' => array(
									'icon' => array(
										'label' => 'Иконка',
										'type' => 'input',
										'default' => 'icon-basic-heart'
									),
									'title' => array(
										'label' => 'Заголовок',
										'type' => 'input',
										'default' => 'Корпоративный сайт'
									),
									'sub-title' => array(
										'label' => 'Под заголовок',
										'type' => 'input',
										'default' => 'от 80 000 руб'
									),
									'text' => array(
										'label' => 'Текст',
										'type' => 'wp_editor',
										'cols' => '3',
										'default' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quis cum impedit, necessitatibus quam et ipsum voluptatem saepe id non quia, excepturi sed quaerat aperiam dolorem obcaecati eveniet est dignissimos quibusdam!'
									),
								),
							),
						)
					),
				),
			),
		),
	),
);