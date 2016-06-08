'use strict'

var React = require('react');
var THREE = require('three');
var ReactTHREE = require('react-three');
var Hammer = require('react-hammerjs');

var Renderer = ReactTHREE.Renderer;
var Scene = ReactTHREE.Scene;
var PointLight = ReactTHREE.PointLight;
var HemisphereLight = ReactTHREE.HemisphereLight;

var GameBoard = require('./GameBoard');
var GameCamera = require('./GameCamera');
var GameControls = require('./GameControls');


class GameScene extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            width: window.innerWidth,
            height: window.innerHeight
        };
        this.gameObjects = [];
        this.gameObjects.push(<GameBoard ref="gameBoard" />);
        this.gameObjects.push(<GameCamera ref="gameCamera"/>);
    }

    componentDidMount() {
        this.camera = this.refs['gameCamera'].getThreeJsObject();
        this.camera.rotateOnAxis(new THREE.Vector3( 1, 0, 0), THREE.Math.degToRad( -30 ));
        this.camera.translateZ( 5 );

        window.addEventListener( 'resize', this.onWindowResize.bind(this), false )
    }

    componentWillUnmount() {
        window.removeEventListener('resize', this.onWindowResize)
    }

    onWindowResize() {
        this.setState({
            width: window.innerWidth,
            height: window.innerHeight
        })
    }
    
    addObjectToScene(object) {
        this.gameObjects.push(object);
    }

    handleSwipe(e) {
        this.refs.gameBoard.handleSwipe(e);
    }

    handleTap(e) {
        this.refs.gameBoard.handleTap(e);
    }

    getRef(id) {
        console.log('Getting ref', this.refs[id]);
        return this.refs[id];
    }

    render() {
        return <div className="game-container">
            <Hammer onTap={this.handleTap.bind(this)} onSwipe={this.handleSwipe.bind(this)}>
                <Renderer toneMapping={THREE.ReinhardToneMapping} gammaInput gammaOutput physicallyCorrectLights shadowMapType={THREE.PCFSoftShadowMap} shadowMapEnabled background={0xf5f5f5} width={this.state.width} height={this.state.height} pixelRatio={window.devicePixelRatio} >
                    <Scene width={this.state.width} height={this.state.height} camera="maincamera">
                        <PointLight color={0xffffff}
                                    intensity={1.75}
                                    distance={1000}
                                    castShadow
                                    shadowCameraVisible
                                    shadowDarkness={0.5}
                                    shadowCameraLeft={-1}
                                    shadowCameraRight={1}
                                    shadowCameraTop={1}
                                    shadowCameraBottom={-1}
                                    shadowCameraFar={10}
                                    shadowCameraNear={0.1}
                                    shadowMapWidth={2048}
                                    shadowMapHeight={2048}
                                    lookAt={new THREE.Vector3( 0, 0, 0 )} position={new THREE.Vector3( 10, 10, 10 )}/>
                        <HemisphereLight color={0xffffff} intensity={0.5} />
                        {this.gameObjects}
                    </Scene>
                </Renderer>
            </Hammer>

            <GameControls gameboard={this.getRef.bind(this)} />
        </div>
    }
}

module.exports = GameScene;
