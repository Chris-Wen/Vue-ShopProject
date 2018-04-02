// Mutation 存在必须同步执行这个限制      所以采用 action，在action内部执行异步操作
import axios from 'axios'
import qs from 'qs'

axios.defaults.baseURL = 'http://community.73776.com/index.php/shop/WebShop'
axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=UTF-8'
axios.defaults.withCredentials = true;

function setCookie(cname, cvalue, exdays) {
	var d = new Date();
	d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
	var expires = "expires=" + d.toUTCString();
	document.cookie = cname + "=" + cvalue + "; " + expires;
}
function getCookie(cname) {
	var name = cname + "=";
	var ca = document.cookie.split(';');
	for (var i = 0; i < ca.length; i++) {
		var c = ca[i].trim();
		if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
	}
	return "";
}

export const handleTitle = ( {commit},payload ) => commit('handleTitle',payload)

export const login = ( {commit}, payload ) => {
    return new Promise((resolve, reject) => {    
        axios.post('/login', qs.stringify(payload) ).then( response => {
            var res = response.data
            if (res.code == 200) {
                setCookie('_ZDKJCREDENT', res.ucookie)
                setCookie('PHPSESSID', res.phpsessid)
                sessionStorage.setItem('zdkjuname', res.uname)
                sessionStorage.setItem('zdkjscore', res.score)
                localStorage.setItem('zdkjtoken', res.utoken)

                commit('login',res)
            } 
            resolve(res.code) 
        }).catch( err =>  reject(err) )
    })
} 

export const valiUserName = ({commit}, payload) => {
    return new Promise( (resolve, reject) => {
        axios.post('/checkUserName', qs.stringify(payload)).then( response=>{
            resolve(response.data.code)
        }).catch( err => reject(err) )
    })
}

export const register = ({commit}, payload) => {
    return new Promise((resolve, reject) => {
        axios.post('/register', qs.stringify(payload))
            .then ( response => resolve(response.data.code))
            .catch( err => reject(err) )
    })
}

export const getBanner = ({commit,state}, payload) => {
    return new Promise((resolve, reject) => {
        axios.get('/getIndexInfo')
            .then( response => {
                console.log(response);
                if (response.data.code==200) {
                    state.indexBanner = response.data.banner
                    state.hotSales = response.data.hotSales
                }
                resolve(response.data)
            }).catch( err => reject(err) )
    })
}