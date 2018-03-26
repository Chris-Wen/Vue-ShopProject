<template>
    <div class="container">
        <div class="top">
            <img src="../assets/images/user.jpg" alt="">
        </div>
        <form @submit.prevent>
            <ul>
                <li>
                    <cube-validator v-model="valid" :for="uname" :rule="rule1" :messages="messages1">
                        <cube-input type="text" name="uname" id="uname" v-model="uname" :clearable="clearable"  placeholder="请输入用户名" />
                    </cube-validator>
                </li>
                <li>
                    <cube-validator v-model="valid" :for="upwd" :rule="rule2" :messages="messages2">
                        <cube-input type="password" name="upwd" id="upwd" v-model="upwd" :clearable="clearable" :eye="eye" placeholder="请输入密码" />
                    </cube-validator>
                </li>
                <li v-if="isRegisterPartShow">
                    <cube-validator v-model="valid1" :for="cpwd" :rule="rule3" :messages="messages3">
                        <cube-input type="password" name="confirm" id="confirm" v-model="cpwd" :clearable="clearable" :eye="eye" placeholder="请确认密码" />
                    </cube-validator>
                </li>
                <li v-if="isRegisterPartShow" class="code">
                    <cube-validator v-model="valid1" :for="code" :rule="rule4" :messages="messages4">
                        <cube-input type="text" name="code" id="code" :clearable="clearable" v-model="code" placeholder="验证码" />
                    </cube-validator>
                    <img src="" alt="">
                </li>
                <li>
                    <button :disabled="vali" :class="{ active: !isRegisterPartShow } " @click="handleLogin">登 录</button>
                    <button :disabled="vali" :class="{ active: isRegisterPartShow }" @click="handleRegister">注 册</button>
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
            uname: '',  upwd: '',  cpwd: '',  code: '',
            methodHandleable: true,
            clearable: true,
            eye: { open: false },
            vali: false,
            valid: true,
            valid1: false,
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
        handleLogin() {
            if ( this.isRegisterPartShow == true ) {
                this.valid1 = false;
                this.isRegisterPartShow = false; 
                return ;
            }
            if ( !this.methodHandleable ) return;
            this.axios({
                type:'POST',
                url:'/login',
                data: {
                    uname: this.uname,
                    upwd: this.upwd
                }
            }).then( response => {

            })
        },
        handleRegister() {
            if ( this.isRegisterPartShow == false ) {
                this.valid1 =true;
                this.isRegisterPartShow = true;
                return ;
            }
            if ( !this.methodHandleable ) return;
            this.axios({
                type:'POST',
                url:'/register',
                data: {
                    uname: this.uname,
                    upwd: this.uwpd,
                    cpwd: this.cpwd,
                    code: this.code
                }
            }).then( response => {

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
            /deep/ .cube-validator {
                display: inline-block;
                width: 45%;
                text-align: center;
                input { height: 80px; width: 100%; text-indent: 1em }
            }
        }
        ul li {
            min-height: 130px;
            /deep/ .cube-input input { height: 80px; text-indent: 1em }   //深度作用选择器   /deep/为 >>> 的别名，在sass中使用
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


