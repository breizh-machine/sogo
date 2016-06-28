'use strict'

var React = require('react');

var CubePickerListItem = React.createClass({
    displayName: 'CubePickerListItem',

    render: function() {
        return  <li className={this.props.cubeItem.selected ? 'cube-picker-item selected' : 'cube-picker-item'} onClick={this._onClicked}>
            <img src={this.props.cubeItem.thumbnail_url} width={'75'} height={'75'} />
            <div className={'description'}>{this.props.cubeItem.description}</div>
        </li>;
    },

    _onClicked: function() {
        this.props.handleCubeClicked(this.props.cubeItem.id);
    }
});
module.exports = CubePickerListItem
