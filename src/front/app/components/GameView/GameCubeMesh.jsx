import React, { Component, PropTypes } from 'react'
import THREE, { Vector3 } from 'three'
import { gridSize, cubeSize, gridHeight } from './GameScene'

class GameCubeMesh extends Component {

    constructor(props) {
        super(props);
    }

    _calculateWorldPosition(turnPosition) {
        let {x, y, z} = turnPosition;

        const initialYOffset = 3 * gridHeight;
        const unit = gridSize / 9;

        x =  2 * x * unit - 3 * unit;
        z =  -1 * (2 * z * unit - 3 * unit);
        y = ( initialYOffset + cubeSize) * 0.5 + y * (unit + gridHeight);

        return new Vector3(x, y, z);
    }

    render() {
        const position = this._calculateWorldPosition(this.props.position);
        return <mesh castShadow receiveShadow position={position}>
            <boxGeometry width={cubeSize} height={cubeSize} depth={cubeSize} />
            <meshBasicMaterial color={0xff00ff} />
        </mesh>
    }
}

module.exports = GameCubeMesh