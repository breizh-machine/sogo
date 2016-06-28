'use strict'

var React = require('react');
var ReactPropTypes = React.PropTypes;

var TextInputRemoteResourcePicker = React.createClass({

    propTypes: {
        className: ReactPropTypes.string,
        id: ReactPropTypes.string,
        placeholder: ReactPropTypes.string,
        value: ReactPropTypes.string,
        changeAction: ReactPropTypes.func.isRequired,
        isLoading: ReactPropTypes.bool.isRequired
    },

    getInputStyle: function() {
        return this.props.isLoading ? {'backgroundColor': 'red'} : {};
    },

    /**
     * @return {object}
     */
    render: function() {
        return (
            <input
                className={this.props.className}
                id={this.props.id}
                placeholder={this.props.placeholder}
                onChange={this.props.changeAction}
                value={this.props.value}
                autoFocus={true}
                style={this.getInputStyle()}
            />
        );
    }
});

module.exports = TextInputRemoteResourcePicker;