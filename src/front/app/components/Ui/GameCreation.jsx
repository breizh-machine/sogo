import React, { Component, PropTypes } from 'react'
import Modal  from 'react-modal'
import { connect } from 'react-redux'
import { If, Then } from 'react-if';

import { isUuid } from '../../tools/stringTools'
import { openGameCreationModalAction, closeGameCreationModalAction, setBetAction, createGame, hideGameCreationErrorMessage } from '../../actions/GameCreationActions'
import CubesPicker from './CubesPicker';
import PlayersPicker from './PlayersPicker';
import ErrorFlashMessage from './Utils/ErrorFlashMessage'

class GameCreation extends Component {
    constructor(props) {
        super(props);
        this.openGameCreationModal = this.openGameCreationModal.bind(this);
        this.closeGameCreationModal = this.closeGameCreationModal.bind(this);
        this.onCreateGameClicked = this.onCreateGameClicked.bind(this);
        this.onBetValueChanged = this.onBetValueChanged.bind(this);
        this.hideErrorMessage = this.hideErrorMessage.bind(this);
    }

    openGameCreationModal() {
        const { dispatch } = this.props;
        dispatch(openGameCreationModalAction());
    }

    closeGameCreationModal() {
        const { dispatch } = this.props;
        dispatch(closeGameCreationModalAction());
    }

    onCreateGameClicked() {
        const { dispatch } = this.props;
        const userId = this.props.user.userId;
        const {localCubeId, betValue, guest} = this.props;
        dispatch(createGame(userId, localCubeId, betValue, guest)).then(
            data => {
                console.log('Action create finished with data ', data);
            }
        );
    }

    onBetValueChanged(e) {
        e.preventDefault();
        const { dispatch } = this.props;
        dispatch(setBetAction(e.target.value));
    }

    _validateGameCreation() {
        return isUuid(this.props.localCubeId) && this.props.betValue >= 10;
    }

    hideErrorMessage() {
        const { dispatch } = this.props;
        dispatch(hideGameCreationErrorMessage());
    }

    render() {
        return  <div className="game-creation">
            <button onClick={this.openGameCreationModal}>Create a new game</button>
            <Modal isOpen={this.props.isModalOpen}>
                <ErrorFlashMessage 
                    display={this.props.displayCreationErrorMessage}
                    message={this.props.creationErrorMessage}
                    closeHandler={this.hideErrorMessage}
                />
                <button onClick={this.closeGameCreationModal}>close</button>
                <h2>Create a new game</h2>
                <div className="cube-picker">
                    <CubesPicker user={this.props.user} />
                </div>
                <div className="player-picker">
                    <PlayersPicker user={this.props.user} />
                </div>
                <div className="game-bet">
                    <input
                        className="game-bet-input"
                        id="game-bet-input"
                        placeholder="bet"
                        onChange={this.onBetValueChanged}
                        value={this.props.betValue}
                        type="number"
                    />
                </div>
                <If condition={this._validateGameCreation()}>
                    <Then>
                        <div className="create-game">
                            <button onClick={this.onCreateGameClicked}>Create</button>
                        </div>
                    </Then>
                </If>
            </Modal>
        </div>;
    }
}

GameCreation.propTypes = {
    dispatch: PropTypes.func.isRequired,
    betValue: PropTypes.number,
    isModalOpen: PropTypes.bool,
    localCubeId: PropTypes.string,
    guest: PropTypes.string,
    creationErrorMessage: PropTypes.string,
    creationLoading: PropTypes.bool,
    displayCreationErrorMessage: PropTypes.bool
}

function mapStateToProps(state) {

    const isModalOpen = state.gameCreation.isModalOpen;
    const betValue = state.gameCreation.betValue;
    const localCubeId = state.cubes.selectedCubeId;
    const guest = state.players.selectedPlayerId;
    const creationErrorMessage = state.gameCreation.creationErrorMessage;
    const creationLoading = state.gameCreation.creationLoading;
    const displayCreationErrorMessage = state.gameCreation.displayCreationErrorMessage;

    return {
        isModalOpen,
        betValue,
        localCubeId,
        guest,
        creationErrorMessage,
        creationLoading,
        displayCreationErrorMessage
    }
}

export default connect(mapStateToProps)(GameCreation)
