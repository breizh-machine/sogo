'use strict'

var React = require('react');
var THREE = require('three');
var ReactTHREE = require('react-three');
var PerspectiveCamera = ReactTHREE.PerspectiveCamera;

var GameCamera = React.createClass({
    displayName: 'GameCamera',
    getInitialState: function() {
        return {
            width: window.innerWidth,
            height: window.innerHeight,
            name:'maincamera',
            fov:'75',
            aspect:window.innerWidth/window.innerHeight,
            near: 0.1,
            far:1000
        };
    },
    getThreeJsObject: function() {
        return this.refs['cameraRef'];
    },
    render: function() {
        var threeCamera = <PerspectiveCamera ref="cameraRef" {...this.state} />;
        return threeCamera;
    }
});

module.exports = GameCamera