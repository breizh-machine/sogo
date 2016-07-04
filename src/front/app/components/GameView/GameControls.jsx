import React, { Component, PropTypes } from 'react'

export const LEFT_POSITION = 'LEFT_POSITION';
export const RIGHT_POSITION = 'RIGHT_POSITION';
export const TOP_POSITION = 'TOP_POSITION';
export const BOTTOM_POSITION = 'BOTTOM_POSITION';

class GameControls extends Component {

    constructor(props) {
        super(props);
        this.handleDirectionClicked = this.handleDirectionClicked.bind(this);
    }

    handleDirectionClicked(direction) {
        this.props.handleMoveCursor(direction)
    };

    render() {
        return <div>
            <button type="button" className="game-control" onClick={this.handleDirectionClicked(LEFT_POSITION)}>Left</button>
            <button type="button" className="game-control" onClick={this.handleDirectionClicked(TOP_POSITION)}>Top</button>
            <button type="button" className="game-control" onClick={this.handlePlayClicked}>PLAY</button>
            <button type="button" className="game-control" onClick={this.handleDirectionClicked(RIGHT_POSITION)}>Right</button>
            <button type="button" className="game-control" onClick={this.handleDirectionClicked(BOTTOM_POSITION)}>Bottom</button>
        </div>;
    }
}

module.exports = GameControls
