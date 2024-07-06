let state;
function useState(initialState) {
    if (typeof state === 'undefined') state = initialState;


    return [state, (newState) => (state = newState)];
}