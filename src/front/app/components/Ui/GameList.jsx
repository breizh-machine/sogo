import React, { Component, PropTypes } from 'react'
import GameListItem from './GameListItem';

class GameList extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        const { entities, joinGameHandler } = this.props;
        return  <ul>
            {Object.keys(entities.games).map(function(keyValue, key) {
                return <GameListItem key={key} gameItem={entities.games[keyValue]} joinGameHandler={joinGameHandler} />;
            })}
        </ul>;
    }
}

GameList.propTypes = {
    joinGameHandler: PropTypes.func.isRequired
}

export default GameList