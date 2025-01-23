import{o as d,f as p,r as u,y as g,z as v,I as h,h as b,c as _,a as n,w as r,i as l,G as i,b as a,J as c,n as k,g as B,K as $}from"./app-BloNxtzK.js";import{_ as C}from"./_plugin-vue_export-helper-DlAUqK2U.js";const E=["type"],L={__name:"SecondaryButton",props:{type:{type:String,default:"button"}},setup(e){return(s,t)=>(d(),p("button",{type:e.type,class:"inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"},[u(s.$slots,"default")],8,E))}},S={class:"fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50","scroll-region":""},M={__name:"Modal",props:{show:{type:Boolean,default:!1},maxWidth:{type:String,default:"2xl"},closeable:{type:Boolean,default:!0}},emits:["close"],setup(e,{emit:s}){const t=e,w=s;g(()=>t.show,()=>{t.show?document.body.style.overflow="hidden":document.body.style.overflow=null});const m=()=>{t.closeable&&w("close")},f=o=>{o.key==="Escape"&&t.show&&m()};v(()=>document.addEventListener("keydown",f)),h(()=>{document.removeEventListener("keydown",f),document.body.style.overflow=null});const x=b(()=>({sm:"sm:max-w-sm",md:"sm:max-w-md",lg:"sm:max-w-lg",xl:"sm:max-w-xl","2xl":"sm:max-w-2xl",screenlg:"sm:max-w-screen-lg"})[t.maxWidth]);return(o,y)=>(d(),_($,{to:"body"},[n(c,{"leave-active-class":"duration-200"},{default:r(()=>[l(a("div",S,[n(c,{"enter-active-class":"ease-out duration-300","enter-from-class":"opacity-0","enter-to-class":"opacity-100","leave-active-class":"ease-in duration-200","leave-from-class":"opacity-100","leave-to-class":"opacity-0"},{default:r(()=>[l(a("div",{class:"fixed inset-0 transform transition-all",onClick:m},y[0]||(y[0]=[a("div",{class:"absolute inset-0 bg-gray-500 opacity-75"},null,-1)]),512),[[i,e.show]])]),_:1}),n(c,{"enter-active-class":"ease-out duration-300","enter-from-class":"opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95","enter-to-class":"opacity-100 translate-y-0 sm:scale-100","leave-active-class":"ease-in duration-200","leave-from-class":"opacity-100 translate-y-0 sm:scale-100","leave-to-class":"opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"},{default:r(()=>[l(a("div",{class:k(["mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:mx-auto",x.value])},[e.show?u(o.$slots,"default",{key:0}):B("",!0)],2),[[i,e.show]])]),_:3})],512),[[i,e.show]])]),_:3})]))}},z={},D={class:"inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"};function N(e,s){return d(),p("button",D,[u(e.$slots,"default")])}const T=C(z,[["render",N]]);export{T as D,M as _,L as a};