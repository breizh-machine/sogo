'use strict'

var React = require('react');
var CubePickerListItem = require('./CubePickerListItem');

var CubePickerList = React.createClass({

    displayName: 'CubePickerList',

    render: function() {
        const clickHandler = this.props.handleCubeClicked;
        return  <ul>
            {this.props.cubes.map(function(cubeItem, key) {
                return <CubePickerListItem key={key} handleCubeClicked={clickHandler} cubeItem={cubeItem} />;
            })}
        </ul>;
    }
});


module.exports = CubePickerList;