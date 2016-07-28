import React, { Component, PropTypes } from 'react'
import { If, Then } from 'react-if';

class GameListItem extends Component {
    constructor(props) {
        super(props);
        this.onJoinButtonClicked = this.onJoinButtonClicked.bind(this);
    }

    onJoinButtonClicked() {
        this.props.joinGameHandler(this.props.gameItem.id);
    }

    _getPlayGameRoute(gameId) {
        //TODO
        return '/scubs/games/' + gameId + '/play'
        /*
        return Routing.generate('playPage', {
            gameId: gameId
        });
        */
    }

    render() {
        const { gameItem } = this.props.gameItem;
        return  <li>
            <div>
                {gameItem.bet} -> {gameItem.opponentName}
            </div>
            <If condition={gameItem.joinable}>
                <Then>
                    <button onClick={this.onJoinButtonClicked}>Join</button>
                </Then>
            </If>
            <If condition={gameItem.playable}>
                <Then>
                    <a href={this._getPlayGameRoute(gameItem.id)}>Play</a>
                </Then>
            </If>
        </li>;
    }
}

GameListItem.propTypes = {
    joinGameHandler: PropTypes.func.isRequired
}

export default GameListItem;