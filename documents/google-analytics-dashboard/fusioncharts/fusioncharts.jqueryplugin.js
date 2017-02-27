/*
 FusionCharts JavaScript Library jQuery Plugin v1.0.4
 Copyright FusionCharts Technologies LLP
 License Information at <http://www.fusioncharts.com/license>

 @author FusionCharts Technologies LLP
*/
(function(x,t){"object"===typeof module&&module.exports?module.exports=x.document?t(x):function(q){if(!q.document)throw Error("Window with document not present");return t(q,!0)}:x.FusionCharts=t(x,!0)})("undefined"!==typeof window?window:this,function(x,t){FusionCharts.register("module",["private","HTMLTableDataHandler",function(){var q=this,H=q.window,x=H.document,h=function(c){var a,b,f=[];b=0;for(a=c.length;b<a;b+=1)3!==c[b].nodeType&&f.push(c[b]);return f},F=function(c){var a=h(c.childNodes);
if(a.length){if("TBODY"===a[0].nodeName)return a[0];if("THEAD"===a[0].nodeName&&a[1]&&"TBODY"===a[1].nodeName)return a[1]}return c},y=function(c){return void 0!==c.innerText?c.innerText:c.textContent},C=function(c){var a,b,f,p,d,e,k=1,g,l={},r=[];a=0;for(f=c.length;a<f;a+=1)for(d=h(c[a].childNodes),k=1,b=e=0,p=d.length;b<p;b+=1){g=b+k+e-1;l[g]&&a-l[g].rowNum<l[g].row&&(e+=l[g].col,g+=l[g].col);1<parseInt(d[b].getAttribute("rowspan"),10)&&(l[g]||(l[g]={}),l[g].rowNum=a,l[g].row=parseInt(d[b].getAttribute("rowspan"),
10),1<parseInt(d[b].getAttribute("colspan"),10)?l[g].col=parseInt(d[b].getAttribute("colspan"),10):l[g].col=1);for(;r.length<=g;)r.push({childNodes:[]});r[g].childNodes.push(d[b]);1<parseInt(d[b].getAttribute("colspan"),10)&&(k+=parseInt(d[b].getAttribute("colspan"),10)-1)}return r},t=function(c,a){for(var b=c.length;b;)if(--b,c[b]===a)return!0;return!1},z=0,w=function(c,a,b,f){var p,d,e,k,g=null,l=[],r=[],n=0,m,n={},q=0,v=0;if("undefined"===typeof b){k=h(c[0].childNodes);e=0;for(p=k.length;e<p;e+=
1)if(c=e+q,l[c]="__fcBLANK__"+(c+1),m=parseInt(k[e].colSpan,10),m=1<m?m:parseInt(k[e].rowSpan,10),1<m){for(b=1;b<m;b+=1)l[c+b]="__fcBLANK__"+(c+b+1);q+=m-1}d=0;b=e+q;for(p=a.length;d<p;d+=1)0<a[d]?delete l[a[d]-1]:delete l[b+a[d]];return{index:-1,labelObj:l}}if(0===b){d=0;for(b=c.length;d<b;d+=1){k=h(c[d].childNodes);n=r[d]=0;if(f&&f._extractByHeaderTag)for(e=0,p=k.length;e<p;e+=1){if("th"==k[e].nodeName.toLowerCase())return a=w(c,a,d+1),delete a.labelObj[f._rowLabelIndex],a}else for(e=0,p=k.length;e<
p;e+=1)if(!t(a,e+1)&&!t(a,e-p))if(m=y(k[e]),""===m.replace(/^\s*/,"").replace(/\s*$/,""))r[d]+=1;else if(parseFloat(m)!=m&&(n+=1,1<n))return w(c,a,d+1);0<d&&(r[d-1]>r[d]?g=d-1:r[d-1]<r[d]&&(g=d))}return null!==g?w(c,a,g+1):w(c,a)}0>b?b+=c.length:0<b&&--b;k=h(c[b].childNodes);f=void 0!==c[0].nodeType?!0:!1;e=0;for(p=k.length;e<p;e+=1){l=0;f?"1"!==k[e].colSpan&&(l=parseInt(k[e].colSpan,10)):"1"!==k[e].rowSpan&&(l=parseInt(k[e].rowSpan,10));l=1<l?l:0;m=y(k[e]);if(""!==m.replace(/^\s*/,"").replace(/\s*$/,
""))n[e+v]=m;else{a:{r=C(c);d=b;g=m=void 0;r=h(r[e].childNodes);q=void 0;m=0;for(g=r.length;m<g;m+=1)if(m!==d&&(q=y(r[m]),parseFloat(q)===q)){d=!0;break a}d=!1}d&&(n[e+v]="__fcBLANK__"+z,z+=1)}if(1<l){m=n[e+v];for(d=1;d<l;d+=1)n[e+v+d]=m+" ("+d+")";v+=l-1}}e=p+v;d=0;for(p=a.length;d<p;d+=1)0<a[d]?delete n[a[d]-1]:delete n[e+a[d]];return{labelObj:n,index:b}};q.addDataHandler("HTMLTable",{encode:function(c,a,b){var f,p,d;a={chartAttributes:{},major:"row",useLabels:!0,useLegend:!0,labelSource:0,legendSource:0,
ignoreCols:[],ignoreRows:[],showLabels:!0,showLegend:!0,seriesColors:[],convertBlankTo:"0",hideTable:!1,chartType:a.chartType&&a.chartType(),labels:[],legend:[],data:[]};var e,k,g,l={},r={};q.extend(a,b);"string"===typeof c&&(c=x.getElementById(c));"undefined"!==typeof H.jQuery&&c instanceof H.jQuery&&(c=c.get(0));if(c){a.hideTable&&(c.style.display="none");var n,m,t,v;b={};var D,A,z,E;p={};d={};g=h(c.childNodes);var B=h(g.length&&"THEAD"===g[0].nodeName&&g[1]&&"TBODY"===g[1].nodeName?g[0].childNodes:
[]).concat(h(F(c).childNodes)),I=B.length,J=0,K=0,G=0,u=0,L=!1,M=a.chartType;-1!=="column2d column3d pie3d pie2d line bar2d area2d doughnut2d doughnut3d pareto2d pareto3d".split(" ").indexOf(M)&&(L=!0);a.rowLabelSource=parseInt(a.labelSource,10);a.colLabelSource=parseInt(a.legendSource,10);"column"===a.major?(c=a.useLabels?w(B,a.ignoreCols,a.rowLabelSource):w(B,a.ignoreCols),g=a.useLegend?w(C(B),a.ignoreRows,a.colLabelSource):w(C(B),a.ignoreRows)):(m=w(C(B),a.ignoreRows,a.rowLabelSource),c=a.useLabels?
m:w(C(B),a.ignoreRows),a._rowLabelIndex=m.index,a._extractByHeaderTag=!0,g=a.useLegend?w(B,a.ignoreCols,a.colLabelSource,a):w(B,a.ignoreCols),delete a._extractByHeaderTag,m=c,c=g,g=m);delete c.labelObj[g.index];delete g.labelObj[c.index];if("row"===a.major)for(n in g.labelObj)b[n]={};else for(n in c.labelObj)b[n]={};for(n=0;n<I;n+=1)if(c.index!==n&&void 0!==g.labelObj[n]){J+=1;t=h(B[n].childNodes);p[n]=0;d[n]={};m=0;for(z=t.length;m<z;m+=1){v=t[m];A=parseInt(v.getAttribute("colspan"),10);E=parseInt(v.getAttribute("rowspan"),
10);for(D=m+p[n];u<n;){if(d[u])for(f in d[u]){if(f>D)break;n-u<=d[u][f].row&&(D+=d[u][f].col)}u+=1}1<A&&(p[n]+=A-1);1<E&&(d[n][D]=1<A?{row:E-1,col:A}:{row:E-1,col:1});if(g.index!==D&&void 0!==c.labelObj[D]){G+=1;v=y(v);if(""===v.replace(/^\s*/,"").replace(/\s*$/,""))if(a.convertBlankTo)v=a.convertBlankTo;else continue;A=1<A?A:1;E=1<E?E:1;if("row"===a.major)for(u=0;u<A;){for(f=0;f<E;)b[n+f][D+u]=parseFloat(v),f+=1;u+=1}else for(u=0;u<A;){for(f=0;f<E;)b[D+u][n+f]=parseFloat(v),f+=1;u+=1}}}G>K&&(K=G)}f=
b;b=M?L?"single":"multi":1<J&&1<K?"multi":"single";p=g;d=c}else f=null,d=p=b=void 0;"row"!==a.major?c=d:(c=p,p=d);l.chart=q.extend({},a.chartAttributes);if("multi"===b){l.categories=[{category:[]}];l.dataset=[];d=l.categories[0].category;g=l.dataset;b=0;for(e in f)for(k in!0===a.showLabels?d.push(q.extend({label:-1!=c.labelObj[e].indexOf("__fcBLANK__")?"":c.labelObj[e]},a.labels[b])):d.push({label:""}),b+=1,f[e])"undefined"===typeof r[k]&&(r[k]=[]),r[k].push({value:f[e][k]});b=0;for(e in r)!0===a.showLegend?
g.push(q.extend({seriesname:-1!==p.labelObj[e].indexOf("__fcBLANK__")?"":p.labelObj[e],data:r[e]},a.legend[b])):g.push({seriesname:"",data:r[e]}),b+=1}else if("single"===b)if(l.data=[],g=l.data,b=0,a.showLabels)for(e in f)for(k in f[e])g.push(q.extend({label:-1!==c.labelObj[e].indexOf("__fcBLANK__")?"":c.labelObj[e],value:f[e][k]},a.labels[b])),b+=1;else for(e in f)for(k in f[e])g.push({value:f[e][k]});return{data:q.core.transcodeData(l,"JSON","XML"),error:void 0}},decode:function(c,a){q.raiseError(a,
"07101734","run","::HTMLTableDataHandler.decode()","FusionCharts HTMLTable data-handler only supports decoding of data.");throw Error("FeatureNotSupportedException()");},transportable:!1})}]);FusionCharts.register("module",["private","extensions.jQueryPlugin",function(){var q=this,t=q.window,x=t.document,h=t.jQuery,F,y,C,I=t.Math.min,z=q.hcLib.isArray,w={feed:"feedData",setdata:"setData",setdataforid:"setDataForId",getdata:"getData",getdataforid:"getDataForId",clear:"clearChart",stop:"stopUpdate",
start:"restartUpdate"},c={feedData:function(a){return"string"===typeof a?[a]:"object"===typeof a&&a.stream?[a.stream]:!1},getData:function(a){return isNaN(a)?"object"===typeof a&&a.index?[a.index]:[]:[a]},getDataForId:function(a){return"string"===typeof a?[a]:"object"===typeof a&&a.id?[a.id]:[]},setData:function(a,b,f){var c=[];"object"!==typeof a?c=[a,b,f]:(a.value&&c.push(a.value),a.label&&c.push(a.label));return c},setDataForId:function(a,b,f){var c=[];"string"===typeof a||"string"===typeof b||
"string"===typeof f?c=[a,b,f]:"object"===typeof a&&(a.value&&c.push(a.value),a.label&&c.push(a.label));return c},clearChart:function(a){return[a]},stopUpdate:function(a){return[a]},restartUpdate:function(a){return[a]}};h.FusionCharts=q.core;F=function(a,b){var f,c,d,e;c=z(b)||b instanceof h?I(a.length,b.length):a.length;for(f=0;f<c;f+=1)d=z(b)||b instanceof h?b[f]:b,a[f].parentNode?q.core.render(h.extend({},d,{renderAt:a[f]})):(d=new FusionCharts(h.extend({},d,{renderAt:a[f]})),h.FusionCharts.delayedRender||
(h.FusionCharts.delayedRender={}),h.FusionCharts.delayedRender[d.id]=a[f],e=x.createElement("script"),e.setAttribute("type","text/javascript"),/msie/i.test(t.navigator.userAgent)&&!t.opera?e.text="FusionCharts.items['"+d.id+"'].render();":e.appendChild(x.createTextNode("FusionCharts.items['"+d.id+"'].render()")),a[f].appendChild(e));return a};q.addEventListener("*",function(a,b){var f;h.extend(a,h.Event("fusioncharts"+a.eventType));a.sender&&a.sender.options?(f=a.sender.options.containerElement||
a.sender.options.containerElementId,"object"===typeof f?h(f).trigger(a,b):h("#"+f).length?h("#"+f).trigger(a,b):h(x).trigger(a,b)):h(x).trigger(a,b)});y=function(a){return a.filter(":FusionCharts").add(a.find(":FusionCharts"))};C=function(a,b,f){"object"===typeof b&&a.each(function(){this.configureLink(b,f)})};h.fn.insertFusionCharts=function(a){return F(this,a)};h.fn.appendFusionCharts=function(a){a.insertMode="append";return F(this,a)};h.fn.prependFusionCharts=function(a){a.insertMode="prepend";
return F(this,a)};h.fn.attrFusionCharts=function(a,b){var f=[],c=y(this);if(void 0!==b)return c.each(function(){this.FusionCharts.setChartAttribute(a,b)}),this;if("object"===typeof a)return c.each(function(){this.FusionCharts.setChartAttribute(a)}),this;c.each(function(){f.push(this.FusionCharts.getChartAttribute(a))});return f};h.fn.updateFusionCharts=function(a){var b={},f=y(this),c=[["swfUrl",!1],["type",!1],["height",!1],["width",!1],["containerBackgroundColor",!0],["containerBackgroundAlpha",
!0],["dataFormat",!1],["dataSource",!1]],d,e,k,g,l,h;d=0;for(e=c.length;d<e;d+=1)l=c[d][0],b.type=b.type||b.swfUrl,a[l]&&(c[d][1]&&(g=!0),b[l]=a[l]);f.each(function(){k=this.FusionCharts;if(g)h=k.clone(b),h.render();else{if(void 0!==b.dataSource||void 0!==b.dataFormat)void 0===b.dataSource?k.setChartData(k.args.dataSource,b.dataFormat):void 0===b.dataFormat?k.setChartData(b.dataSource,k.args.dataFormat):k.setChartData(b.dataSource,b.dataFormat);void 0===b.width&&void 0===b.height||k.resizeTo(b.width,
b.height);b.type&&k.chartType(b.type)}});return this};h.fn.cloneFusionCharts=function(a,b){var c,p;"function"!==typeof a&&"function"===typeof b&&(p=a,a=b,b=p);c=[];y(this).each(function(){c.push(this.FusionCharts.clone(b,{},!0))});a.call(h(c),c);return this};h.fn.disposeFusionCharts=function(){y(this).each(function(){this.FusionCharts.dispose();delete this.FusionCharts;0===this._fcDrillDownLevel&&delete this._fcDrillDownLevel});return this};h.fn.convertToFusionCharts=function(a,b){var c=[];"undefined"===
typeof a.dataConfiguration&&(a.dataConfiguration={});h.extend(!0,a.dataConfiguration,b);a.dataSource||(a.dataSource=this.get(0));a.renderAt?"string"===typeof a.renderAt?c.push(h("#"+a.renderAt).insertFusionCharts(a).get(0)):"object"===typeof a.renderAt&&c.push(h(a.renderAt).insertFusionCharts(a).get(0)):this.each(function(){c.push(h("<div></div>").insertBefore(this).insertFusionCharts(a).get(0))});return h(c)};h.fn.drillDownFusionChartsTo=function(){var a=y(this),b,c,h,d,e;"undefined"===typeof this._fcDrillDownLevel&&
(this._fcDrillDownLevel=0);b=0;for(c=arguments.length;b<c;b+=1)if(e=arguments[b],z(e))for(h=0,d=e.length;h<d;h+=1)C(a,e[h],this._fcDrillDownLevel),this._fcDrillDownLevel+=1;else C(a,e,this._fcDrillDownLevel),this._fcDrillDownLevel+=1;return this};h.fn.streamFusionChartsData=function(a,b,f,h){var d=y(this),e=[],k,g,l;g=w[a&&a.toLowerCase()];if(void 0===g)if(1===arguments.length)l=[a],g=w.feed;else return this;else l=1===arguments.length?[]:c[g](b,f,h);if("getData"===g||"getDataForId"===g)return d.each(function(){k=
this.FusionCharts;"function"===typeof k[g]&&e.push(k[g].apply(k,l))}),e;d.each(function(){k=this.FusionCharts;"function"===typeof k[g]&&k[g].apply(k,l)});return this};h.extend(h.expr[":"],{FusionCharts:function(a){return a.FusionCharts instanceof q.core}})}]);t&&(x.FusionCharts=FusionCharts);return FusionCharts});