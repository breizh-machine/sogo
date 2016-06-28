import { CALL_API, Schemas } from '../middleware/api'


export const CUBES_REQUEST = 'CUBES_REQUEST'
export const CUBES_SUCCESS = 'CUBES_SUCCESS'
export const CUBES_FAILURE = 'CUBES_FAILURE'

// Fetches a single user from Github API.
// Relies on the custom API middleware defined in ../middleware/api.js.
function fetchCube(userId, fullUrl, method, params) {
    return {
        [CALL_API]: {
            types: [ CUBES_REQUEST, CUBES_SUCCESS, CUBES_FAILURE ],
            fullUrl: fullUrl,
            method: method,
            params: params,
            userId: userId
        }
    }
}

// Fetches a single user from Github API unless it is cached.
// Relies on Redux Thunk middleware.
export function loadCubes(userId, fullUrl, method, params) {
    return (dispatch, getState) => {
        return dispatch(fetchCube(userId, fullUrl, method, params));
    }
}













export function fetchCubes(url, userId, name = '', gameId = undefined) {

    return function (dispatch) {
        dispatch(requestCubes(userId, name, gameId))
        return fetch(url)
            .then(response => response.json())
            .then(json =>
                dispatch(receiveCubes(json))
            )

        // In a real world app, you also want to
        // catch any error in the network call.
        //TODO
    }
}