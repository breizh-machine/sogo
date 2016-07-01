import React, { Component, PropTypes } from 'react'
import THREE from 'three'
import ReactTHREE from 'react-three'
const PerspectiveCamera = ReactTHREE.PerspectiveCamera;

class GameCamera extends Component {

    constructor(props) {
        super(props);
    }

    render() {
        return <PerspectiveCamera {...this.props} />;
    }
}

GameCamera.defaultProps = {
    ref: 'maincamera',
    name: 'maincamera',
    fov: '75',
    aspect: window.innerWidth/window.innerHeight,
    near: 0.1,
    far: 1000,
    rotation: new THREE.Vector3( 0, 0, 0),
    position: new THREE.Vector3( 0, 5, 0)
}

module.exports = GameCamera