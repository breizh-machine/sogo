'use strict'

import React, { Component, PropTypes } from 'react';

class TextInputRemoteResourcePicker extends Component {

    getInputStyle() {
        return this.props.isLoading ? {'backgroundColor': 'red'} : {};
    }

    /**
     * @return {object}
     */
    render() {
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
        )
    }
}

TextInputRemoteResourcePicker.propTypes = {
    className: PropTypes.string,
    id: PropTypes.string,
    placeholder: PropTypes.string,
    value: PropTypes.string,
    changeAction: PropTypes.func.isRequired,
    isLoading: PropTypes.bool.isRequired
}

export default TextInputRemoteResourcePicker;