import { combineReducers } from 'redux'
import {
    JOIN_GAME_ACTION, JOIN_GAME_SUCCESS, JOIN_GAME_ERROR
} from '../actions/GamesPageAction'

function gamesPage(state = {
    joinGameLoading: false,
    joinGameError: {message: '', code: 0},
    joinGameSucceeded: false,
    displayJoinGameError: false
}, action) {
    switch (action) {
        case JOIN_GAME_ACTION:
            return Object.assign({}, state, {
                joinGameLoading: true,
                displayJoinGameError: false
            })
        case JOIN_GAME_SUCCESS:
            return Object.assign({}, state, {
                joinGameLoading: false,
                joinGameSucceeded: true
            })
        case JOIN_GAME_ERROR:
            return Object.assign({}, state, {
                joinGameLoading: false,
                joinGameError: action.error,
                displayJoinGameError: true
            })
        default:
            return state
    }
}

export default gamesPage;