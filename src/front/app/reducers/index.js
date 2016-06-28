import { combineReducers } from 'redux'
import cubes from './CubesPickerReducer'
import gameCreation from './GameCreationReducer'

const rootReducer = combineReducers({
    cubes,
    gameCreation
});

export default rootReducer