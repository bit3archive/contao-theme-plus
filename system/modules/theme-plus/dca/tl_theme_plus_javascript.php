<?php

/**
 * Theme+ - Theme extension for the Contao Open Source CMS
 *
 * Copyright (C) 2013 bit3 UG <http://bit3.de>
 *
 * @package    Theme+
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @link       http://www.themeplus.de
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


if (TL_MODE == 'BE') {
	$GLOBALS['TL_CSS']['theme_plus_be'] = 'assets/theme-plus/css/be.css';
}

$session = \Session::getInstance();
if (\Input::get('type')) {
	$type = \Input::get('type');
}
else if ($session->get('THEME_PLUS_LAST_JS_TYPE')) {
	$type = $session->get('THEME_PLUS_LAST_JS_TYPE');
}
else {
	$type = '';
}

$this->loadLanguageFile('tl_theme_plus_filter');

/**
 * Table tl_theme_plus_javascript
 */
$GLOBALS['TL_DCA']['tl_theme_plus_javascript'] = [
	// Config
	'config'          => [
		'dataContainer'    => 'Table',
		'ptable'           => 'tl_theme',
		'enableVersioning' => true,
		'onload_callback'  => [
			['Bit3\Contao\ThemePlus\DataContainer\File', 'changeFileSource']
		],
		'sql'              => [
			'keys' => [
				'id'  => 'primary',
				'pid' => 'index'
			]
		],
	],
	// List
	'list'            => [
		'sorting'           => [
			'mode'                  => 4,
			'flag'                  => 11,
			'fields'                => ['sorting'],
			'panelLayout'           => 'filter;limit',
			'headerFields'          => ['name', 'author', 'tstamp'],
			'child_record_callback' => ['Bit3\Contao\ThemePlus\DataContainer\File', 'listFile'],
			'child_record_class'    => 'no_padding'
		],
		'global_operations' => [
			'newFile' => [
				'label'      => &$GLOBALS['TL_LANG']['tl_theme_plus_javascript']['newFile'],
				'href'       => 'act=paste&mode=create&type=file',
				'class'      => 'header_new_file',
				'attributes' => 'onclick="Backend.getScrollOffset();"'
			],
			'newUrl'  => [
				'label'      => &$GLOBALS['TL_LANG']['tl_theme_plus_javascript']['newUrl'],
				'href'       => 'act=paste&mode=create&type=url',
				'class'      => 'header_new_url',
				'attributes' => 'onclick="Backend.getScrollOffset();"'
			],
			'newCode' => [
				'label'      => &$GLOBALS['TL_LANG']['tl_theme_plus_javascript']['newCode'],
				'href'       => 'act=paste&mode=create&type=code',
				'class'      => 'header_new_code',
				'attributes' => 'onclick="Backend.getScrollOffset();"'
			],
			'all'     => [
				'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'       => 'act=select',
				'class'      => 'header_edit_all',
				'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			],
		],
		'operations'        => [
			'edit'   => [
				'label' => &$GLOBALS['TL_LANG']['tl_theme_plus_javascript']['edit'],
				'href'  => 'act=edit',
				'icon'  => 'edit.gif'
			],
			'copy'   => [
				'label' => &$GLOBALS['TL_LANG']['tl_theme_plus_javascript']['copy'],
				'href'  => 'act=paste&amp;mode=copy',
				'icon'  => 'copy.gif'
			],
			'cut'    => [
				'label'      => &$GLOBALS['TL_LANG']['tl_theme_plus_javascript']['cut'],
				'href'       => 'act=paste&amp;mode=cut',
				'icon'       => 'cut.gif',
				'attributes' => 'onclick="Backend.getScrollOffset();"'
			],
			'delete' => [
				'label'      => &$GLOBALS['TL_LANG']['tl_theme_plus_javascript']['delete'],
				'href'       => 'act=delete',
				'icon'       => 'delete.gif',
				'attributes' => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			],
			'show'   => [
				'label' => &$GLOBALS['TL_LANG']['tl_theme_plus_javascript']['show'],
				'href'  => 'act=show',
				'icon'  => 'show.gif'
			]
		]
	],
	// Palettes
	'palettes'        => [
		'__selector__' => ['type', 'filter']
	],
	// MetaPalettes
	'metapalettes'    => [
		'default' => [
			'source' => ['type']
		],
		'file'    => [
			'source'  => ['type', 'filesource'],
			'file'    => ['file'],
			'layouts' => ['layouts'],
			'filter'  => [':hide', 'cc', 'filter'],
			'assetic' => [':hide', 'asseticFilter'],
			'expert'  => [':hide', 'position', 'standalone'],
		],
		'url'     => [
			'source'  => ['type'],
			'file'    => ['url', 'fetchUrl'],
			'layouts' => ['layouts'],
			'filter'  => [':hide', 'cc', 'filter'],
			'assetic' => [':hide', 'asseticFilter'],
			'expert'  => [':hide', 'position', 'standalone'],
		],
		'code'    => [
			'source'  => ['type', 'code_snippet_title'],
			'file'    => ['code'],
			'layouts' => ['layouts'],
			'filter'  => [':hide', 'cc', 'filter'],
			'assetic' => [':hide', 'asseticFilter'],
			'expert'  => [':hide', 'position', 'standalone'],
		],
	],
	// MetaSubpalettes
	'metasubpalettes' => [
		'filter' => ['filterRule']
	],
	// Fields
	'fields'          => [
		'id'                 => [
			'sql' => "int(10) unsigned NOT NULL auto_increment"
		],
		'pid'                => [
			'foreignKey' => 'tl_style_sheet.name',
			'sql'        => "int(10) unsigned NOT NULL default '0'",
			'relation'   => ['type' => 'belongsTo', 'load' => 'lazy']
		],
		'sorting'            => [
			'sql' => "int(10) unsigned NOT NULL default '0'"
		],
		'tstamp'             => [
			'sql' => "int(10) unsigned NOT NULL default '0'"
		],
		'type'               => [
			'label'         => &$GLOBALS['TL_LANG']['tl_theme_plus_javascript']['type'],
			'default'       => $type,
			'inputType'     => 'select',
			'filter'        => true,
			'options'       => ['file', 'url', 'code'],
			'reference'     => &$GLOBALS['TL_LANG']['tl_theme_plus_javascript'],
			'eval'          => [
				'includeBlankOption' => true,
				'submitOnChange'     => true,
				'tl_class'           => 'w50'
			],
			'save_callback' => [['Bit3\Contao\ThemePlus\DataContainer\JavaScript', 'rememberType']],
			'sql'           => "varchar(32) NOT NULL default ''"
		],
		'filesource'         => [
			'label'     => &$GLOBALS['TL_LANG']['tl_theme_plus_javascript']['filesource'],
			'default'   => $GLOBALS['TL_CONFIG']['uploadPath'],
			'inputType' => 'select',
			'filter'    => true,
			'options'   => [$GLOBALS['TL_CONFIG']['uploadPath'], 'assets', 'system/modules', 'composer/vendor'],
			'eval'      => [
				'submitOnChange' => true,
				'tl_class'       => 'w50'
			],
			'sql'       => "varchar(32) NOT NULL default '{$GLOBALS['TL_CONFIG']['uploadPath']}'"
		],
		'code_snippet_title' => [
			'label'     => &$GLOBALS['TL_LANG']['tl_theme_plus_javascript']['code_snippet_title'],
			'inputType' => 'text',
			'eval'      => [
				'mandatory' => true,
				'unique'    => true,
				'maxlength' => 255,
				'tl_class'  => 'w50'
			],
			'sql'       => "varchar(255) NOT NULL default ''"
		],
		'file'               => [
			'label'     => &$GLOBALS['TL_LANG']['tl_theme_plus_javascript']['file'],
			'inputType' => 'fileTree',
			'eval'      => [
				'mandatory'  => true,
				'fieldType'  => 'radio',
				'files'      => true,
				'filesOnly'  => true,
				'extensions' => 'js'
			],
			'sql'       => "blob NULL"
		],
		'url'                => [
			'label'     => &$GLOBALS['TL_LANG']['tl_theme_plus_javascript']['url'],
			'inputType' => 'text',
			'eval'      => [
				'mandatory'      => true,
				'decodeEntities' => true,
				'tl_class'       => 'long'
			],
			'sql'       => "blob NULL"
		],
		'fetchUrl'           => [
			'label'     => &$GLOBALS['TL_LANG']['tl_theme_plus_javascript']['fetchUrl'],
			'inputType' => 'checkbox',
			'eval'      => [],
			'sql'       => "char(1) NOT NULL default ''"
		],
		'code'               => [
			'label'     => &$GLOBALS['TL_LANG']['tl_theme_plus_javascript']['code'],
			'inputType' => 'textarea',
			'eval'      => [
				'mandatory'    => true,
				'allowHtml'    => true,
				'preserveTags' => true,
				'class'        => 'monospace',
				'rte'          => version_compare(VERSION, '3.3', '<') ? 'codeMirror|javascript' : 'ace|javascript',
				'helpwizard'   => true
			],
			'sql'       => "blob NULL"
		],
		'position'           => [
			'label'     => &$GLOBALS['TL_LANG']['tl_theme_plus_javascript']['position'],
			'exclude'   => true,
			'inputType' => 'select',
			'options'   => ['head', 'body'],
			'reference' => &$GLOBALS['TL_LANG']['tl_theme_plus_javascript']['positions'],
			'eval'      => [
				'includeBlankOption' => true,
				'blankOptionLabel'   => &$GLOBALS['TL_LANG']['tl_theme_plus_javascript']['positions']['inherit']
			],
			'sql'       => "char(4) NOT NULL default ''"
		],
		'layouts'            => [
			'label'            => &$GLOBALS['TL_LANG']['tl_theme_plus_javascript']['layouts'],
			'exclude'          => true,
			'inputType'        => 'checkbox',
			'options_callback' => ['Bit3\Contao\ThemePlus\DataContainer\JavaScript', 'listLayouts'],
			'eval'             => ['multiple' => true, 'doNotSaveEmpty' => true],
			'load_callback'    => [['Bit3\Contao\ThemePlus\DataContainer\JavaScript', 'loadLayouts']],
			'save_callback'    => [['Bit3\Contao\ThemePlus\DataContainer\JavaScript', 'saveLayouts']],
		],
		'cc'                 => [
			'label'     => &$GLOBALS['TL_LANG']['tl_theme_plus_javascript']['cc'],
			'exclude'   => true,
			'inputType' => 'text',
			'eval'      => ['tl_class' => 'long'],
			'sql'       => "blob NULL"
		],
		'filter'             => [
			'label'     => &$GLOBALS['TL_LANG']['tl_theme_plus_javascript']['filter'],
			'exclude'   => true,
			'filter'    => true,
			'inputType' => 'checkbox',
			'eval'      => ['submitOnChange' => true],
			'sql'       => "char(1) NOT NULL default ''"
		],
		'filterRule'         => [
			'label'     => &$GLOBALS['TL_LANG']['tl_theme_plus_stylesheet']['filterRule'],
			'exclude'   => true,
			'inputType' => 'multiColumnWizard',
			'eval'      => [
				'columnFields' => [
					'platform'        => [
						'label'     => &$GLOBALS['TL_LANG']['tl_theme_plus_filter']['platform'],
						'exclude'   => true,
						'inputType' => 'select',
						'options'   => [
							'desktop'          => &$GLOBALS['TL_LANG']['tl_theme_plus_filter']['desktop'],
							'tablet-or-mobile' => &$GLOBALS['TL_LANG']['tl_theme_plus_filter']['tablet-or-mobile'],
							'tablet'           => &$GLOBALS['TL_LANG']['tl_theme_plus_filter']['tablet'],
							'mobile'           => &$GLOBALS['TL_LANG']['tl_theme_plus_filter']['mobile'],
						],
						'eval'      => [
							'includeBlankOption' => true,
							'style'              => 'width:180px',
						]
					],
					'system'          => [
						'label'            => &$GLOBALS['TL_LANG']['tl_theme_plus_filter']['system'],
						'exclude'          => true,
						'inputType'        => 'select',
						'options_callback' => ['Bit3\Contao\ThemePlus\DataContainer\File', 'getSystems'],
						'eval'             => [
							'style'              => 'width:158px',
							'includeBlankOption' => true
						]
					],
					'browser'         => [
						'label'            => &$GLOBALS['TL_LANG']['tl_theme_plus_filter']['browser'],
						'exclude'          => true,
						'inputType'        => 'select',
						'options_callback' => ['Bit3\Contao\ThemePlus\DataContainer\File', 'getBrowsers'],
						'eval'             => [
							'style'              => 'width:158px',
							'includeBlankOption' => true
						]
					],
					'comparator'      => [
						'label'     => &$GLOBALS['TL_LANG']['tl_theme_plus_filter']['comparator'],
						'inputType' => 'select',
						'options'   => [
							'lt'  => '<',
							'lte' => '<=',
							'gte' => '>=',
							'gt'  => '>'
						],
						'eval'      => [
							'style'              => 'width:70px',
							'includeBlankOption' => true
						]
					],
					'browser_version' => [
						'label'     => &$GLOBALS['TL_LANG']['tl_theme_plus_filter']['browser_version'],
						'inputType' => 'text',
						'eval'      => [
							'style' => 'width:70px'
						]
					],
					'invert'          => [
						'label'     => &$GLOBALS['TL_LANG']['tl_theme_plus_filter']['invert'],
						'exclude'   => true,
						'inputType' => 'checkbox',
						'eval'      => [
							'style' => 'width:60px'
						]
					]
				]
			],
			'sql'       => "blob NULL"
		],
		'standalone'             => [
			'label'     => &$GLOBALS['TL_LANG']['tl_theme_plus_javascript']['standalone'],
			'exclude'   => true,
			'filter'    => true,
			'inputType' => 'checkbox',
			'sql'       => "char(1) NOT NULL default ''"
		],
		'asseticFilter'      => [
			'label'            => &$GLOBALS['TL_LANG']['tl_theme_plus_javascript']['asseticFilter'],
			'inputType'        => 'select',
			'options_callback' => ['Bit3\Contao\ThemePlus\DataContainer\JavaScript', 'getAsseticFilterOptions'],
			'reference'        => &$GLOBALS['TL_LANG']['assetic'],
			'eval'             => ['includeBlankOption' => true],
			'sql'              => "varbinary(32) NOT NULL default ''"
		],
	]
];
