'use strict'

var React = require('react');
var THREE = require('three');
var ReactTHREE = require('react-three');

var Mesh = ReactTHREE.Mesh;
var Object3D = ReactTHREE.Object3D;

var geometry = new THREE.BoxGeometry( 4, 0.1, 4, 50, 50, 50 );
var topgeometry = new THREE.BoxGeometry( 0.1, 0.1, 0.1 );
var material = new THREE.MeshLambertMaterial( { color: 0x00ff00 } );
var topmaterial = new THREE.MeshLambertMaterial( { color: 0xffff00 } );

var GameBoard = React.createClass({
    displayName: 'GameBoard',
    propTypes: {
        rotation: React.PropTypes.instanceOf(THREE.Euler)
    },

    getInitialState: function() {
        return {
            children: [],
            rotation: new THREE.Euler(0, 0, 0, 'XYZ')
        }
    },

    componentDidMount: function() {
        this.yRot = 0;
        this.animate();
    },
    animate: function() {
        let prevYRot = this.yRot;
        let nextYrot = prevYRot - (prevYRot / 50);
        if (Math.abs(nextYrot) < 0.05) nextYrot = 0;
        let rotation = new THREE.Euler( 0, 0, 0 );
        rotation.y = this.state.rotation.y + THREE.Math.degToRad( nextYrot );
        this.yRot = nextYrot;
        this.setState({
            rotation: rotation
        });
        this.frameId = requestAnimationFrame(this.animate);
    },

    addChild: function(object) {
        let children = this.state.children;
        children.push(object);
        this.setState({
            children: children
        });
    },

    handleSwipe: function(e) {
        this.yRot = e.deltaX / 100;
    },

    handleTap: function() {
        this.yRot = 0;
    },

    render: function() {
        return <Object3D castShadow receiveShadow ref="ref" rotation={this.state.rotation}>
            <Mesh castShadow receiveShadow position={new THREE.Vector3(0,0,0)} geometry={geometry} material={material} />
            <Mesh castShadow receiveShadow position={new THREE.Vector3(0,0.1,0)} geometry={topgeometry} material={topmaterial} />
            {this.state.children}
        </Object3D>;
    }
});

module.exports = GameBoard