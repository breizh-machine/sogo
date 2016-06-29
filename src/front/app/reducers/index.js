import { combineReducers } from 'redux'
import cubes from './CubesPickerReducer'
import gameCreation from './GameCreationReducer'
import games from './GameListReducer'
import players from './PlayersPickerReducer'

const rootReducer = combineReducers({
    cubes,
    gameCreation,
    games,
    players
});

export default rootReducer