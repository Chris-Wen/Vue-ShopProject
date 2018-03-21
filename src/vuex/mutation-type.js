// ALL MUTATIONS
export const handleTitle = (state,payload) => {
    console.log(payload)
    state.titleGroup.title  = payload.title;
    state.titleGroup.showIcon = payload.showIcon;
    state.titleGroup.icon   = payload.icon;
    state.titleGroup.link   = payload.link;
}