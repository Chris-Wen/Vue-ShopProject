<template>
    <div class="container">
        <div class="top">
            <img src="../assets/images/user.jpg" alt="">
        </div>
        <cube-popup type="login-popup" ref="myPopup" :mask="false" :content="popMsg" />
        <form @submit.prevent>
            <ul>
                <li v-if="!isRegisterPartShow">
                    <cube-validator v-model="valiuname" :for="loginName" :rule="ruleLoginName" :messages="loginNameMsg">
                        <cube-input type="text" v-model="loginName" :clearable="clearable"  placeholder="请输入用户名" />
                    </cube-validator>
                </li>
                <li v-if="!isRegisterPartShow">
                    <cube-validator v-model="valiupwd" :for="loginUpwd" :rule="ruleLoginUpwd" :messages="loginUpwdMsg">
                        <cube-input type="password" v-model="loginUpwd" :clearable="clearable" :eye="eye" placeholder="请输入密码" />
                    </cube-validator>
                </li>

                <li v-if="isRegisterPartShow">
                    <cube-validator v-model="validuname" :for="uname" :rule="rule1" :messages="messages1">
                        <cube-input type="text" v-model="uname" :clearable="clearable"  placeholder="请输入用户名" @blur="checkName"/>
                    </cube-validator>
                </li>
                <li v-if="isRegisterPartShow">
                    <cube-validator v-model="validupwd" :for="upwd" :rule="rule2" :messages="messages2">
                        <cube-input type="password" v-model="upwd" :clearable="clearable" :eye="eye" placeholder="请输入密码" />
                    </cube-validator>
                </li>
                <li v-if="isRegisterPartShow">
                    <cube-validator v-model="validcpwd" :for="cpwd" :rule="rule3" :messages="messages3">
                        <cube-input type="password" v-model="cpwd" :clearable="clearable" :eye="eye" placeholder="请确认密码" />
                    </cube-validator>
                </li>
                
                <li class="code">
                    <cube-validator v-model="valicode" :for="code" :rule="rule4" :messages="messages4">
                        <cube-input type="text" :clearable="clearable" v-model="code" placeholder="验证码" />
                    </cube-validator>
                    <img class="verify" :src="verify" @click="changeVerify" alt="valicode">
                </li>
                <li>
                    <button :class="{ active: !isRegisterPartShow } " @click="handleLogin">登 录</button>
                    <button :class="{ active: isRegisterPartShow }" @click="handleRegister">注 册</button>
                </li>
            </ul>
        </form>
    </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
    name:'Login',
    data(){
        return{
            titleInfo: {
                title: '登录注册',
                showIcon: false
            },
            isRegisterPartShow: false,
            isUnameExist: false,
            loginName: '', loginUpwd: '',
            uname: '',  upwd: '',  cpwd: '',  code: '',
            valiuname: false, valiupwd: false,
            validuname: false, validupwd: false, validcpwd: false, valicode: false,
            popMsg: '',
            clearable: true,
            eye: { open: false },
            valid: false,
            verify: 'http://community.73776.com/index.php/shop/WebShop/verify',    //  验证码
            ruleLoginName: { required: true },
            ruleLoginUpwd: { required: true },
            loginNameMsg: { required: '请输入用户名' },
            loginUpwdMsg: { required: '请输入密码' },
            rule1: {
                required: true,
                pattern: /^[a-zA-Z][a-zA-Z0-9_]*$/,
                custom: val => {
                    return val.length >= 3 && val.length <= 12
                }
            },
            messages1: {
                required: '请填写用户名',
                pattern: '用户名必须以字母开头',
                custom: '用户名长度为3到12位'
            },
            rule2: {
                required: true,
                pattern: /^[a-zA-Z0-9]*$/,
                custom: val => {
                    return val.length >= 6 && val.length <=20
                }                
            },
            messages2: {
                required: '请填写密码',
                custom: '密码长度为6到20位'
            },
            rule3: {
                required: true,
                custom: val => {
                    return this.cpwd == this.upwd
                }
            },
            messages3: {
                required: '请填写确认密码',
                custom: '两次输入密码不一致'
            },
            rule4: {
                required: true
            },
            messages4: {
                required: '请输入验证码'
            }
        }
    },
    methods:{
        ...mapActions( ['handleTitle'] ),
        showPopup(refId, msg) {
            this.popMsg = msg;
            const component = this.$refs[refId];
            component.show()
            setTimeout(() => {
                component.hide()
            }, 2000)
        },
        changeVerify() {
            this.verify = this.verify + '?rand = '+ Math.random();
        },
        handleLogin() {
            if ( this.isRegisterPartShow == true ) return this.isRegisterPartShow = false
            if (!(this.valiuname && this.valiupwd)) return this.showPopup('myPopup', '请输入完整登录信息')
            if ( !this.code ) return this.showPopup('myPopup', '请输入验证码')

            var params = {
                uname: this.loginName,
                upwd:  this.loginUpwd,
                verify: this.code
            }
            this.$store.dispatch('login',params)
                    .then( res => {
                        var msg;
                        switch(res) {
                            case 200: msg = '登录成功'; setTimeout(() => { this.$router.go(-1)}, 1500);
                                break;
                            case 301:  msg = '验证码错误'
                                break;
                            case 201: msg = '用户名或密码错误'
                                break;
                        }
                        this.showPopup('myPopup', msg)
                    })
        },
        checkName() {
            if (!this.uname) return;
            this.$store.dispatch('valiUserName', {uname: this.uname}).then( res=>{
                if (res==201) {
                    this.showPopup('myPopup', "该用户名已注册")
                    this.isUnameExist = true
                } else if (res==200) {
                    this.isUnameExist = false
                }
            })
        },
        handleRegister() {  
            if ( this.isRegisterPartShow == false ) return this.isRegisterPartShow = true  
            if (!(this.validuname && this.validupwd && this.validcpwd)) return this.showPopup('myPopup', '请输入正确完整注册信息') 

            if (this.isUnameExist) return this.showPopup('myPopup', '该用户名已注册');
            if ( !this.code ) return this.showPopup('myPopup', '请输入验证码')
            var params = {
                uname: this.uname,
                upwd: this.upwd,
                verify: this.code
            }
            this.$store.dispatch('register', params)
                    .then(res=>{
                        var msg;
                        switch (res) {
                            case 200: msg = "注册成功"; setTimeout( () => location.reload(), 2000);
                                break;
                            case 500: msg = "注册失败，请稍候重试";
                                break;
                            case 301: msg = "验证码错误";
                                break;
                        }
                        this.showPopup('myPopup', msg)
                    })
        },
        
    },
    mounted(){
        this.handleTitle({
            title:  this.titleInfo.title,
            showIcon: this.titleInfo.showIcon
        })
    }
}
</script>

<style lang="scss" scoped>
.container{
    .top{
        width: 100%;
        height: 300px;
        background-color: #fff;
        line-height: 300px;
        img{  width: 150px }
    }
    form {
        width: 80%;
        margin: 0 auto;
        padding-top: 100px;
        ul li.code {
            text-align: left;
            /deep/ .cube-validator {            //深度作用选择器   /deep/为 >>> 的别名，在sass中使用
                display: inline-block;
                width: 45%;
                text-align: center;
                input { height: 80px; width: 100%; text-indent: 1em }
            }
            img { float: right; width: 240px; height: 80px; }
        }
        ul li {
            min-height: 130px;
            /deep/ .cube-input input { height: 80px; text-indent: 1em; font-size: 2em; }   
            /deep/ i { transform: scale(1.8) }
            p {
                width: 100%;
                text-align: center;
                height: 60px;
                line-height: 60px; 
            }  
            button {
                width: 100%;
                height: 80px;
                margin-top:30px;
                color: white;
                word-spacing: 2em;
                font-size: 1.08em;
                background: -webkit-gradient(linear, left top, right top, from(#979797) , to(#b2b2b2)); 
                background: linear-gradient(to right, #979797 , #b2b2b2);
            }
            .active {
                background: -webkit-gradient(linear, left top, right top, from(#ffc80c) , to(#ff6416)); 
                background: linear-gradient(to right, #ffc80c , #ff6416);
            }
        }
    }
}

</style>


