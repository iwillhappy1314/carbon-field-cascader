/**
 * The external dependencies.
 */
import React from 'react';
import PropTypes from 'prop-types';
import {compose, withHandlers, setStatic, branch} from 'recompose';

import Cascader from 'antd/lib/cascader';
require('antd/lib/cascader/style/index.less');


/**
 * The internal dependencies.
 */
import Field from 'fields/components/field';
import withStore from 'fields/decorators/with-store';
import withSetup from 'fields/decorators/with-setup';

/**
 * Render a number input field.
 *
 * @param  {Object}        props
 * @param  {String}        props.name
 * @param  {Object}        props.field
 * @param  {Function}      props.handleChange
 * @return {React.Element}
 */
export const Chained_Field = ({
	name,
	field,
	handleChange
}) => {

	console.log(field.value);

	return <Field field={field}>
        <Cascader options={field.options} onChange={handleChange} showSearch={true} defaultValue={['zhejiang', 'hangzhou', 'xihu']}>
            <input
				type="text"
                name={name}
                value={field.value.value}
                autoComplete='off'
                className="regular-text"
            />
        </Cascader>

        <input
            type="hidden"
            name={`${name}[country]`}
            value={field.value[0]}
            disabled={!field.ui.is_visible}
            readOnly />

        <input
            type="hidden"
            name={`${name}[state]`}
            value={field.value[1]}
            disabled={!field.ui.is_visible}
            readOnly />

        <input
            type="hidden"
            name={`${name}[city]`}
            value={field.value[2]}
            disabled={!field.ui.is_visible}
            readOnly />
	</Field>;
};

/**
 * 验证属性
 *
 * @type {Object}
 */
Chained_Field.propTypes = {
	name: PropTypes.string,
	field: PropTypes.shape({
		id: PropTypes.string,
		value: PropTypes.string,
        options: PropTypes.object,
	}),
	handleChange: PropTypes.func,
};


/**
 * The enhancer.
 *
 * @type {Function}
 */
export const enhance = compose(

	/**
	 * Connect to the Redux store.
	 */
	withStore(),


    /**
     * Render "No-Options" component when the field doesn't have options.
     */
    branch(
        /**
         * Test to see if the "No-Options" should be rendered.
         */
        ({ field: { options } }) => options && options.length,

        /**
         * Render the actual field.
         */
        compose(
            /**
             * Attach the setup hooks.
             */
            withSetup(),

            /**
             * Pass some handlers to the component.
             */
            withHandlers({
                handleChange: ({ field, setFieldValue }) => value => {
                    setFieldValue(field.id, value, 'assign');
				}
            }),
        ),

    )
);

export default setStatic('type', [
	'chained',
])(enhance(Chained_Field));

