import { CALL_API } from '../middleware/api'


export const GAMES_FAILURE = 'GAMES_FAILURE'
export const GAMES_REQUEST= 'GAMES_REQUEST'
export const GAMES_SUCCESS= 'GAMES_SUCCESS'
export function requestGames() {
    return {
        type: GAMES_REQUEST
    }
}

function fetchGames(callParams) {
    return {
        [CALL_API]: {
            types: [ GAMES_SUCCESS, GAMES_FAILURE ],
            callParams: callParams
        }
    }
}
export function loadGames(userId)
{
    const url = Routing.generate('scubs_api.game_all', {userId: userId, _format: 'json'});
    const method = 'get';
    const params = [];
    const callParams = { url, method, params };

    return ( dispatch ) => {
        dispatch(requestGames());
        return dispatch(fetchGames(callParams));
    }
}