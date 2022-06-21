import{g as x,u as f,L as g,d as h,D as v}from"./useApolloClient.52323202.js";import{d as m,f as r,i as y,j as t,v as o,p as i,x as _,F as u,q as d,u as c,y as b,t as w,h as $,z as k}from"./runtime-dom.esm-bundler.723b1be6.js";const C=x`
    query Employers {
        employers(orderBy: "name desc") {
            id
            crn
            name
            employeeCount
            currentYear
            logoUrl
            dateSynced:dateUpdated @formatDateTime(format:"Y-m-d H:i")
            currentPayRun{
                taxYear
                period
            }
        }
    }
`,E=["href","title"],R={class:"flex items-center col-span-2 whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-indigo-800 sm:pl-6"},L={class:"object-cover object-center w-6 h-6 rounded-full overflow-hidden mb-0"},P=["src"],S={style:{"margin-bottom":"0"}},Y={class:"flex items-center whitespace-nowrap px-3 py-4 text-sm text-gray-500"},B={class:"flex items-center whitespace-nowrap px-3 py-4 text-sm text-gray-500"},j={class:"flex items-center whitespace-nowrap px-3 py-4 text-sm text-gray-500"},D={class:"flex items-center whitespace-nowrap px-3 py-4 text-sm text-gray-500"},N=m({__name:"listitem--employer",props:{employer:null},setup(e){return(l,a)=>{var s,n,p;return e.employer?(r(),y("a",{key:0,href:`/admin/staff-management/pay-runs/${e.employer.id}/${(s=e.employer.currentPayRun)==null?void 0:s.taxYear}`,title:`Go to pay runs of ${e.employer.name}`,class:"grid grid-cols-6 border-b border-solid border-gray-200 no-underline hover:bg-gray-200"},[t("div",R,[t("div",L,[t("img",{src:e.employer.logoUrl,class:"w-full h-full"},null,8,P)]),t("span",S,o(e.employer.name),1)]),t("div",Y,o(e.employer.crn?e.employer.crn:"-"),1),t("div",B,o(e.employer.employeeCount),1),t("div",j,o((n=e.employer.currentPayRun)==null?void 0:n.taxYear)+" / "+o((p=e.employer.currentPayRun)==null?void 0:p.period),1),t("div",D,o(e.employer.dateSynced),1)],8,E)):i("",!0)}}}),q=m({__name:"list--employers",props:{employers:Object},setup(e){return(l,a)=>(r(!0),y(u,null,_(e.employers,s=>(r(),d(N,{key:s.id,employer:s},null,8,["employer"]))),128))}}),U=t("div",{class:"sm:flex"},[t("div",{class:"sm:flex-auto"},[t("h1",{class:"text-xl font-semibold text-gray-900"},"Employers"),t("p",{class:"mt-2 text-sm text-gray-700"},"Select a company below to begin bulk pay run management.")])],-1),V={class:"mt-8 flex flex-col w-full"},A={class:"-my-2 overflow-x-auto w-full"},F={class:"inline-block min-w-full py-2 align-middle"},O={class:"overflow-hidden shadow border border-solid border-gray-300 md:rounded-lg"},z=b('<div class="grid grid-cols-6 border-b border-solid border-gray-300"><div class="col-span-2 py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Company</div><div class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">CRN</div><div class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Employee count</div><div class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Current Pay Run</div><div class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Last synced</div></div>',1),G=m({__name:"Employers",setup(e){const{result:l,loading:a}=f(C);return(s,n)=>(r(),y(u,null,[U,t("div",V,[t("div",A,[t("div",F,[t("div",O,[z,c(a)?(r(),d(g,{key:0})):i("",!0),c(l)?(r(),d(q,{key:1,employers:c(l).employers},null,8,["employers"])):i("",!0)])])])])],64))}}),H=async()=>w({setup(){k(v,h)},render:()=>$(G)}).mount("#employer-container");H().then(()=>{console.log()});
//# sourceMappingURL=employers.5eb90b5a.js.map
