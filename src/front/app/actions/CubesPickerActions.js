import { CALL_API } from '../middleware/api'

export const SELECT_CUBE = 'SELECT_CUBE'
export function selectCube(cubeId) {
    return {
        type: SELECT_CUBE,
        selectedCubeId: cubeId
    }
}

export const CUBES_FAILURE = 'CUBES_FAILURE'
export const CUBES_REQUEST= 'CUBES_REQUEST'
export const CUBES_SUCCESS= 'CUBES_SUCCESS'
export function requestCubes(q) {
    return {
        type: CUBES_REQUEST,
        cubesPickerInputValue: q
    }
}

/*
export function receiveCubes(json) {
    console.log('receiveCubes called : ', json);
    return {
        type: CUBES_SUCCESS,
        cubes: json.map(child => child)
    }
}
*/

function fetchCubes(callParams) {
    return {
        [CALL_API]: {
            types: [ CUBES_SUCCESS, CUBES_FAILURE ],
            callParams: callParams
        }
    }
}
export function loadCubes(userId, q = '')
{
    const url = Routing.generate('scubs_api.cubes_by_player', {userId: userId, _format: 'json'});
    const method = 'get';
    const params = [];
    const callParams = { url, method, params };

    return ( dispatch ) => {
        dispatch(requestCubes(q));
        return dispatch(fetchCubes(callParams));
    }
}