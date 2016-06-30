import { combineReducers } from 'redux'
import cubes from './CubesPickerReducer'
import gameCreation from './GameCreationReducer'
import games from './GameListReducer'
import players from './PlayersPickerReducer'
import gamesPage from './GamesPageReducer'

const rootReducer = combineReducers({
    cubes,
    gameCreation,
    games,
    players,
    gamesPage
});

export default rootReducer