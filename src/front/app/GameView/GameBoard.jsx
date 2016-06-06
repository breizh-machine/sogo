'use strict'

var React = require('react');
var THREE = require('three');
var ReactTHREE = require('react-three');
var Hammer = require('react-hammerjs');

var Mesh = ReactTHREE.Mesh;
var Object3D = ReactTHREE.Object3D;

var geometry = new THREE.BoxGeometry( 4, 0.1, 4 );
var topgeometry = new THREE.BoxGeometry( 0.1, 0.1, 0.1 );
var material = new THREE.MeshBasicMaterial( { color: 0x00ff00 } );
var topmaterial = new THREE.MeshBasicMaterial( { color: 0xffff00 } );

var GameBoard = React.createClass({
    displayName: 'GameBoard',
    propTypes: {
        rotation: React.PropTypes.instanceOf(THREE.Vector4)
    },
    getInitialState: function() {
        return {
            children: [],
            rotation: new THREE.Vector4( 0, 0, 0, 'XYZ' ),
            yRot: 0
        }
    },
    componentDidMount: function() {
        this.animate();
    },
    animate: function() {
        var prevYRot = this.state.yRot;
        this.state.yRot = this.state.yRot - (prevYRot / 50);
        if (Math.abs(this.state.yRot) < 0.05) this.state.yRot = 0;
        this.setState({
            yRot: this.state.yRot
            ,rotation: THREE.Vector4( 0, THREE.Math.degToRad(this.state.yRot), 0, 'XYZ' )
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

    getThreeJsObject: function() {
        return this.refs['ref'];
    },

    handleSwipe: function(e) {
        this.setState({ yRot:  e.deltaX / 100 });
    },

    handleTap: function() {
        this.setState({ yRot:  0 });
    },

    render: function() {

        return <Object3D ref="ref" quaternion={this.state.rotation}>
            <Mesh position={new THREE.Vector3(0,0,0)} geometry={geometry} material={material} />
            <Mesh position={new THREE.Vector3(0,0.1,0)} geometry={topgeometry} material={topmaterial} />
            {this.state.children}
        </Object3D>;
    }
});

module.exports = GameBoard