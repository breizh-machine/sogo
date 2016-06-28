import 'babel-polyfill'
import React from 'react'
import { render } from 'react-dom'
import { Provider } from 'react-redux'
import CubesPicker from './components/Ui/CubesPicker'
import configureStore from './stores/configureStore'

const store = configureStore();

render(
    <Provider store={store}>
        <CubesPicker user={ {userId: 'a7e8c0d3-9e54-43de-a1bb-307bd428073b'} }/>
    </Provider>,
    document.getElementById('game-lobby-container')
)

