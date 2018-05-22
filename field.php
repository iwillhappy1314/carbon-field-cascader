<?php
use Carbon_Fields\Carbon_Fields;
use Carbon_Field_Chained\Chained_Field;

define( 'Carbon_Field_Chained\\DIR', __DIR__ );

Carbon_Fields::extend( Chained_Field::class, function( $container ) {
	return new Chained_Field( $container['arguments']['type'], $container['arguments']['name'], $container['arguments']['label'] );
} );