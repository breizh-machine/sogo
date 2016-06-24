'use strict'

var React = require('react');

var CubePickerListItem = require('./CubePickerListItem');

var _cubePickerItems = [];

function _reinitAll()
{
    _cubePickerItems.map(function(cubeItem){
        cubeItem.selected = false;
    });
}

function _toggleItemSelected(id) {
    console.log('id selected : ', id);
    _reinitAll();
    if (_cubePickerItems[id] === undefined) {
        _cubePickerItems[id] = {selected: true};
    } else {
        _cubePickerItems[id].selected = true;
    }
}

function _isSelected(id) {
    console.log('is ', id, ' selected : ', _cubePickerItems[id] !== undefined && _cubePickerItems[id].selected);
    return _cubePickerItems[id] !== undefined && _cubePickerItems[id].selected;
}

var CubePickerList = React.createClass({

    displayName: 'CubePickerList',

    render: function() {
        return  <ul>
            {this.props.cubes.map(function(cubeItem, key) {
                return <CubePickerListItem key={key} onCubeItemClicked={_toggleItemSelected} cubeItem={cubeItem} isSelected={_isSelected}/>;
            })}
        </ul>;
    },


});


module.exports = CubePickerList;