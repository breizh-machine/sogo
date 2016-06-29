import React, { Component } from 'react'
import { If, Then } from 'react-if';

class PlayerPickerListItem extends Component {

    constructor(props) {
        super(props);
        this.onClicked = this.onClicked.bind(this);
    }

    render() {
        return  <li style={{'display': 'inline-block'}} className={this.props.playerItem.selected ? 'player-picker-item selected' : 'player-picker-item'} onClick={this.onClicked}>
            <img src={this.props.playerItem.profile_picture} width={'75'} height={'75'} />
            <div className={'username'}>{this.props.playerItem.username}</div>
            <If condition={ this.props.playerItem.selected === true}>
                <Then>
                    <div>selected</div>
                </Then>
            </If>
        </li>;
    }

    onClicked() {
        this.props.handlePlayerClicked(this.props.playerItem.id);
    }
};

export default PlayerPickerListItem;
