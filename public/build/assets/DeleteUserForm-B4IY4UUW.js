import{k as c,T as y,f as w,b as o,a as t,w as l,q as _,o as x,d as u,u as a,m as g,n as k}from"./app-BloNxtzK.js";import{D as m,_ as v,a as D}from"./DangerButton-CmKKXpRm.js";import{_ as b,a as C,b as V}from"./TextInput-PwV-8zbG.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";const B={class:"space-y-6"},U={class:"p-6"},$={class:"mt-6"},A={class:"mt-6 flex justify-end"},I={__name:"DeleteUserForm",setup(N){const r=c(!1),n=c(null),s=y({password:""}),p=()=>{r.value=!0,_(()=>n.value.focus())},i=()=>{s.delete(route("profile.destroy"),{preserveScroll:!0,onSuccess:()=>d(),onError:()=>n.value.focus(),onFinish:()=>s.reset()})},d=()=>{r.value=!1,s.reset()};return(P,e)=>(x(),w("section",B,[e[6]||(e[6]=o("header",null,[o("h2",{class:"text-lg font-medium text-gray-900"},"Delete Account"),o("p",{class:"mt-1 text-sm text-gray-600"}," Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain. ")],-1)),t(m,{onClick:p},{default:l(()=>e[1]||(e[1]=[u("Delete Account")])),_:1}),t(v,{show:r.value,onClose:d},{default:l(()=>[o("div",U,[e[4]||(e[4]=o("h2",{class:"text-lg font-medium text-gray-900"}," Are you sure you want to delete your account? ",-1)),e[5]||(e[5]=o("p",{class:"mt-1 text-sm text-gray-600"}," Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account. ",-1)),o("div",$,[t(b,{for:"password",value:"Password",class:"sr-only"}),t(C,{id:"password",ref_key:"passwordInput",ref:n,modelValue:a(s).password,"onUpdate:modelValue":e[0]||(e[0]=f=>a(s).password=f),type:"password",class:"mt-1 block w-3/4",placeholder:"Password",onKeyup:g(i,["enter"])},null,8,["modelValue"]),t(V,{message:a(s).errors.password,class:"mt-2"},null,8,["message"])]),o("div",A,[t(D,{onClick:d},{default:l(()=>e[2]||(e[2]=[u(" Cancel ")])),_:1}),t(m,{class:k(["ms-3",{"opacity-25":a(s).processing}]),disabled:a(s).processing,onClick:i},{default:l(()=>e[3]||(e[3]=[u(" Delete Account ")])),_:1},8,["class","disabled"])])])]),_:1},8,["show"])]))}};export{I as default};