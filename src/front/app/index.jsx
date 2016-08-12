import 'babel-polyfill'
import React from 'react'
import { render } from 'react-dom'
import { Provider } from 'react-redux'

import Scubs from './Scubs/Scubs'
import { GAMES_PAGE_STATE, PLAY_PAGE_STATE } from './Scubs/states';
import configureStore from './Scubs/rootStore'

const store = configureStore();
window.store = store;
window.Provider = Provider;
window.Scubs = Scubs;
window.render = render;
window.React = React;
