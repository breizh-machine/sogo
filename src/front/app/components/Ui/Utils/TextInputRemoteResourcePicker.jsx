'use strict'

var React = require('react');
var ReactPropTypes = React.PropTypes;
var $ = require('jquery');

var ENTER_KEY_CODE = 13;

var TextInputRemoteResourcePicker = React.createClass({

    propTypes: {
        className: ReactPropTypes.string,
        id: ReactPropTypes.string,
        placeholder: ReactPropTypes.string,
        value: ReactPropTypes.string,
        onSave: ReactPropTypes.func.isRequired,
        changeAction: ReactPropTypes.func.isRequired,
        urlBuilder: ReactPropTypes.func.isRequired
    },

    getInitialState: function() {
        return {
            value: this.props.value || '',
            loading: false
        };
    },

    /**
     * @return {object}
     */
    render: function() /*object*/ {
        return (
            <input
                className={this.props.className}
                id={this.props.id}
                placeholder={this.props.placeholder}
                onBlur={this._save}
                onChange={this._onChange}
                onKeyDown={this._onKeyDown}
                value={this.state.value}
                autoFocus={true}
            />
        );
    },

    /**
     * Invokes the callback passed in as onSave, allowing this component to be
     * used in different ways.
     */
    _save: function() {
        this.props.onSave(this.state.value);
    },


    _updateRemoteData: function(data, value){
        console.log('value', value);
        this.setState({
            loading: false
        });
        this.props.changeAction(data);
    },

    /**
     * @param {object} event
     */
    _onChange: function(/*object*/ event) {
        var inputValue = event.target.value;
        this.setState({
            value: inputValue,
            loading: true
        });
        var updateHandler = this._updateRemoteData;
        $.ajax({
            url: this.props.urlBuilder(inputValue),
            dataType: 'json'
        }).done(function(data) {
            updateHandler(data, inputValue);
        });
    },

    /**
     * @param  {object} event
     */
    _onKeyDown: function(event) {
        if (event.keyCode === ENTER_KEY_CODE) {
            this._save();
        }
    }

});

module.exports = TextInputRemoteResourcePicker;