/* File generated by shrinker.ch - DateTime: 2013-01-28, 09:16:17 */
(function(f){var v=(f.browser.msie?"paste":"input")+".mask",r=window.orientation!=undefined;f.mask={definitions:{9:"[0-9]",a:"[A-Za-z]","*":"[A-Za-z0-9]"},dataName:"rawMaskFn"};f.fn.extend({caret:function(k,h){if(this.length!=0){if(typeof k=="number"){h=typeof h=="number"?h:k;return this.each(function(){if(this.setSelectionRange)this.setSelectionRange(k,h);else if(this.createTextRange){var j=this.createTextRange();j.collapse(true);j.moveEnd("character",h);j.moveStart("character",k);j.select()}})}if(this[0].setSelectionRange){k=
this[0].selectionStart;h=this[0].selectionEnd}else if(document.selection&&document.selection.createRange){var s=document.selection.createRange();k=0-s.duplicate().moveStart("character",-1E5);h=k+s.text.length}return{begin:k,end:h}}},unmask:function(){return this.trigger("unmask")},mask:function(k,h){if(!k&&this.length>0)return f(this[0]).data(f.mask.dataName)();h=f.extend({placeholder:"_",completed:null},h);var s=f.mask.definitions,j=[],w=k.length,x=null,n=k.length;f.each(k.split(""),function(q,p){p==
"?"?(n--,w=q):s[p]?(j.push(RegExp(s[p])),x==null&&(x=j.length-1)):j.push(null)});return this.trigger("unmask").each(function(){function q(g){for(var a=i.val(),b=-1,c=0,d=0;c<n;c++)if(j[c]){for(o[c]=h.placeholder;d++<a.length;){var e=a.charAt(d-1);if(j[c].test(e)){o[c]=e;b=c;break}}if(d>a.length)break}else o[c]==a.charAt(d)&&c!=w&&(d++,b=c);if(!g&&b+1<w){i.val("");z(0,n)}else if(g||b+1>=w){p();g||i.val(i.val().substring(0,b+1))}return w?c:x}function p(){return i.val(o.join("")).val()}function z(g,
a){for(var b=g;b<a&&b<n;b++)j[b]&&(o[b]=h.placeholder)}function y(g){var a=g.which,b=i.caret();if(g.ctrlKey||g.altKey||g.metaKey||a<32)return true;if(a){b.end-b.begin!=0&&(z(b.begin,b.end),C(b.begin,b.end-1));g=t(b.begin-1);if(g<n){a=String.fromCharCode(a);if(j[g].test(a)){b=g;for(var c=h.placeholder;b<n;b++)if(j[b]){var d=t(b),e=o[b];o[b]=c;if(d<n&&j[d].test(e))c=e;else break}o[g]=a;p();g=t(g);i.caret(g);h.completed&&g>=n&&h.completed.call(i)}}return false}}function D(g){g=g.which;if(g==8||g==46||
r&&g==127){var a=i.caret(),b=a.begin;a=a.end;if(a-b==0){if(g!=46)for(;--b>=0&&!j[b];);else b=a=t(b-1);a=g==46?t(a):a}z(b,a);C(b,a-1);return false}if(g==27){i.val(A);i.caret(0,q());return false}}function C(g,a){if(!(g<0)){for(var b=g,c=t(a);b<n;b++)if(j[b]){if(c<n&&j[b].test(o[c])){o[b]=o[c];o[c]=h.placeholder}else break;c=t(c)}p();i.caret(Math.max(x,g))}}function t(g){for(;++g<=n&&!j[g];);return g}var i=f(this),o=f.map(k.split(""),function(g){if(g!="?")return s[g]?h.placeholder:g}),A=i.val();i.data(f.mask.dataName,
function(){return f.map(o,function(g,a){return j[a]&&g!=h.placeholder?g:null}).join("")});i.attr("readonly")||i.one("unmask",function(){i.unbind(".mask").removeData(f.mask.dataName)}).bind("focus.mask",function(){A=i.val();var g=q();p();var a=function(){g==k.length?i.caret(0,g):i.caret(g)};(f.browser.msie?a:function(){setTimeout(a,0)})()}).bind("blur.mask",function(){q();i.val()!=A&&i.change()}).bind("keydown.mask",D).bind("keypress.mask",y).bind(v,function(){setTimeout(function(){i.caret(q(true))},
0)});q()})}})})(jQuery);function CalcKeyCode(f){f.substring(0,1);return f.charCodeAt(0)}function checkNumber(f){var v=f.value.length,r=f.value.charAt(v-1);r=CalcKeyCode(r);if(r<48||r>57){v=f.value.substring(0,v-1);f.value=v}return false}
(function(f){function v(a,b){f.each(b,function(c,d){if(typeof d==="function")b[c]=d(a,b,c);else if(typeof d==="string"){var e=d.substr(0,4);if(e==="fun:"){e=f.autoNumeric[d.substr(4)];b[c]=typeof e==="function"?f.autoNumeric[d.substr(4)](a,b,c):null}else if(e==="css:")b[c]=f(d.substr(4)).val()}})}function r(a,b){if(typeof a[b]==="string")a[b]*=1}function k(a,b){var c=f.extend({},f.fn.autoNumeric.defaults,b);if(f.metadata)c=f.extend(c,a.metadata());v(a,c);var d=c.vMax.toString().split("."),e=!c.vMin&&
c.vMin!==0?[]:c.vMin.toString().split(".");r(c,"vMax");r(c,"vMin");r(c,"mDec");c.aNeg=c.vMin<0?"-":"";if(typeof c.mDec!=="number")c.mDec=Math.max((d[1]?d[1]:"").length,(e[1]?e[1]:"").length);if(c.altDec===null&&c.mDec>0)if(c.aDec==="."&&c.aSep!==",")c.altDec=",";else if(c.aDec===","&&c.aSep!==".")c.altDec=".";d=c.aNeg?"([-\\"+c.aNeg+"]?)":"(-?)";c._aNegReg=d;c._skipFirst=RegExp(d+"[^-"+(c.aNeg?"\\"+c.aNeg:"")+"\\"+c.aDec+"\\d].*?(\\d|\\"+c.aDec+"\\d)");c._skipLast=RegExp("(\\d\\"+c.aDec+"?)[^\\"+
c.aDec+"\\d]\\D*$");e=(c.aNeg?c.aNeg:"-")+c.aNum+"\\"+c.aDec;if(c.altDec&&c.altDec!==c.aSep)e+=c.altDec;c._allowed=RegExp("[^"+e+"]","gi");c._numReg=RegExp(d+"(?:\\"+c.aDec+"?(\\d+\\"+c.aDec+"\\d+)|(\\d*(?:\\"+c.aDec+"\\d*)?))");return c}function h(a,b,c){if(b.aSign)for(;a.indexOf(b.aSign)>-1;)a=a.replace(b.aSign,"");a=a.replace(b._skipFirst,"$1$2");a=a.replace(b._skipLast,"$1");a=a.replace(b._allowed,"");if(b.altDec)a=a.replace(b.altDec,b.aDec);a=(a=a.match(b._numReg))?[a[1],a[2],a[3]].join(""):
"";if(c){b="^"+b._aNegReg+"0*(\\d"+(c==="leading"?")":"|$)");a=a.replace(RegExp(b),"$1$2")}return a}function s(a,b,c){if(b&&c){var d=a.split(b);if(d[1]&&d[1].length>c)if(c>0){d[1]=d[1].substring(0,c);a=d.join(b)}else a=d[0]}return a}function j(a,b,c){if(b&&b!==".")a=a.replace(b,".");if(c&&c!=="-")a=a.replace(c,"-");a.match(/\d/)||(a+="0");return a}function w(a,b,c){if(c&&c!=="-")a=a.replace("-",c);if(b&&b!==".")a=a.replace(".",b);return a}function x(a,b){a=h(a,b);a=s(a,b.aDec,b.mDec);a=j(a,b.aDec,
b.aNeg);var c=+a;return c>=b.vMin&&c<=b.vMax}function n(a,b,c){if(a===""||a===b.aNeg)return b.wEmpty==="zero"?a+"0":b.wEmpty==="sign"||c?a+b.aSign:a;return null}function q(a,b){a=h(a,b);var c=n(a,b,true);if(c!==null)return c;c=b.dGroup===2?/(\d)((\d)(\d{2}?)+)$/:b.dGroup===4?/(\d)((\d{4}?)+)$/:/(\d)((\d{3}?)+)$/;var d=a.split(b.aDec);if(b.altDec&&d.length===1)d=a.split(b.altDec);var e=d[0];if(b.aSep)for(;c.test(e);)e=e.replace(c,"$1"+b.aSep+"$2");if(b.mDec!==0&&d.length>1){if(d[1].length>b.mDec)d[1]=
d[1].substring(0,b.mDec);a=e+b.aDec+d[1]}else a=e;if(b.aSign){c=a.indexOf(b.aNeg)!==-1;a=a.replace(b.aNeg,"");a=b.pSign==="p"?b.aSign+a:a+b.aSign;if(c)a=b.aNeg+a}return a}function p(a,b,c,d){a=a===""?"0":a.toString();var e="";e=0;var l="",u=typeof d==="boolean"||d===null?d?b:0:+d;d=function(B){B=B.replace(u===0?/(\.[1-9]*)0*$/:u===1?/(\.\d[1-9]*)0*$/:RegExp("(\\.\\d{"+u+"}[1-9]*)0*$"),"$1");if(u===0)B=B.replace(/\.$/,"");return B};if(a.charAt(0)==="-"){l="-";a=a.replace("-","")}a.match(/^\d/)||(a=
"0"+a);if(l==="-"&&+a===0)l="";if(+a>0)a=a.replace(/^0*(\d)/,"$1");var E=a.lastIndexOf("."),m=a.length-1-(E===-1?a.length-1:E);if(m<=b){e=a;if(m<u){if(E===-1)e+=".";for(;m<u;){c="000000".substring(0,u-m);e+=c;m+=c.length}}else if(m>u)e=d(e);else if(m===0&&u===0)e=e.replace(/\.$/,"");return l+e}b=E+b;e=+a.charAt(b+1);m=a.substring(0,b+1).split("");a=a.charAt(b)==="."?a.charAt(b-1)%2:a.charAt(b)%2;if(e>4&&c==="S"||e>4&&c==="A"&&l===""||e>5&&c==="A"&&l==="-"||e>5&&c==="s"||e>5&&c==="a"&&l===""||e>4&&
c==="a"&&l==="-"||e>5&&c==="B"||e===5&&c==="B"&&a===1||e>0&&c==="C"&&l===""||e>0&&c==="F"&&l==="-"||e>0&&c==="U")for(e=m.length-1;e>=0;e-=1)if(m[e]!=="."){m[e]=+m[e]+1;if(m[e]<10)break;else if(e>0)m[e]="0"}m=m.slice(0,b+1);e=d(m.join(""));return l+e}function z(a,b){this.options=b;this.that=a;this.$that=f(a);this.formatted=false;this.io=k(this.$that,this.options);this.value=a.value}function y(a,b){var c;c=a.data("autoNumeric");if(!c){c={};a.data("autoNumeric",c)}var d=c.holder;if(d===undefined&&b){d=
new z(a.get(0),b);c.holder=d}return d}function D(a){if((a=a.data("autoNumeric"))&&a.holder)return a.holder.options;return{}}function C(a){var b=f(a.target);b=y(b);b.init(a);if(b.skipAllways(a))return b.processed=true;if(b.processAllways()){b.processed=true;b.formatQuick();a.preventDefault();return false}else b.formatted=false;return true}function t(a){var b=f(a.target);b=y(b);var c=b.processed;b.init(a);if(b.skipAllways(a))return true;if(c){a.preventDefault();return false}if(b.processAllways()||b.processKeypress()){b.formatQuick();
a.preventDefault();return false}else b.formatted=false}function i(a){var b=f(a.target);b=y(b);b.init(a);a=b.skipAllways(a);b.kdCode=0;delete b.valuePartsBeforePaste;if(a)return true;if(this.value==="")return true;b.formatted||b.formatQuick()}function o(a){a=f(a.target);var b=y(a);b.inVal=a.val();b=n(b.inVal,b.io,true);b!==null&&a.val(b)}function A(a){a=f(a.target);var b=y(a),c=b.io,d=a.val(),e=d;if(d!==""){d=h(d,c);if(n(d,c)===null&&x(d,c)){d=j(d,c.aDec,c.aNeg);d=p(d,c.mDec,c.mRound,c.aPad);d=w(d,
c.aDec,c.aNeg)}else d=""}var l=n(d,c,false);if(l===null)l=q(d,c);l!==e&&a.val(l);if(l!==b.inVal){a.change();delete b.inVal}}function g(a){if(typeof a==="string"){a=a.replace(/\[/g,"\\[").replace(/\]/g,"\\]");a="#"+a.replace(/(:|\.)/g,"\\$1")}return f(a)}z.prototype={init:function(a){this.value=this.that.value;this.io=k(this.$that,this.options);this.cmdKey=a.metaKey;this.shiftKey=a.shiftKey;var b=this.that,c={};if(b.selectionStart===undefined){b.focus();var d=document.selection.createRange();c.length=
d.text.length;d.moveStart("character",-b.value.length);c.end=d.text.length;c.start=c.end-c.length}else{c.start=b.selectionStart;c.end=b.selectionEnd;c.length=c.end-c.start}this.selection=c;if(a.type==="keydown"||a.type==="keyup")this.kdCode=a.keyCode;this.which=a.which;this.formatted=this.processed=false},setSelection:function(a,b,c){a=Math.max(a,0);b=Math.min(b,this.that.value.length);this.selection={start:a,end:b,length:b-a};if(c===undefined||c){c=this.that;if(c.selectionStart===undefined){c.focus();
c=c.createTextRange();c.collapse(true);c.moveEnd("character",b);c.moveStart("character",a);c.select()}else{c.selectionStart=a;c.selectionEnd=b}}},setPosition:function(a,b){this.setSelection(a,a,b)},getBeforeAfter:function(){var a=this.value,b=a.substring(0,this.selection.start);a=a.substring(this.selection.end,a.length);return[b,a]},getBeforeAfterStriped:function(){var a=this.getBeforeAfter();a[0]=h(a[0],this.io);a[1]=h(a[1],this.io);return a},normalizeParts:function(a,b){var c=this.io;b=h(b,c);var d=
b.match(/^\d/)?true:"leading";a=h(a,c,d);if(a===""||a===c.aNeg)if(b>"")b=b.replace(/^0*(\d)/,"$1");d=a+b;if(c.aDec){var e=d.match(RegExp("^"+c._aNegReg+"\\"+c.aDec));if(e){a=a.replace(e[1],e[1]+"0");d=a+b}}if(c.wEmpty==="zero"&&(d===c.aNeg||d===""))a+="0";return[a,b]},setValueParts:function(a,b){var c=this.io,d=this.normalizeParts(a,b),e=d.join("");d=d[0].length;if(x(e,c)){e=s(e,c.aDec,c.mDec);if(d>e.length)d=e.length;this.value=e;this.setPosition(d,false);return true}return false},signPosition:function(){var a=
this.io,b=a.aSign,c=this.that;if(b){b=b.length;if(a.pSign==="p")return a.aNeg&&c.value&&c.value.charAt(0)===a.aNeg?[1,b+1]:[0,b];else{a=c.value.length;return[a-b,a]}}else return[1E3,-1]},expandSelectionOnSign:function(a){var b=this.signPosition(),c=this.selection;if(c.start<b[1]&&c.end>b[0])if((c.start<b[0]||c.end>b[1])&&this.value.substring(Math.max(c.start,b[0]),Math.min(c.end,b[1])).match(/^\s*$/))c.start<b[0]?this.setSelection(c.start,b[0],a):this.setSelection(b[1],c.end,a);else this.setSelection(Math.min(c.start,
b[0]),Math.max(c.end,b[1]),a)},checkPaste:function(){if(this.valuePartsBeforePaste!==undefined){var a=this.getBeforeAfter(),b=this.valuePartsBeforePaste;delete this.valuePartsBeforePaste;a[0]=a[0].substr(0,b[0].length)+h(a[0].substr(b[0].length),this.io);if(!this.setValueParts(a[0],a[1])){this.value=b.join("");this.setPosition(b[0].length,false)}}},skipAllways:function(a){var b=this.kdCode,c=this.which,d=this.cmdKey;if(b===17&&a.type==="keyup"&&this.valuePartsBeforePaste!==undefined){this.checkPaste();
return false}if(b>=112&&b<=123||b>=91&&b<=93||b>=9&&b<=31||b<8&&(c===0||c===b)||b===144||b===145||b===45)return true;if(d&&b===65)return true;if(d&&(b===67||b===86||b===88)){a.type==="keydown"&&this.expandSelectionOnSign();if(b===86)if(a.type==="keydown"||a.type==="keypress"){if(this.valuePartsBeforePaste===undefined)this.valuePartsBeforePaste=this.getBeforeAfter()}else this.checkPaste();return a.type==="keydown"||a.type==="keypress"||b===67}if(d)return true;if(b===37||b===39){c=this.io.aSep;d=this.selection.start;
var e=this.that.value;if(a.type==="keydown"&&c&&!this.shiftKey)if(b===37&&e.charAt(d-2)===c)this.setPosition(d-1);else b===39&&e.charAt(d)===c&&this.setPosition(d+1);return true}if(b>=34&&b<=40)return true;return false},processAllways:function(){var a;if(this.kdCode===8||this.kdCode===46){if(this.selection.length){this.expandSelectionOnSign(false);a=this.getBeforeAfterStriped()}else{a=this.getBeforeAfterStriped();if(this.kdCode===8)a[0]=a[0].substring(0,a[0].length-1);else a[1]=a[1].substring(1,a[1].length)}this.setValueParts(a[0],
a[1]);return true}return false},processKeypress:function(){var a=this.io,b=String.fromCharCode(this.which),c=this.getBeforeAfterStriped(),d=c[0];c=c[1];if(b===a.aDec||a.altDec&&b===a.altDec||(b==="."||b===",")&&this.kdCode===110){if(!a.mDec||!a.aDec)return true;if(a.aNeg&&c.indexOf(a.aNeg)>-1)return true;if(d.indexOf(a.aDec)>-1)return true;if(c.indexOf(a.aDec)>0)return true;if(c.indexOf(a.aDec)===0)c=c.substr(1);this.setValueParts(d+a.aDec,c);return true}if(b==="-"||b==="+"){if(!a.aNeg)return true;
if(d===""&&c.indexOf(a.aNeg)>-1){d=a.aNeg;c=c.substring(1,c.length)}d=d.charAt(0)===a.aNeg?d.substring(1,d.length):b==="-"?a.aNeg+d:d;this.setValueParts(d,c);return true}if(b>="0"&&b<="9"){if(a.aNeg&&d===""&&c.indexOf(a.aNeg)>-1){d=a.aNeg;c=c.substring(1,c.length)}this.setValueParts(d+b,c)}return true},formatQuick:function(){var a=this.io,b=this.getBeforeAfterStriped(),c=q(this.value,this.io),d=c.length;if(c){b=b[0].split("");var e;for(e=0;e<b.length;e+=1)b[e].match("\\d")||(b[e]="\\"+b[e]);b=RegExp("^.*?"+
b.join(".*?"));if(b=c.match(b)){d=b[0].length;if((d===0&&c.charAt(0)!==a.aNeg||d===1&&c.charAt(0)===a.aNeg)&&a.aSign&&a.pSign==="p")d=this.io.aSign.length+(c.charAt(0)==="-"?1:0)}else if(a.aSign&&a.pSign==="s")d-=a.aSign.length}this.that.value=c;this.setPosition(d);this.formatted=true}};f.fn.autoNumeric=function(a){return this.each(function(){var b=a;b=b||{};var c=f(this),d=y(c,b);if(d.io.aForm&&(this.value||d.io.wEmpty!=="empty"))c.autoNumericSet(c.autoNumericGet(b),b)}).unbind(".autoNumeric").bind({"keydown.autoNumeric":C,
"keypress.autoNumeric":t,"keyup.autoNumeric":i,"focusin.autoNumeric":o,"focusout.autoNumeric":A})};f.autoNumeric={};f.autoNumeric.Strip=function(a,b){var c=g(a),d=D(c);if(b&&typeof b==="object")d=f.extend({},d,b);c=k(c,d);d=g(a).val();d=h(d,c);d=j(d,c.aDec,c.aNeg);if(+d===0)d="0";return d};f.autoNumeric.Format=function(a,b,c){a=g(a);var d=D(a);if(c&&typeof c==="object")d=f.extend({},d,c);c=k(a,d);b=p(b,c.mDec,c.mRound,c.aPad);b=w(b,c.aDec,c.aNeg);x(b,c)||(b=p("",c.mDec,c.mRound,c.aPad));return q(b,
c)};f.fn.autoNumericGet=function(a){if(a)return f.autoNumeric.Strip(this,a);return f.autoNumeric.Strip(this)};f.fn.autoNumericSet=function(a,b){if(b)return this.val(f.autoNumeric.Format(this,a,b));return this.val(f.fn.autoNumeric.Format(this,a))};f.autoNumeric.defaults={aNum:"0123456789",aSep:",",dGroup:"3",aDec:".",altDec:null,aSign:"",pSign:"p",vMax:"999999999.99",vMin:"0.00",mDec:null,mRound:"S",aPad:true,wEmpty:"empty",aForm:false};f.fn.autoNumeric.defaults=f.autoNumeric.defaults;f.fn.autoNumeric.Strip=
f.autoNumeric.Strip;f.fn.autoNumeric.Format=f.autoNumeric.Format})(jQuery);
