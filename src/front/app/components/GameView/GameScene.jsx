import React, { Component, PropTypes } from 'react'
import { connect } from 'react-redux'
import React3 from 'react-three-renderer';
import THREE, { Vector3, Fog, BoxGeometry, MeshLambertMaterial, Euler, Matrix4 } from 'three';
import { resizeScene, translateCameraOnZ, rotateCameraOnAxis, rotateAroundGameBoard,
    updateRotationImpulse, addRotationImpulse, stopGameBoardRotation } from '../../actions/GameView/GameSceneActions';
import Hammer from 'react-hammerjs'

import GameCubeMesh from './GameCubeMesh.jsx'

class GameScene extends React.Component {

    constructor(props, context) {
        super(props, context);
        this.onAnimate = this.onAnimate.bind(this);
        this.onSwipe = this.onSwipe.bind(this);
        this.onTap = this.onTap.bind(this);
        this.onWindowResize = this.onWindowResize.bind(this);
    }

    componentDidMount() {
        const { dispatch } = this.props;
        dispatch(translateCameraOnZ( 5 ));
        dispatch(rotateCameraOnAxis( new Vector3( 1, 0, 0), -25 ));
        window.addEventListener( 'resize', this.onWindowResize, false )
    }

    componentWillUnmount() {
        window.removeEventListener('resize', this.onWindowResize)
    }

    onWindowResize() {
        const { dispatch } = this.props;
        dispatch(resizeScene(window.innerWidth, window.innerHeight))
    }

    onSwipe(e) {
        const { dispatch } = this.props;
        dispatch(addRotationImpulse( -1 * e.deltaX ));
    }

    onTap() {
        const { dispatch } = this.props;
        dispatch(stopGameBoardRotation());
    }

    onAnimate() {
        const { dispatch } = this.props;
        dispatch(updateRotationImpulse());
        dispatch(rotateAroundGameBoard());
    }

    render() {
        const { width, height, cameraMatrix, game} = this.props;
        let position = new Vector3();
        let rotation = new Euler();
        let scale = new Vector3();
        cameraMatrix.decompose(position, rotation, scale);
        return (
            <Hammer onTap={this.onTap} onSwipe={this.onSwipe}>
                <React3 mainCamera="camera" width={width} height={height} onAnimate={this.onAnimate} >
                    <scene>
                        <perspectiveCamera position={position} rotation={rotation} name="camera" fov={75} aspect={width / height} near={0.1} far={1000} />
                        <ambientLight color={0x666666} />
                        <directionalLight color={0xffffff} intensity={1.75} lookAt={new Vector3( 0, 0, 0 )} position={new Vector3( 10, 10, 10 )}/>
                        <mesh>
                            <boxGeometry width={1} height={1} depth={1} />
                            <meshBasicMaterial color={0x00ff00} />
                        </mesh>
                        <object3D castShadow receiveShadow>
                            {game.turns.map(function(turnItem, key) {
                                return <GameCubeMesh key={key} position={new Vector3(turnItem.x, turnItem.y, turnItem.z)} />;
                            })}
                            <mesh castShadow receiveShadow position={new Vector3(0,0,0)}>
                                <boxGeometry width={4} height={0.1} depth={4} />
                                <meshBasicMaterial color={0x00ffff} />
                            </mesh>
                            <mesh castShadow receiveShadow position={new Vector3(0,0,0)}>
                                <boxGeometry width={0.1} height={0.1} depth={0.1} />
                                <meshBasicMaterial color={0x00ff00} />
                            </mesh>
                        </object3D>
                    </scene>
                </React3>
            </Hammer>
        );
    }
}

GameScene.defaultProps = {
    width: window.innerWidth,
    height: window.innerHeight,
    cameraRotationY: 0,
    cameraMatrix: new Matrix4()
}

GameScene.propTypes = {
    dispatch: PropTypes.func.isRequired,
    width: React.PropTypes.number.isRequired,
    height: React.PropTypes.number.isRequired,
    cameraRotationY: React.PropTypes.number,
    cameraMatrix: React.PropTypes.object,
    rotationImpulse: React.PropTypes.number
};

function mapStateToProps(state) {

    const width = state.gameScene.width;
    const height = state.gameScene.height;
    const cameraMatrix = state.gameScene.cameraMatrix;
    const rotationImpulse = state.gameScene.rotationImpulse;
    const cameraRotationY = state.gameScene.cameraRotationY;

    return {
        width,
        height,
        cameraRotationY,
        cameraMatrix,
        rotationImpulse
    }
}

export default connect(mapStateToProps)(GameScene)
