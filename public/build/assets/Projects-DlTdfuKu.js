import{k as d,h as I,z as ne,T as ie,o as p,f as h,a as l,u as a,w as y,F as S,Z as ue,b as e,j as ce,d as _,t as r,e as q,i as g,v as de,l as B,m as T,D as me,n as pe,C as x,q as O,B as he,O as ge}from"./app-DPnApB4g.js";import{_ as fe}from"./AuthenticatedLayout-CryNvS0J.js";import{_ as W,a as ve}from"./DangerButton-CoxAGWRV.js";import{S as ye}from"./SaveButton-Du63H9Xn.js";import{_ as b,a as C,b as D}from"./TextInput-Z2BqUOoq.js";import be from"./ProjectCard-C0Htpkg5.js";import{h as z}from"./helpers-B3tPaVM3.js";import"./ApplicationLogo-Cu6Y3gsS.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";const _e=e("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"},"Projects",-1),xe={class:"py-12"},we={class:"max-w-7xl mx-auto sm:px-6 lg:px-8"},ke={class:"bg-white overflow-hidden shadow-sm sm:rounded-lg p-6"},Ce={class:"flex justify-between items-center mb-3 gap-2"},Ve={class:"flex gap-3 grow"},je=["disabled"],Se={class:"flex gap-2 items-center mb-2"},Te={class:"flex flex-wrap justify-between gap-5"},De=["onClick","onDragstart","onDrop"],$e={class:"p-4 text-white bg-blue-500 min-w-[360px] min-h-[220px] w-fit rounded flex justify-center items-center shadow-2xl order-[999999]"},Ee=e("path",{d:"M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344V280H168c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V168c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H280v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"},null,-1),Re=[Ee],Ne={class:"p-6"},Fe=e("h2",{class:"text-lg font-medium text-gray-900"}," Create new Project ",-1),He={class:"mt-6"},Ke={class:"mt-6"},Me={class:"mt-6"},Ue={class:"relative w-3/4"},Be={class:"absolute right-2 top-[9px]"},Oe={class:"mt-6"},ze={class:"relative w-3/4"},Ae={class:"absolute right-2 top-[9px]"},Le={class:"mt-6 flex justify-end"},Pe={class:"p-6"},Ie={class:"flex justify-between items-center gap-2 mb-5"},qe=e("h2",{class:"text-xl font-bold text-gray-900"}," Reports ",-1),We={class:"flex gap-1 justify-between items-center"},Ze=["disabled"],Ge=["disabled"],Je={class:"w-full"},Qe=e("tr",null,[e("th",null,"Name"),e("th",null,"Time"),e("th",null,"Earnings")],-1),Xe={class:"text-center px-5 py-1 text-lg font-bold bg-blue-200 border border-b-white"},Ye={class:"text-center px-5 py-1 text-lg font-bold bg-blue-200 border border-b-white"},et={class:"text-center px-5 py-1 text-lg font-bold bg-blue-200 border border-b-white"},tt=e("td",{class:"text-center px-5 py-1 text-lg font-bold bg-gray-200 border border-b-white"}," Totals ",-1),ot={class:"text-center px-5 py-1 text-lg font-bold bg-gray-200 border border-b-white"},st={class:"text-center px-5 py-1 text-lg font-bold bg-gray-200 border border-b-white"},lt={class:"text-xl"},ht={__name:"Projects",props:{projects:{type:Array},client:{type:Object},searchTerm:{type:String}},setup(f){const $=d(!1),E=d(null),Z=d(null),w=d([]),R=d(!1),N=d(!1),V=d(!0),j=d(!1),v=f,i=I(()=>v.projects);ne(()=>{F()});const F=()=>{i.value.forEach((s,o)=>{i.value[o].show=!0})},H=d("");let A=0;const G=s=>{clearTimeout(A),A=setTimeout(()=>{i.value.forEach((o,t)=>{o.name.toLowerCase().includes(H.value.toLowerCase())?i.value[t].show=!0:i.value[t].show=!1})},500)},J=()=>{setTimeout(()=>{i.value.forEach((s,o)=>{i.value[o].selected=R.value})},100)},Q=s=>{i.value[s].deleted=!0},X=()=>{O(()=>F())},Y=s=>{ge.get(route("project.show",s))},K=d(null),ee=s=>{K.value=s},te=s=>{s.preventDefault()},oe=s=>{const o=i.value[K.value].order,t=i.value[s].order;i.value[K.value].order=t,i.value[s].order=o;let u=[];i.value.forEach(c=>{u.push({id:c.id,order:c.order,name:c.name})}),axios.patch(route("project.ordering"),{projectsOrder:u}).then(c=>{c.data.success}).catch(function(c){})},n=ie({client_id:v.client.id,client_slug:v.client.slug,name:"",hourly_rate:String(v.client.hourly_rate),hourly_rate_two:String(v.client.hourly_rate_two),notes:""}),se=()=>{$.value=!0,O(()=>E.value.focus())},k=()=>{console.log(n),n.post(route("project.store"),{preserveScroll:!0,onSuccess:()=>{M(),O(()=>F())},onError:()=>E.value.focus()})},M=()=>{$.value=!1,n.reset()},L=d(0),P=d(0),U=d(0),m=(s,o=!1)=>{let t=o?s.hourly_rate_two:s.hourly_rate;return parseFloat(s.total_time/60/60*t).toFixed(2)},le=I(()=>!i.value.filter(s=>s.selected).length),ae=()=>{w.value=i.value.filter(u=>u.selected);let s=0,o=0;const t=w.value.reduce((u,c)=>(s+=parseFloat(m(c)),o+=parseFloat(m(c,!0)),console.log(s),u+c.total_time),0);L.value=z.makeTimeClock(t),P.value=s.toFixed(2),U.value=o.toFixed(2),console.log(w.value),N.value=!0},re=()=>{N.value=!1};return(s,o)=>(p(),h(S,null,[l(a(ue),{title:"Dashboard"}),l(fe,null,{header:y(()=>[_e]),default:y(()=>[e("div",xe,[e("div",we,[e("div",ke,[e("div",Ce,[e("div",Ve,[l(a(ce),{href:s.route("client.index"),class:"grow-0 inline-flex items-center w-fit px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"},{default:y(()=>[_(" << Back")]),_:1},8,["href"]),e("button",{type:"button",disabled:le.value,onClick:ae,class:"grow-0 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"}," Calculate",8,je),l(b,{for:"name",value:"name",class:"sr-only"}),l(C,{id:"name",ref_key:"searchInput",ref:Z,onKeyup:G,modelValue:H.value,"onUpdate:modelValue":o[0]||(o[0]=t=>H.value=t),type:"text",class:"grow",placeholder:"Search by Project Name"},null,8,["modelValue"])]),e("h1",null,r(f.client.name),1)]),e("div",Se,[l(b,{for:"selectall",value:"Select All",class:"text-black text-xl font-light leading-5",onClick:o[1]||(o[1]=q(()=>{},["stop"]))}),g(e("input",{type:"checkbox",id:"selectall","onUpdate:modelValue":o[2]||(o[2]=t=>R.value=t),onClick:o[3]||(o[3]=q(t=>J(),["stop"]))},null,512),[[de,R.value]])]),e("div",Te,[(p(!0),h(S,null,B(i.value,(t,u)=>g((p(),h("div",{key:u,draggable:!0,onClick:c=>Y(t.id),onDragstart:c=>ee(u),onDragover:te,onDrop:c=>oe(u),style:he("order:"+t.order),class:"p-3 hover:opacity-90 text-white bg-blue-500 min-w-[360px] min-h-[220px] w-[360px] rounded relative overflow-hidden shadow-xl border border-slate-400 cursor-pointer"},[l(be,{project:t,client:f.client,"onDelete:project":Q,"onUpdate:project":X,index:u},null,8,["project","client","index"])],44,De)),[[x,t.show&&!t.deleted]])),128)),e("div",$e,[(p(),h("svg",{class:"cursor-pointer w-20 h-20 opacity-50",onClick:se,xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 512 512"},Re))])]),l(W,{show:$.value,onClose:M},{default:y(()=>[e("div",Ne,[Fe,e("div",He,[l(b,{for:"name",value:"name",class:"sr-only"}),l(C,{id:"name",ref_key:"projectNameInput",ref:E,modelValue:a(n).name,"onUpdate:modelValue":o[4]||(o[4]=t=>a(n).name=t),type:"text",class:"mt-1 block w-3/4",placeholder:"Project Name",onKeyup:T(k,["enter"])},null,8,["modelValue"]),l(D,{message:a(n).errors.name,class:"mt-2"},null,8,["message"])]),e("div",Ke,[l(b,{for:"notes",value:"notes",class:"sr-only"}),g(e("textarea",{id:"notes","onUpdate:modelValue":o[5]||(o[5]=t=>a(n).notes=t),type:"text",class:"mt-1 block w-3/4 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm",placeholder:"Notes",onKeyup:T(k,["enter"])},null,544),[[me,a(n).notes]]),l(D,{message:a(n).errors.notes,class:"mt-2"},null,8,["message"])]),e("div",Me,[l(b,{for:"hourly_rate",value:"hourly_rate",class:"sr-only"}),e("div",Ue,[e("span",Be,r(v.client.currency)+" / Hour",1),l(C,{id:"hourly_rate",modelValue:a(n).hourly_rate,"onUpdate:modelValue":o[6]||(o[6]=t=>a(n).hourly_rate=t),type:"text",class:"mt-1 block w-3/4",placeholder:"Hourly Rate",onKeyup:T(k,["enter"])},null,8,["modelValue"])]),l(D,{message:a(n).errors.hourly_rate,class:"mt-2"},null,8,["message"])]),e("div",Oe,[l(b,{for:"hourly_rate_two",value:"hourly rate two",class:"sr-only"}),e("div",ze,[e("span",Ae,r(v.client.currency)+" / Hour",1),l(C,{id:"hourly_rate_two",modelValue:a(n).hourly_rate_two,"onUpdate:modelValue":o[7]||(o[7]=t=>a(n).hourly_rate_two=t),type:"text",class:"mt-1 block w-3/4",placeholder:"Second Hourly Rate",onKeyup:T(k,["enter"])},null,8,["modelValue"])]),l(D,{message:a(n).errors.hourly_rate_two,class:"mt-2"},null,8,["message"])]),e("div",Le,[l(ve,{onClick:M},{default:y(()=>[_(" Close ")]),_:1}),l(ye,{class:pe(["ms-3",{"opacity-25":a(n).processing}]),disabled:a(n).processing,onClick:k},{default:y(()=>[_(" Save ")]),_:1},8,["class","disabled"])])])]),_:1},8,["show"]),l(W,{show:N.value,onClose:re,maxWidth:"screenlg"},{default:y(()=>[e("div",Pe,[e("div",Ie,[qe,l(b,{for:"name",value:"name",class:"sr-only"}),l(C,{id:"name",ref:"hourlyRate",onKeyup:s.calculateHourlyRate,modelValue:s.hourlyRate,"onUpdate:modelValue":o[8]||(o[8]=t=>s.hourlyRate=t),type:"text",class:"grow",placeholder:"Hourly Rate"},null,8,["onKeyup","modelValue"]),e("div",We,[e("button",{type:"button",disabled:V.value,onClick:o[9]||(o[9]=t=>{V.value=!0,j.value=!1}),class:"grow-0 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"}," Table",8,Ze),e("button",{type:"button",disabled:j.value,onClick:o[10]||(o[10]=t=>{V.value=!1,j.value=!0}),class:"grow-0 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"}," Text",8,Ge)])]),g(e("table",Je,[Qe,(p(!0),h(S,null,B(w.value,(t,u)=>(p(),h("tr",{key:u},[e("td",Xe,r(t.name),1),e("td",Ye,r(a(z).makeTimeClock(t.total_time)),1),e("td",et,[_(r(m(t))+" "+r(f.client.currency)+" ",1),g(e("span",null," / "+r(m(t,!0))+" "+r(f.client.currency),513),[[x,m(t,!0)>0]])])]))),128)),e("tr",null,[tt,e("td",ot,r(L.value),1),e("td",st,[_(r(P.value)+" ",1),g(e("span",null," / "+r(U.value)+" "+r(f.client.currency),513),[[x,U.value>0]])])])],512),[[x,V.value]]),g(e("div",null,[(p(!0),h(S,null,B(w.value,(t,u)=>(p(),h("div",{key:u,class:"mb-5"},[e("p",lt,r(t.name),1),e("p",null,r(a(z).makeTimeClock(t.total_time)),1),e("p",null,[_(r(m(t))+" ",1),g(e("span",null," / "+r(m(t,!0))+" "+r(f.client.currency),513),[[x,m(t,!0)>0]])])]))),128))],512),[[x,j.value]])])]),_:1},8,["show"])])])])]),_:1})],64))}};export{ht as default};