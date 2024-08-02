import{_ as P}from"./LogoDark.vue_vue_type_script_setup_true_lang-B-S4CMck.js";import{v as C,H as i,n as F,o as m,q as E,b as l,a as e,w as a,e as o,V as p,f as d,aP as u,P as c,d as V,c as v,y as x,aQ as M,l as b,aR as Z,aT as T,F as A,a2 as w,as as k,am as U}from"./main-CEyIHaeF.js";import{_ as j}from"./AuthFooter.vue_vue_type_script_setup_true_lang-BZdD4UbD.js";const I={class:"d-flex justify-space-between align-center"},S=l("h3",{class:"text-h3 text-center mb-0"},"Sign up",-1),z={class:"mb-6"},D={class:"mb-6"},O={class:"mb-6"},J={class:"mb-6"},Q={class:"mb-6"},G={class:"d-sm-inline-flex align-center mt-2 mb-7 mb-sm-0 font-weight-bold"},K={class:"text-caption"},W=C({__name:"AuthRegister",setup(H){const n=i(!1),f=i(""),_=i(""),h=i(),y=i(""),g=i(""),R=i([s=>!!s||"Password is required",s=>s&&s.length<=10||"Password must be less than 10 characters"]),q=i([s=>!!s||"First Name is required"]),B=i([s=>!!s||"Last Name is required"]),$=i([s=>!!s||"E-mail is required",s=>/.+@.+\..+/.test(s)||"E-mail must be valid"]);function N(){h.value.validate()}return(s,t)=>{const L=F("router-link");return m(),E(A,null,[l("div",I,[S,e(L,{to:"/auth/login",class:"text-primary text-decoration-none"},{default:a(()=>[o("Already have an account?")]),_:1})]),e(T,{ref_key:"Regform",ref:h,"lazy-validation":"",action:"/dashboards/analytical",class:"mt-7 loginForm"},{default:a(()=>[e(p,{class:"my-0"},{default:a(()=>[e(d,{cols:"12",sm:"6",class:"py-0"},{default:a(()=>[l("div",z,[e(u,null,{default:a(()=>[o("First Name*")]),_:1}),e(c,{modelValue:y.value,"onUpdate:modelValue":t[0]||(t[0]=r=>y.value=r),rules:q.value,"hide-details":"auto",required:"",variant:"outlined",class:"mt-2",color:"primary",placeholder:"John"},null,8,["modelValue","rules"])])]),_:1}),e(d,{cols:"12",sm:"6",class:"py-0"},{default:a(()=>[l("div",D,[e(u,null,{default:a(()=>[o("Last Name*")]),_:1}),e(c,{modelValue:g.value,"onUpdate:modelValue":t[1]||(t[1]=r=>g.value=r),rules:B.value,"hide-details":"auto",required:"",variant:"outlined",class:"mt-2",color:"primary",placeholder:"Doe"},null,8,["modelValue","rules"])])]),_:1})]),_:1}),l("div",O,[e(u,null,{default:a(()=>[o("Company")]),_:1}),e(c,{"hide-details":"auto",variant:"outlined",class:"mt-2",color:"primary",placeholder:"Demo Inc."})]),l("div",J,[e(u,null,{default:a(()=>[o("Email Address*")]),_:1}),e(c,{modelValue:_.value,"onUpdate:modelValue":t[2]||(t[2]=r=>_.value=r),rules:$.value,placeholder:"demo@company.com",class:"mt-2",required:"","hide-details":"auto",variant:"outlined",color:"primary"},null,8,["modelValue","rules"])]),l("div",Q,[e(u,null,{default:a(()=>[o("Password")]),_:1}),e(c,{modelValue:f.value,"onUpdate:modelValue":t[5]||(t[5]=r=>f.value=r),rules:R.value,placeholder:"*****",required:"",variant:"outlined",color:"primary","hide-details":"auto",type:n.value?"text":"password",class:"pwdInput mt-2"},{append:a(()=>[e(V,{color:"secondary",icon:"",rounded:"",variant:"text"},{default:a(()=>[n.value==!1?(m(),v(x(M),{key:0,style:{color:"rgb(var(--v-theme-secondary))"},onClick:t[3]||(t[3]=r=>n.value=!n.value)})):b("",!0),n.value==!0?(m(),v(x(Z),{key:1,style:{color:"rgb(var(--v-theme-secondary))"},onClick:t[4]||(t[4]=r=>n.value=!n.value)})):b("",!0)]),_:1})]),_:1},8,["modelValue","rules","type"])]),l("div",G,[l("h6",K,[o(" By Signing up, you agree to our "),e(L,{to:"/auth/register",class:"text-primary link-hover font-weight-medium"},{default:a(()=>[o("Terms of Service ")]),_:1}),o(" and "),e(L,{to:"/auth/register",class:"text-primary link-hover font-weight-medium"},{default:a(()=>[o("Privacy Policy")]),_:1})])]),e(V,{color:"primary",block:"",class:"mt-4",variant:"flat",size:"large",onClick:t[6]||(t[6]=r=>N())},{default:a(()=>[o("Create Account")]),_:1})]),_:1},512)],64)}}}),X=l("div",{class:"blur-logo"},[l("svg",{width:"100%",height:"calc(100vh - 175px)",viewBox:"0 0 405 809",fill:"none",xmlns:"http://www.w3.org/2000/svg"},[l("path",{d:"M-358.39 358.707L-293.914 294.23L-293.846 294.163H-172.545L-220.81 342.428L-233.272 354.889L-282.697 404.314L-276.575 410.453L0.316589 687.328L283.33 404.314L233.888 354.889L230.407 351.391L173.178 294.163H294.48L294.547 294.23L345.082 344.765L404.631 404.314L0.316589 808.629L-403.998 404.314L-358.39 358.707ZM0.316589 0L233.938 233.622H112.637L0.316589 121.301L-112.004 233.622H-233.305L0.316589 0Z",fill:"#69b1ff"}),l("path",{d:"M-516.39 358.707L-451.914 294.23L-451.846 294.163H-330.545L-378.81 342.428L-391.272 354.889L-440.697 404.314L-434.575 410.453L-157.683 687.328L125.33 404.314L75.8879 354.889L72.4068 351.391L15.1785 294.163H136.48L136.547 294.23L187.082 344.765L246.631 404.314L-157.683 808.629L-561.998 404.314L-516.39 358.707ZM-157.683 0L75.9383 233.622H-45.3627L-157.683 121.301L-270.004 233.622H-391.305L-157.683 0Z",fill:"#95de64",opacity:"0.6"}),l("path",{d:"M-647.386 358.707L-582.91 294.23L-582.842 294.163H-461.541L-509.806 342.428L-522.268 354.889L-571.693 404.314L-565.571 410.453L-288.68 687.328L-5.66624 404.314L-55.1082 354.889L-58.5893 351.391L-115.818 294.163H5.48342L5.5507 294.23L56.0858 344.765L115.635 404.314L-288.68 808.629L-692.994 404.314L-647.386 358.707ZM-288.68 0L-55.0578 233.622H-176.359L-288.68 121.301L-401 233.622H-522.301L-288.68 0Z",fill:"#fff1f0",opacity:"1"})])],-1),Y={class:"pt-6 pl-6"},ee={class:"d-flex align-center justify-center",style:{"min-height":"calc(100vh - 148px)"}},se=C({__name:"RegisterPage",setup(H){return(n,f)=>(m(),v(p,{class:"bg-containerBg position-relative","no-gutters":""},{default:a(()=>[X,e(d,{cols:"12"},{default:a(()=>[l("div",Y,[e(P)])]),_:1}),e(d,{cols:"12",lg:"12",class:"d-flex align-center"},{default:a(()=>[e(w,null,{default:a(()=>[l("div",ee,[e(p,{justify:"center"},{default:a(()=>[e(d,{cols:"12",md:"12"},{default:a(()=>[e(k,{elevation:"0",class:"loginBox"},{default:a(()=>[e(k,{elevation:"24"},{default:a(()=>[e(U,{class:"pa-sm-10 pa-6"},{default:a(()=>[e(W)]),_:1})]),_:1})]),_:1})]),_:1})]),_:1})])]),_:1})]),_:1}),e(d,{cols:"12"},{default:a(()=>[e(w,{class:"pt-0 pb-6"},{default:a(()=>[e(j)]),_:1})]),_:1})]),_:1}))}});export{se as default};