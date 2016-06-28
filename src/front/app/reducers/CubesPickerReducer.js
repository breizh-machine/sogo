import { combineReducers } from 'redux'
import {
    SELECT_CUBE,
    CUBES_REQUEST, CUBES_SUCCESS
} from '../actions/CubesPickerActions'

function cubes(state = {
    isFetching: false,
    items: [],
    cubesPickerInputValue: '',
    callStack: 0,
    selectedCubeId: ''
}, action) {
    switch (action.type) {
        case CUBES_REQUEST:
            return Object.assign({}, state, {
                isFetching: true,
                cubesPickerInputValue: action.cubesPickerInputValue,
                callStack: state.callStack + 1
            })
        case CUBES_SUCCESS:
            return Object.assign({}, state, {
                isFetching: (state.callStack - 1) > 0,
                items: action.response,
                callStack: state.callStack - 1
            })
        case SELECT_CUBE:
            const updatedCubes = state.items.map(function (item) {
                if (item.id === action.selectedCubeId) {
                    return Object.assign({}, item, {selected: true});
                }
                return Object.assign({}, item, {selected: false});
            });
            return Object.assign({}, state, {
                items: updatedCubes,
                selectedCubeId: action.selectedCubeId
            });
        default:
            return state
    }
}

export default cubes;