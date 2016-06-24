'use strict'

var React = require('react');

var CubePickerListItem = React.createClass({
    displayName: 'CubePickerListItem',

    render: function() {
        return  <li className={this._getClassName()} onClick={this._onClicked}>
            <img src={this.props.cubeItem.thumbnail_url} width={'75'} height={'75'} />
            <div className={'description'}>{this.props.cubeItem.description}</div>
        </li>;
    },

    _onClicked: function() {
        this.props.onCubeItemClicked(this.props.cubeItem.id);
    },

    _getClassName: function() {
        return this.props.isSelected(this.props.cubeItem.id) ? 'cube-picker-item selected' : 'cube-picker-item';
    }
});
module.exports = CubePickerListItem
