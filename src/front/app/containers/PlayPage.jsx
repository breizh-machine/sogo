import React, { Component, PropTypes } from 'react'
import { connect } from 'react-redux'

import GameScene from '../components/GameView/GameScene'

class PlayPage extends Component {

    constructor(props) {
        super(props);
    }

    render() {
        return  <div className="play-page">
            <GameScene game={this.props.currentGame} />
        </div>;
    }
}

PlayPage.propTypes = {
    dispatch: PropTypes.func.isRequired
}

function mapStateToProps(state) {
    const joinGameLoading = state.gamesPage.joinGameLoading;

    return {
        joinGameLoading
    }
}

export default connect(mapStateToProps)(PlayPage)

