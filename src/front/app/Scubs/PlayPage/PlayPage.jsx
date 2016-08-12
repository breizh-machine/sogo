import React, { Component, PropTypes } from 'react'
import { connect } from 'react-redux'
import { FPSStats } from 'react-stats';

import GameScene from './GameScene/GameScene'

class PlayPage extends Component {

    constructor(props) {
        super(props);
    }

    render() {
        const { currentGame, user } = this.props;
        return  <div className="play-page">
            <FPSStats />
            <GameScene game={currentGame} user={user}/>k
        </div>;
    }
}

export default PlayPage

