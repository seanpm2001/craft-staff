import{d as c,o as s,b as d,e,t as m,f as a,i as y,g as i,F as p,u as n,G as f,j as x,h as u,p as _}from"./vue.esm-bundler.e8142316.js";import{u as h,d as b,D as g}from"./useApolloClient.9f162bd9.js";import{E as v}from"./employers.e321ff5c.js";import{L as w}from"./listitem--loading.f93c3183.js";const $=["href","title"],E={class:"flex items-center col-span-2 whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-indigo-800 sm:pl-6"},k={class:"object-cover object-center w-6 h-6 rounded-full overflow-hidden mb-0"},C=["src"],L={style:{"margin-bottom":"0"}},B={class:"flex items-center whitespace-nowrap px-3 py-4 text-sm text-gray-500"},j=e("div",{class:"flex items-center whitespace-nowrap px-3 py-4 text-sm text-gray-500"},"-",-1),N=e("div",{class:"flex items-center whitespace-nowrap px-3 py-4 text-sm text-gray-500"},"-",-1),S=e("div",{class:"flex items-center whitespace-nowrap px-3 py-4 text-sm text-gray-500"},"-",-1),D=c({__name:"listitem--benefit-employer",props:{employer:null},setup(t){return(o,r)=>t.employer?(s(),d("a",{key:0,href:`/admin/staff-management/benefits/employers/${t.employer.id}`,title:`Go to pay runs of ${t.employer.name}`,class:"grid grid-cols-6 border-b border-solid border-gray-200 no-underline hover:bg-gray-200"},[e("div",E,[e("div",k,[e("img",{src:t.employer.logoUrl,class:"w-full h-full"},null,8,C)]),e("span",L,m(t.employer.name),1)]),e("div",B,m(t.employer.crn?t.employer.crn:"-"),1),j,N,S],8,$)):a("",!0)}}),V=c({__name:"list--benefit-employers",props:{employers:Object},setup(t){return(o,r)=>(s(!0),d(p,null,y(t.employers,l=>(s(),i(D,{key:l.id,employer:l},null,8,["employer"]))),128))}}),A=e("div",{class:"sm:flex"},[e("div",{class:"sm:flex-auto"},[e("h1",{class:"text-xl font-semibold text-gray-900"}," Employers "),e("p",{class:"mt-2 text-sm text-gray-700"}," Select a company below to manage the benefits ")])],-1),F={class:"mt-8 flex flex-col w-full"},G={class:"-my-2 overflow-x-auto w-full"},O={class:"inline-block min-w-full py-2 align-middle"},R={class:"overflow-hidden shadow border border-solid border-gray-300 md:rounded-lg"},M=f('<div class="grid grid-cols-6 border-b border-solid border-gray-300"><div class="col-span-2 py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"> Company </div><div class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"> CRN </div><div class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"> Benefit count </div><div class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"> Last updated </div></div>',1),P=c({__name:"Employers",setup(t){const{result:o,loading:r}=h(v);return(l,U)=>(s(),d(p,null,[A,e("div",F,[e("div",G,[e("div",O,[e("div",R,[M,n(r)?(s(),i(w,{key:0})):a("",!0),n(o)?(s(),i(V,{key:1,employers:n(o).employers},null,8,["employers"])):a("",!0)])])])])],64))}}),Q=async()=>x({setup(){_(g,b)},render:()=>u(P)}).mount("#benefit-employer-container");Q().then(()=>{console.log()});
//# sourceMappingURL=benefitEmployers.695644f5.js.map
