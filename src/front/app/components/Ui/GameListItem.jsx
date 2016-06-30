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
        return 'wwww.google.com/'+gameId;
        //return Routing.generate()
    }

    render() {
        return  <li>
            <div>
                {this.props.gameItem.bet} -> {this.props.gameItem.opponent_name}
            </div>
            <If condition={this.props.gameItem.joinable}>
                <Then>
                    <button onClick={this.onJoinButtonClicked}>Join</button>
                </Then>
            </If>
            <If condition={this.props.gameItem.playable}>
                <Then>
                    <a href={this._getPlayGameRoute(this.props.gameItem.id)}>Play</a>
                </Then>
            </If>
        </li>;
    }
}

GameListItem.propTypes = {
    joinGameHandler: PropTypes.func.isRequired
}

export default GameListItem;