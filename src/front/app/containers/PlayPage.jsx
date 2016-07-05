import React, { Component, PropTypes } from 'react'
import { connect } from 'react-redux'

import GameScene from '../components/GameView/GameScene'
import { FPSStats } from 'react-stats';

class PlayPage extends Component {

    constructor(props) {
        super(props);
    }

    render() {
        const { currentGame } = this.props;
        return  <div className="play-page">
            <FPSStats />
            <GameScene game={currentGame} />
        </div>;
    }
}

export default PlayPage

