import React, { Component, PropTypes } from 'react'

export const LEFT_POSITION = 'LEFT_POSITION';
export const RIGHT_POSITION = 'RIGHT_POSITION';
export const TOP_POSITION = 'TOP_POSITION';
export const BOTTOM_POSITION = 'BOTTOM_POSITION';

class GameControls extends Component {

    constructor(props) {
        super(props);
        this.handleDirectionClicked = this.handleDirectionClicked.bind(this);
        this.handleLeftCliked = this.handleLeftCliked.bind(this);
        this.handleRightCliked = this.handleRightCliked.bind(this);
        this.handleTopCliked = this.handleTopCliked.bind(this);
        this.handleBottomCliked = this.handleBottomCliked.bind(this);
    }

    handleDirectionClicked(direction) {
        console.log('direction clicked', direction);
        this.props.handleMoveCursor(direction)
    }

    handleLeftCliked() {
        this.handleDirectionClicked(LEFT_POSITION);
    }
    handleRightCliked() {
        this.handleDirectionClicked(RIGHT_POSITION);
    }
    handleTopCliked() {
        this.handleDirectionClicked(TOP_POSITION);
    }
    handleBottomCliked() {
        this.handleDirectionClicked(BOTTOM_POSITION);
    }

    handlePlayClicked() {
        this.props.handlePlay();
    }

    render() {
        return <div className="game-controls">
            <button id="left" type="button" className="game-control" onClick={this.handleLeftCliked}>Left</button>
            <button type="button" className="game-control" onClick={this.handleTopCliked}>Top</button>
            <button type="button" className="game-control" onClick={this.handlePlayClicked}>PLAY</button>
            <button type="button" className="game-control" onClick={this.handleRightCliked}>Right</button>
            <button type="button" className="game-control" onClick={this.handleBottomCliked}>Bottom</button>
        </div>;
    }
}

export default GameControls;
