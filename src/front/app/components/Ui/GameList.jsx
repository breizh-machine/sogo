import React, { Component, PropTypes } from 'react'
import { connect } from 'react-redux'
import { loadGames } from '../../actions/GameListActions'
import GameListItem from './GameListItem';

class GameList extends Component {
    constructor(props) {
        super(props);
    }

    componentDidMount() {
        const { dispatch } = this.props;
        dispatch(loadGames(this.props.user.id))
    }

    render() {
        const { games, joinGameHandler } = this.props;
        return  <ul>
            {games.map(function(gameItem, key) {
                return <GameListItem key={key} gameItem={gameItem} joinGameHandler={joinGameHandler} />;
            })}
        </ul>;
    }
}

GameList.propTypes = {
    games: PropTypes.array,
    dispatch: PropTypes.func.isRequired,
    joinGameHandler: PropTypes.func.isRequired
}

function mapStateToProps(state) {
    const games = state.games.items;

    return {
        games
    }
}

export default connect(mapStateToProps)(GameList)