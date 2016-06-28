import { combineReducers } from 'redux'
import cubes from './CubesPickerReducer'

const rootReducer = combineReducers({
    cubes
});

export default rootReducer