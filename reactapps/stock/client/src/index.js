import React from 'react'
import ReactDOM from 'react-dom/client'
import Stock from './components'
import ErrorBoundary from './components/ErrorBoundary'
import { Provider } from 'react-redux';
import {createStore} from 'redux'
import reducer from './reducers';

const store = createStore(reducer);

const root=ReactDOM.createRoot(document.getElementById('root'));

root.render(
    <ErrorBoundary>
        <Provider store={store}>
            <Stock />
        </Provider>
    </ErrorBoundary>

);
