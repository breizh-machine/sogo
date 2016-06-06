'use strict'

var React = require('react');
var THREE = require('three');
var ReactTHREE = require('react-three');

var Mesh = ReactTHREE.Mesh;
var Object3D = ReactTHREE.Object3D;

var geometry = new THREE.BoxGeometry( 4, 0.1, 4 );
var topgeometry = new THREE.BoxGeometry( 0.1, 0.1, 0.1 );
var material = new THREE.MeshBasicMaterial( { color: 0x00ff00 } );
var topmaterial = new THREE.MeshBasicMaterial( { color: 0xffff00 } );

var GameBoard = React.createClass({
    displayName: 'GameBoard',
    propTypes: {
        position: React.PropTypes.instanceOf(THREE.Vector3),
        quaternion: React.PropTypes.instanceOf(THREE.Quaternion).isRequired
    },
    render: function() {
        return  <Object3D quaternion={this.props.quaternion}>
            <Mesh position={new THREE.Vector3(0,0,0)} geometry={geometry} material={material} />
            <Mesh position={new THREE.Vector3(0,0.1,0)} geometry={topgeometry} material={topmaterial} />
            
        </Object3D>;
    }
});

module.exports = GameBoard