'use strict'

var React = require('react');
var THREE = require('three');
var ReactTHREE = require('react-three');
var Hammer = require('react-hammerjs');

var Renderer = ReactTHREE.Renderer;
var Scene = ReactTHREE.Scene;

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
                <Renderer background={0xffffff} width={this.state.width} height={this.state.height} pixelRatio={window.devicePixelRatio} >
                    <Scene width={this.state.width} height={this.state.height} camera="maincamera">
                        {this.gameObjects}
                    </Scene>
                </Renderer>
            </Hammer>
            <GameControls gameboard={this.getRef.bind(this)} />
        </div>

    }
}

module.exports = GameScene;