import { combineReducers } from 'redux'
import {
    OPEN_GAME_CREATION_MODAL, CLOSE_GAME_CREATION_MODAL,SET_BET_ACTION
} from '../actions/GameCreationActions'

//TODO get minBetValue
function gameCreation(state = {
    isModalOpen: false,
    betValue: 10
}, action) {
    switch (action.type) {
        case OPEN_GAME_CREATION_MODAL:
            return Object.assign({}, state, {
                isModalOpen: true
            })
        case CLOSE_GAME_CREATION_MODAL:
            return Object.assign({}, state, {
                isModalOpen: false
            })
        case SET_BET_ACTION:
            return Object.assign({}, state, {
                betValue: action.bet
            })
        default:
            return state
    }
}

export default gameCreation;