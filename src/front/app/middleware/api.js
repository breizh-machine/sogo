import 'isomorphic-fetch'

function callApi(url, method, params = []) {

    var options = {
        method: method,
        headers: {
            'Accept': 'application/json, application/xml, text/play, text/html, *.*',
            'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'
        }
    };
    if (method.toUpperCase() !== 'GET' && method.toUpperCase() !== 'HEAD') {
        options.body = JSON.stringify(params);
    }
    console.log(options);

    return fetch(url, options)
        .then(function(response) {
            return response.json().then(json => ({ json, response }));
        }).then(({ json, response }) => {
            if (!response.ok ||response.status === 500) {
                return Promise.reject(json)
            }
            if (response.status == 201 && response.headers.has('Location')) {
                return response.headers.get('Location');
            }
            return json;
        });
}

export const CALL_API = Symbol('Call API');

export default store => next => action => {
    const callAPI = action[CALL_API]
    if (typeof callAPI === 'undefined') {
        return next(action)
    }

    const { types } = callAPI;
    const { method, params } = callAPI.callParams;
    let { url } = callAPI.callParams;

    if (typeof url === 'function') {
        url = url(store.getState())
    }

    if (typeof url !== 'string') {
        throw new Error('Specify a string endpoint URL.')
    }
    if (!Array.isArray(types)) {
        throw new Error('Expected an array of action types.')
    }
    if (!types.every(type => typeof type === 'string')) {
        throw new Error('Expected action types to be strings.')
    }

    function actionWith(data) {
        const finalAction = Object.assign({}, action, data)
        delete finalAction[CALL_API]
        return finalAction
    }

    const [ successType, failureType ] = types
    if (types.length > 2) {
        const requestType = types[0];
        next(actionWith({ type: requestType }))
    }

    return callApi(url, method, params).then(
        response => next(actionWith({
            response,
            type: successType
        })),
        error => next(actionWith({
            type: failureType,
            error: error.message || 'Something bad happened'
        }))
    )
}