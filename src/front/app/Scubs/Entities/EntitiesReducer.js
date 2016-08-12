import { combineReducers } from 'redux'
import {
    GAME_CREATED_ACTION, INIT_ENTITIES
} from './EntitiesActions'
import { addOrUpdateEntity, convertEntitiesArrayToObject } from '../../../core/utils/arrayTools';

function entities(state = {
    games: [],
    cubes: []
}, action) {
    switch (action.type) {
        case GAME_CREATED_ACTION:
            let newGames = addOrUpdateEntity(state.games, action.newGame);
            return Object.assign({}, state, {
                games: newGames
            })
        case INIT_ENTITIES:
            let convertedGames = convertEntitiesArrayToObject(action.entities.games);
            let convertedCubes = convertEntitiesArrayToObject(action.entities.cubes);
            return Object.assign({}, state, {
                games: convertedGames,
                cubes: convertedCubes
            })
        default:
            return state
    }
}

export default entities;