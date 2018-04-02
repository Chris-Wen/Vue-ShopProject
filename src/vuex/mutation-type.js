// ALL MUTATIONS
export const handleTitle = (state,payload) => {
    state.titleGroup.title  = payload.title;
    state.titleGroup.showIcon = payload.showIcon;
    state.titleGroup.icon   = payload.icon;
    state.titleGroup.link   = payload.link;
}

export const login = (state, payload) => {
    state.userInfo.uname = payload.uname
    state.userInfo.score = payload.score
    state.userInfo.token = payload.utoken
}
