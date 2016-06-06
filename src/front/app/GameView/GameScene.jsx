'use strict'

var React = require('react');
var THREE = require('three');
var ReactTHREE = require('react-three');

var Renderer = ReactTHREE.Renderer;
var Scene = ReactTHREE.Scene;
var THREEScene = ReactTHREE.THREEScene;

var GameBoard = require('./GameBoard');
var GameCamera = require('./GameCamera');


class GameScene extends React.Component {
    constructor(props) {
        super(props);

        this.gameboardProps = {position:new THREE.Vector3(0,0,0), quaternion:new THREE.Quaternion()};

        this.state = {
            time: 1.0,
            width: window.innerWidth,
            height: window.innerHeight,
            gameobjects: [<GameBoard {...this.gameboardProps} />]
        };

        this.state.camera = <GameCamera ref="gameCamera"/>;

        this.animate = () => {
            this.setState({
                time: this.state.time + 0.05
            });

            this.frameId = requestAnimationFrame(this.animate)
        }
    }

    componentDidMount() {
        var camera = this.refs['gameCamera'].getThreeJsObject();

        camera.rotateOnAxis(new THREE.Vector3( 1, 0, 0), THREE.Math.degToRad( -30 ));
        camera.translateZ( 5 );

        //this.refs['gameCamera'].getThreeJsObject().rotateOnAxis(new THREE.Vector3( 1, 0, 0), THREE.Math.degToRad( 30 ));
        console.log(this.refs['gameCamera'].getThreeJsObject().position);

        this.animate()
        window.addEventListener( 'resize', this.onWindowResize.bind(this), false )
        window.addEventListener( 'click', this.onClickCallback.bind(this), false)
    }

    onClickCallback() {
        this.refs['gameCamera'].getThreeJsObject().lookAt(new THREE.Vector3( 0, 0, 0));
        this.refs['gameCamera'].getThreeJsObject().rotateOnAxis(new THREE.Vector3( 1, 0, 0), THREE.Math.degToRad( 35 ));
    }

    componentWillUnmount() {
        cancelAnimationFrame(this.frameId)
        window.removeEventListener('resize', this.onWindowResize)
    }

    onWindowResize() {
        this.setState({
            width: window.innerWidth,
            height: window.innerHeight
        })
    }

    render() {
        return <Renderer background={0xffffff} width={this.state.width} height={this.state.height} pixelRatio={window.devicePixelRatio} >
            <Scene width={this.state.width} height={this.state.height} camera="maincamera">
                {this.state.gameobjects}
                {this.state.camera}
            </Scene>
        </Renderer>
    }
}

module.exports = GameScene;
