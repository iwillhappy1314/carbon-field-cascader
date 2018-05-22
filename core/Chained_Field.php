<?php

namespace Carbon_Field_Chained;

use Carbon_Fields\Field\Predefined_Options_Field;
use Carbon_Fields\Helper\Delimiter;
use Carbon_Fields\Helper\Helper;
use Carbon_Fields\Value_Set\Value_Set;

class Chained_Field extends Predefined_Options_Field
{

	protected $options = [];


	/**
	 * {@inheritDoc}
	 */
	protected $default_value = [
		'country' => '',
		'state'   => '',
		'city'    => '',
	];


	/**
	 * Value delimiter
	 *
	 * @var string
	 */
	protected $value_delimiter = '|';


	/**
	 * Create a field from a certain type with the specified label.
	 *
	 * @param string $type  Field type
	 * @param string $name  Field name
	 * @param string $label Field label
	 */
	public function __construct( $type, $name, $label )
	{
		$this->set_value_set( new Value_Set( Value_Set::TYPE_MULTIPLE_PROPERTIES, [ 'country' => '', 'state' => '', 'city' => '' ] ) );
		parent::__construct( $type, $name, $label );
	}


	/**
	 * Prepare the field type for use
	 * Called once per field type when activated
	 */
	public static function field_type_activated()
	{
		$dir    = \Carbon_Field_Chained\DIR . '/languages/';
		$locale = get_locale();
		$path   = $dir . $locale . '.mo';
		load_textdomain( 'carbon-field-chained', $path );
	}

	/**
	 * Enqueue scripts and styles in admin
	 * Called once per field type
	 */
	public static function admin_enqueue_scripts()
	{
		$root_uri = \Carbon_Fields\Carbon_Fields::directory_to_url( \Carbon_Field_Chained\DIR );

		# Enqueue JS
		wp_enqueue_script( 'carbon-field-chained', $root_uri . '/assets/js/bundle.js', [ 'carbon-fields-boot' ] );

		# Enqueue CSS
		wp_enqueue_style( 'carbon-field-chained', $root_uri . '/assets/css/field.css' );
	}


	/**
	 * {@inheritDoc}
	 */
	public function set_value_from_input1( $input )
	{

		if ( ! isset( $input[ $this->get_name() ] ) ) {
			return $this->set_value( [] );
		}

		$options_values = $input[ $this->get_name() ];

		return $this->set_value( $options_values );
	}


	public function set_value_from_input( $input )
	{
		if ( ! isset( $input[ $this->get_name() ] ) ) {
			$this->set_value( null );

			return $this;
		}

		$value_set = [
			'country' => '',
			'state'   => '',
			'city'    => '',
		];

		foreach ( $value_set as $key => $v ) {
			if ( isset( $input[ $this->get_name() ][ $key ] ) ) {
				$value_set[ $key ] = $input[ $this->get_name() ][ $key ];
			}
		}

		// $value_set[ 'country' ] = (int) $value_set[ 'country' ];
		// $value_set[ 'state' ]   = (int) $value_set[ 'state' ];
		// $value_set[ 'city' ]    = (int) $value_set[ 'city' ];
		$value_set[ Value_Set::VALUE_PROPERTY ] = $value_set[ 'country' ] . ',' . $value_set[ 'state' ] . ',' . $value_set[ 'city' ];

		$this->set_value( $value_set );

		return $this;
	}


	/**
	 * 加载选项
	 *
	 * @return array
	 */
	function load_options()
	{
		return $this->option_collections[ 0 ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function to_json( $load )
	{
		$field_data = parent::to_json( $load );

		$options   = $this->load_options();
		$value_set = $this->get_value();

		$field_data = array_merge( $field_data, [
			'value'   => [
				'country' => $value_set[ 'country' ],
				'state'   => $value_set[ 'state' ],
				'city'    => $value_set[ 'city' ],
				'value'   => $value_set[ Value_Set::VALUE_PROPERTY ],
			],
			'options' => $options,
		] );

		return $field_data;
	}


	/**
	 * {@inheritDoc}
	 */
	public function get_formatted_value()
	{
		$options_values = $this->get_options_values();
		if ( empty( $options_values ) ) {
			$options_values[] = '';
		}

		$value = $this->get_value();

		return $value;
	}

}
