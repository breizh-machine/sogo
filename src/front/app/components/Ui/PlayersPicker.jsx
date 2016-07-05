import React, { Component, PropTypes } from 'react'
import { connect } from 'react-redux'
import { selectPlayer, loadPlayers } from '../../actions/PlayersPickerActions'
import TextInputRemoteResourcePicker from './Utils/TextInputRemoteResourcePicker';
import PlayerPickerList from './PlayerPickerList';

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
        const { players } = this.props;
        return  <div className="player-picker">
            <TextInputRemoteResourcePicker
                className={'player-picker'}
                id={'player-picker'}
                placeholder={'Select an opponent'}
                value={this.props.players.playersPickerInputValue}
                changeAction={this.handleChange}
                isLoading={this.props.players.isFetching}
            />
            <div className={'picker-container'}>
                <PlayerPickerList players={players.items} handlePlayerClicked={this.handlePlayerClicked}/>
            </div>
        </div>;
    }
}

PlayersPicker.propTypes = {
    players: PropTypes.object,
    dispatch: PropTypes.func.isRequired
}

function mapStateToProps(state) {
    const players = state.players;

    return {
        players
    }
}

export default connect(mapStateToProps)(PlayersPicker)