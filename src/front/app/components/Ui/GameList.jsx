import React, { Component, PropTypes } from 'react'
import GameListItem from './GameListItem';

class GameList extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        const { entities, joinGameHandler } = this.props;
        return  <ul>
            {entities.games.map(function(gameItem, key) {
                return <GameListItem key={key} gameItem={gameItem} joinGameHandler={joinGameHandler} />;
            })}
        </ul>;
    }
}

GameList.propTypes = {
    joinGameHandler: PropTypes.func.isRequired
}

export default GameList