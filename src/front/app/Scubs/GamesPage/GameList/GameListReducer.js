import { combineReducers } from 'redux'
import {
    GAMES_REQUEST, GAMES_SUCCESS
} from './GameListActions'

function games(state = {
    isFetching: false,
    items: []
}, action) {
    switch (action.type) {
        case GAMES_REQUEST:
            return Object.assign({}, state, {
                isFetching: true
            })
        case GAMES_SUCCESS:
            return Object.assign({}, state, {
                isFetching: false,
                items: action.response
            })
        default:
            return state
    }
}

export default games;