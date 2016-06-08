'use strict'

var React = require('react');
var THREE = require('three');
var ReactTHREE = require('react-three');

var Mesh = ReactTHREE.Mesh;
var topgeometry = new THREE.BoxGeometry( 0.1, 0.1, 0.1 );
var topmaterial = new THREE.MeshLambertMaterial( { color: 0xff0000 } );

var GameControls = React.createClass({
    displayName: 'GameControls',
    getInitialState: function() {
        return {
            counter: 2
        };
    },
    handleClick: function() {
        this.gameboard.addChild(<Mesh widthSegments={0.01}
                                      heightSegments={0.01}
                                      castShadow receiveShadow position={new THREE.Vector3(Math.random(),0.1 * this.state.counter,Math.random())} geometry={topgeometry} material={topmaterial} />);
        this.setState({ counter: this.state.counter + 1 });
    },
    componentDidMount: function() {
        this.gameboard = this.props.gameboard('gameBoard');
    },
    render: function() {
        return  <div className="game-controls">
            <button type="button" className="game-control">G</button>
            <button type="button"className="game-control">H</button>
            <button type="button" className="game-control" onClick={this.handleClick}>PLAY</button>
            <button type="button" className="game-control">D</button>
            <button type="button" className="game-control">B</button>
        </div>;
    }
});

module.exports = GameControls
