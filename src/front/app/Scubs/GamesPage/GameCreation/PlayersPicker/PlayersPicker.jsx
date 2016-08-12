import React, { Component, PropTypes } from 'react'
import { connect } from 'react-redux'

import { selectPlayer, loadPlayers } from './PlayersPickerActions'
import TextInputRemoteResourcePicker from '../../../../../core/components/TextInputRemoteResourcePicker/TextInputRemoteResourcePicker';
import PlayerPickerList from './PlayerPickerList/PlayerPickerList';

class PlayersPicker extends Component {
    constructor(props) {
        super(props);
        this.handleChange = this.handleChange.bind(this);
        this.handlePlayerClicked = this.handlePlayerClicked.bind(this);
    }

    handlePlayerClicked(playerId) {
        this.props.dispatch(selectPlayer(playerId));
    }

    handleChange(e) {
        e.preventDefault();
        const { dispatch } = this.props
        dispatch(loadPlayers(this.props.user.id, e.target.value));
    }

    render() {
        const { playersPicker } = this.props;
        return  <div className="player-picker">
            <TextInputRemoteResourcePicker
                className={'player-picker'}
                id={'player-picker'}
                placeholder={'Select an opponent'}
                value={playersPicker.playersPickerInputValue}
                changeAction={this.handleChange}
                isLoading={playersPicker.isFetching}
            />
            <div className={'picker-container'}>
                <PlayerPickerList players={playersPicker.items} handlePlayerClicked={this.handlePlayerClicked}/>
            </div>
        </div>;
    }
}

PlayersPicker.propTypes = {
    players: PropTypes.object,
    dispatch: PropTypes.func.isRequired
}

function mapStateToProps(state) {
    const playersPicker = state.playersPicker;

    return {
        playersPicker
    }
}

export default connect(mapStateToProps)(PlayersPicker)