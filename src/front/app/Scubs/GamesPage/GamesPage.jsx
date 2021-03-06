import React, { Component, PropTypes } from 'react'
import { connect } from 'react-redux'

import { joinGame } from './GamesPageAction';
import GameCreation from './GameCreation/GameCreation';
import GameList from './GameList/GameList';

class GamesPage extends Component {
    constructor(props) {
        super(props);
        this.joinGameHandler = this.joinGameHandler.bind(this);
    }

    joinGameHandler(gameId) {
        const { dispatch } = this.props;
        dispatch(joinGame(this.props.user.id, gameId));
    }

    render() {
        const { entities, user } = this.props;
        return  <div className="games-page">
            <GameCreation entities={entities} user={user} />
            <GameList entities={entities} user={user} joinGameHandler={this.joinGameHandler}/>
        </div>;
    }
}

GamesPage.propTypes = {
    dispatch: PropTypes.func.isRequired,
    joinGameLoading: PropTypes.bool,
    joinGameError: PropTypes.object
}

function mapStateToProps(state) {
    const joinGameLoading = state.gamesPage.joinGameLoading;
    const joinGameError = state.gamesPage.joinGameError;
    const joinGameSucceeded = state.gamesPage.joinGameSucceeded;
    const displayJoinGameError = state.gamesPage.displayJoinGameError;

    return {
        joinGameLoading,
        joinGameError,
        joinGameSucceeded,
        displayJoinGameError
    }
}

export default connect(mapStateToProps)(GamesPage)

