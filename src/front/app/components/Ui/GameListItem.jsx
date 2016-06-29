import React, { Component } from 'react'

class GameListItem extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        return  <li>
            {this.props.gameItem.bet} -> {this.props.gameItem.opponent_name}
        </li>;
    }
}

export default GameListItem;