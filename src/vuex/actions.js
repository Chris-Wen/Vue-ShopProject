// Mutation 存在必须同步执行这个限制      所以采用 action，在action内部执行异步操作

//commit 别忘记传参问题
export const handleTitle = ( {commit},payload ) => commit('handleTitle',payload)
