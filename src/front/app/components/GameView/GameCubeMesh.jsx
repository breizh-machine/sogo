import React, { Component, PropTypes } from 'react'
import THREE, { Vector3 } from 'three'

import { calculateWorldPosition } from '../../tools/threeTools'
import { gridSize, cubeSize, gridHeight } from './GameScene'

class GameCubeMesh extends Component {

    constructor(props) {
        super(props);
    }

    render() {
        const position = calculateWorldPosition(this.props.position);
        const _material = this.props.isLocalTurn ? 'localCubePhongMaterial' : 'visitorCubePhongMaterial';

        return <mesh position={position} castShadow>
            <boxGeometry width={cubeSize} height={cubeSize} depth={cubeSize} />
            <materialResource resourceId={_material} />
        </mesh>
    }
}

export default GameCubeMesh