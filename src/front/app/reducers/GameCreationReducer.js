import { combineReducers } from 'redux'
import {
    OPEN_GAME_CREATION_MODAL, CLOSE_GAME_CREATION_MODAL,SET_BET_ACTION, HIDE_GAME_CREATION_ERROR_MESSAGE,
    CREATE_GAME_ACTION, CREATE_GAME_SUCCESS, CREATE_GAME_ERROR
} from '../actions/GameCreationActions'

//TODO get minBetValue
function gameCreation(state = {
    isModalOpen: false,
    betValue: 10,
    creationLoading: false,
    creationErrorMessage: undefined,
    displayCreationErrorMessage: false,
    localCubeId: '',
    guest: ''
}, action) {
    switch (action.type) {
        case HIDE_GAME_CREATION_ERROR_MESSAGE:
            return Object.assign({}, state, {
                displayCreationErrorMessage: false
            })
        case OPEN_GAME_CREATION_MODAL:
            return Object.assign({}, state, {
                isModalOpen: true
            })
        case CLOSE_GAME_CREATION_MODAL:
            return Object.assign({}, state, {
                isModalOpen: false,
                creationErrorMessage: '',
                displayCreationErrorMessage: false,
                guest: '',
                localCubeId: '',
                betValue: 10
            })
        case SET_BET_ACTION:
            return Object.assign({}, state, {
                betValue: action.bet
            })
        case CREATE_GAME_ACTION: 
            return Object.assign({}, state, {
                creationLoading: true,
                creationErrorMessage: '',
                displayCreationErrorMessage: false
            })
        case CREATE_GAME_SUCCESS:
            return Object.assign({}, state, {
                creationLoading: false,
                isModalOpen: false
            })
        case CREATE_GAME_ERROR:
            console.log(action);
            return Object.assign({}, state, {
                creationLoading: false,
                creationErrorMessage: action.error.errorMessage,
                displayCreationErrorMessage: true
            })
        default:
            return state
    }
}

export default gameCreation;