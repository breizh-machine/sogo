'use strict'

import React, { Component } from 'react'
import PlayerPickerListItem from './PlayerPickerListItem'

class PlayerPickerList extends Component {

    constructor(props) {
        super(props);
    }

    render() {
        const clickHandler = this.props.handlePlayerClicked;
        return  <ul>
            {this.props.players.map(function(playerItem, key) {
                return <PlayerPickerListItem key={key} handlePlayerClicked={clickHandler} playerItem={playerItem} />;
            })}
        </ul>;
    }
}

export default PlayerPickerList;