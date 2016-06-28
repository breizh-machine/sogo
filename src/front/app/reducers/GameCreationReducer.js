import { combineReducers } from 'redux'
import {
    OPEN_GAME_CREATION_MODAL, CLOSE_GAME_CREATION_MODAL
} from '../actions/GameCreationActions'

function gameCreation(state = {
    isModalOpen: false
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
        default:
            return state
    }
}

export default gameCreation;