import React, { Component, PropTypes } from 'react'
import THREE, { Vector3 } from 'three'

const gridSize = 4;
const cubeSize = 0.1;

class GameCubeMesh extends Component {

    constructor(props) {
        super(props);
    }

    _calculateWorldPosition(turnPosition) {
        let {x, y, z} = turnPosition;
        x -= gridSize / 2 + cubeSize;
        z -= gridSize / 2 + cubeSize;
        y += cubeSize;
        return new Vector3(x, y, z);
    }

    render() {
        const position = this._calculateWorldPosition(this.props.position);
        return <mesh castShadow receiveShadow position={position}>
            <boxGeometry width={0.1} height={0.1} depth={0.1} />
            <meshBasicMaterial color={0x00ffff} />
        </mesh>
    }
}

module.exports = GameCubeMesh