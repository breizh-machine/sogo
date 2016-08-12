import { combineReducers } from 'redux'
import {
    SELECT_PLAYER,
    PLAYERS_REQUEST, PLAYERS_SUCCESS, PLAYERS_FAILURE
} from './PlayersPickerActions'

function players(state = {
    isFetching: false,
    items: [],
    playersPickerInputValue: '',
    callStack: 0,
    selectedPlayerId: ''
}, action) {
    switch (action.type) {
        case PLAYERS_REQUEST:
            if (action.playersPickerInputValue) {
                return Object.assign({}, state, {
                    isFetching: true,
                    playersPickerInputValue: action.playersPickerInputValue,
                    callStack: state.callStack + 1
                })
            } else {
                return Object.assign({}, state, {
                    items: [],
                    playersPickerInputValue: action.playersPickerInputValue
                })
            }
        case PLAYERS_SUCCESS:
            return Object.assign({}, state, {
                isFetching: (state.callStack - 1) > 0,
                items: action.response,
                callStack: state.callStack - 1
            })
        case PLAYERS_FAILURE:
            //TODO Add error message
            return Object.assign({}, state, {
                isFetching: (state.callStack - 1) > 0,
                items: [],
                callStack: state.callStack - 1
            })
        case SELECT_PLAYER:
            const updatedPlayers = state.items.map(function (item) {
                if (item.id === action.selectedPlayerId) {
                    return Object.assign({}, item, {selected: true});
                }
                return Object.assign({}, item, {selected: false});
            });
            return Object.assign({}, state, {
                items: updatedPlayers,
                selectedPlayerId: action.selectedPlayerId
            });
        default:
            return state
    }
}

export default players;