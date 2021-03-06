import React, { Component, PropTypes } from 'react'
import { connect } from 'react-redux'
import React3 from 'react-three-renderer';
import THREE, { Vector3, Fog, BoxGeometry, MeshLambertMaterial, Euler, Matrix4 } from 'three';
import Hammer from 'react-hammerjs'


import GameCubeMesh from './GameCubeMesh/GameCubeMesh'
import GameControls from './GameControls/GameControls'
import Resources from './Resources/Resources'
import ErrorFlashMessage from '../../../../core/components/FlashMessage/ErrorFlashMessage'

import { resizeScene, translateCameraOnZ, rotateCameraOnAxis, rotateAroundGameBoard,
    updateRotationImpulse, addRotationImpulse, stopGameBoardRotation, doPlayTurn, hidePlayTurnErrorMessage } from './GameSceneActions';
import { moveCursor } from './GameControls/GameControlsActions'

import { calculateWorldPosition, isPositionAvailable, getYatPosition } from '../../../../core/utils/threeTools'


export const gridSize = 4;
export const cubeSize = 0.25;
export const gridHeight = 0.1;

class GameScene extends Component {

    constructor(props, context) {
        super(props, context);
        this.onAnimate = this.onAnimate.bind(this);
        this.onSwipe = this.onSwipe.bind(this);
        this.onTap = this.onTap.bind(this);
        this.onWindowResize = this.onWindowResize.bind(this);
        this.handleMoveCursor = this.handleMoveCursor.bind(this);
        this.handlePlay = this.handlePlay.bind(this);
        this.hidePlayTurnErrorMessage = this.hidePlayTurnErrorMessage.bind(this);
    }

    componentDidMount() {
        const { dispatch } = this.props;
        dispatch(translateCameraOnZ( 6 ));
        dispatch(rotateCameraOnAxis( new Vector3( 1, 0, 0), -32 ));
        window.addEventListener( 'resize', this.onWindowResize, false );
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
        if (Math.abs(this.props.rotationImpulse) > 0) {
            dispatch(updateRotationImpulse());
            dispatch(rotateAroundGameBoard());
        }
    }

    handleMoveCursor(direction) {
        const { dispatch, game } = this.props;
        dispatch(moveCursor(direction, game.turns));
    }

    handlePlay() {
        const { dispatch, cursorPosition, game, user } = this.props;
        const userId = user.id;
        const gameId = game.id;
        let position = cursorPosition;
        position.y = getYatPosition(position.x, position.z, game.turns);
        dispatch(doPlayTurn(userId, gameId, position));
    }

    hidePlayTurnErrorMessage() {
        const { dispatch } = this.props;
        dispatch(hidePlayTurnErrorMessage());
    }

    render() {
        const { width, height, cameraMatrix, game, cursorPosition} = this.props;
        let position = new Vector3();
        let rotation = new Euler();
        let scale = new Vector3();
        cameraMatrix.decompose(position, rotation, scale);
        const canUserPlay = !game.isEnded && game.isMyTurn && isPositionAvailable(cursorPosition, game.turns);
        let worldCursorPosition = calculateWorldPosition(cursorPosition);

        return (
            <div class='game-scene'>
                <ErrorFlashMessage
                    display={this.props.displayPlayTurnErrorMessage}
                    message={this.props.playTurnError.message}
                    closeHandler={this.hidePlayTurnErrorMessage}
                />
                <Hammer onTap={this.onTap} onSwipe={this.onSwipe}>
                    <React3 mainCamera="camera" width={width} height={height} onAnimate={this.onAnimate} clearColor={0xffffff}>
                        <Resources
                            localCubeTexture={this.props.game.localCubeTexture}
                            visitorCubeTexture={this.props.game.visitorCubeTexture}
                            gameboardTexture={this.props.game.gameboardTexture}
                        />
                        <scene>
                            <perspectiveCamera position={position} rotation={rotation} name="camera" fov={75} aspect={width / height} near={0.1} far={1000} />
                            <ambientLight color={0xbbbbbb} />
                            <directionalLight castShadow color={0xffffff} intensity={1.25} lookAt={new Vector3( 0, 0, 0 )} position={new Vector3( 10, 10, 10 )}/>
                            <object3D>
                                <mesh position={new Vector3(0,0,0)} receiveShadow>
                                    <boxGeometry width={gridSize} height={gridHeight} depth={gridSize} />
                                    <materialResource resourceId={'gameboardPhongMaterial'} />
                                </mesh>
                                {game.turns.map(function(turnItem, key) {
                                    return <GameCubeMesh key={key} position={new Vector3(turnItem.x, turnItem.y, turnItem.z)} isLocalTurn={turnItem.isLocalTurn} />;
                                })}
                                <mesh position={worldCursorPosition} receiveShadow>
                                    <boxGeometry width={cubeSize} height={cubeSize} depth={cubeSize} />
                                    <materialResource resourceId={'gameboardPhongMaterial'} />
                                </mesh>
                            </object3D>
                        </scene>
                    </React3>
                </Hammer>
                <GameControls canUserPlay={canUserPlay} handleMoveCursor={this.handleMoveCursor} handlePlay={this.handlePlay} />
            </div>
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
    const cursorPosition = state.gameScene.cursorPosition;
    const playTurnError = state.gameScene.playTurnError;
    const playTurnLoading= state.gameScene.playTurnLoading;
    const displayPlayTurnErrorMessage = state.gameScene.displayPlayTurnErrorMessage;

    return {
        width,
        height,
        cameraRotationY,
        cameraMatrix,
        rotationImpulse,
        cursorPosition,
        playTurnError,
        playTurnLoading,
        displayPlayTurnErrorMessage
    }
}

export default connect(mapStateToProps)(GameScene)
