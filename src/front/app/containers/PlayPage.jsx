import React, { Component, PropTypes } from 'react'
import { connect } from 'react-redux'

import GameScene from '../components/GameView/GameScene'
import GameControls from '../components/GameView/GameControls'
import { moveCursor } from '../actions/GameView/GameControlsActions'

class PlayPage extends Component {

    constructor(props) {
        super(props);
        this.handleMoveCursor = this.handleMoveCursor.bind(this);
    }

    handleMoveCursor(direction) {
        const { dispatch } = this.props;
        console.log('Moving cursor');
        dispatch(moveCursor(direction, this.props.currentGame.turns));
    }

    handlePlay() {
        const { dispatch } = this.props;
        //dispatch(moveCursor(direction));
    }

    render() {
        return  <div className="play-page">
            <GameScene game={this.props.currentGame} cursorPosition={this.props.cursorPosition}/>
            <GameControls handleMoveCursor={this.handleMoveCursor} handlePlay={this.handlePlay} />
        </div>;
    }
}

PlayPage.propTypes = {
    dispatch: PropTypes.func.isRequired
}

function mapStateToProps(state) {
    const cursorPosition = state.gamesPage.cursorPosition;

    return {
        cursorPosition
    }
}

export default connect(mapStateToProps)(PlayPage)

