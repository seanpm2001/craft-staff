import{T as V,i as C,b as $,c as J,e as q,f as W,S as X,K as o,O,L as H,_ as P}from"./useApolloClient.83c3f34b.js";function Z(t){return typeof t=="object"&&t!==null}function ee(t,e){if(!Boolean(t))throw new Error(e!=null?e:"Unexpected invariant triggered.")}const te=/\r\n|[\n\r]/g;function S(t,e){let n=0,i=1;for(const s of t.body.matchAll(te)){if(typeof s.index=="number"||ee(!1),s.index>=e)break;n=s.index+s[0].length,i+=1}return{line:i,column:e+1-n}}function ne(t){return G(t.source,S(t.source,t.start))}function G(t,e){const n=t.locationOffset.column-1,i="".padStart(n)+t.body,s=e.line-1,a=t.locationOffset.line-1,c=e.line+a,h=e.line===1?n:0,l=e.column+h,E=`${t.name}:${c}:${l}
`,u=i.split(/\r\n|[\n\r]/g),m=u[s];if(m.length>120){const x=Math.floor(l/80),b=l%80,f=[];for(let T=0;T<m.length;T+=80)f.push(m.slice(T,T+80));return E+M([[`${c} |`,f[0]],...f.slice(1,x+1).map(T=>["|",T]),["|","^".padStart(b)],["|",f[x+1]]])}return E+M([[`${c-1} |`,u[s-1]],[`${c} |`,m],["|","^".padStart(l)],[`${c+1} |`,u[s+1]]])}function M(t){const e=t.filter(([i,s])=>s!==void 0),n=Math.max(...e.map(([i])=>i.length));return e.map(([i,s])=>i.padStart(n)+(s?" "+s:"")).join(`
`)}function se(t){const e=t[0];return e==null||"kind"in e||"length"in e?{nodes:e,source:t[1],positions:t[2],path:t[3],originalError:t[4],extensions:t[5]}:e}class F extends Error{constructor(e,...n){var i,s,a;const{nodes:c,source:h,positions:l,path:E,originalError:u,extensions:m}=se(n);super(e),this.name="GraphQLError",this.path=E!=null?E:void 0,this.originalError=u!=null?u:void 0,this.nodes=B(Array.isArray(c)?c:c?[c]:void 0);const x=B((i=this.nodes)===null||i===void 0?void 0:i.map(f=>f.loc).filter(f=>f!=null));this.source=h!=null?h:x==null||(s=x[0])===null||s===void 0?void 0:s.source,this.positions=l!=null?l:x==null?void 0:x.map(f=>f.start),this.locations=l&&h?l.map(f=>S(h,f)):x==null?void 0:x.map(f=>S(f.source,f.start));const b=Z(u==null?void 0:u.extensions)?u==null?void 0:u.extensions:void 0;this.extensions=(a=m!=null?m:b)!==null&&a!==void 0?a:Object.create(null),Object.defineProperties(this,{message:{writable:!0,enumerable:!0},name:{enumerable:!1},nodes:{enumerable:!1},source:{enumerable:!1},positions:{enumerable:!1},originalError:{enumerable:!1}}),u!=null&&u.stack?Object.defineProperty(this,"stack",{value:u.stack,writable:!0,configurable:!0}):Error.captureStackTrace?Error.captureStackTrace(this,F):Object.defineProperty(this,"stack",{value:Error().stack,writable:!0,configurable:!0})}get[Symbol.toStringTag](){return"GraphQLError"}toString(){let e=this.message;if(this.nodes)for(const n of this.nodes)n.loc&&(e+=`

`+ne(n.loc));else if(this.source&&this.locations)for(const n of this.locations)e+=`

`+G(this.source,n);return e}toJSON(){const e={message:this.message};return this.locations!=null&&(e.locations=this.locations),this.path!=null&&(e.path=this.path),this.extensions!=null&&Object.keys(this.extensions).length>0&&(e.extensions=this.extensions),e}}function B(t){return t===void 0||t.length===0?void 0:t}function d(t,e,n){return new F(`Syntax Error: ${n}`,{source:t,positions:[e]})}let L;(function(t){t.QUERY="QUERY",t.MUTATION="MUTATION",t.SUBSCRIPTION="SUBSCRIPTION",t.FIELD="FIELD",t.FRAGMENT_DEFINITION="FRAGMENT_DEFINITION",t.FRAGMENT_SPREAD="FRAGMENT_SPREAD",t.INLINE_FRAGMENT="INLINE_FRAGMENT",t.VARIABLE_DEFINITION="VARIABLE_DEFINITION",t.SCHEMA="SCHEMA",t.SCALAR="SCALAR",t.OBJECT="OBJECT",t.FIELD_DEFINITION="FIELD_DEFINITION",t.ARGUMENT_DEFINITION="ARGUMENT_DEFINITION",t.INTERFACE="INTERFACE",t.UNION="UNION",t.ENUM="ENUM",t.ENUM_VALUE="ENUM_VALUE",t.INPUT_OBJECT="INPUT_OBJECT",t.INPUT_FIELD_DEFINITION="INPUT_FIELD_DEFINITION"})(L||(L={}));let r;(function(t){t.SOF="<SOF>",t.EOF="<EOF>",t.BANG="!",t.DOLLAR="$",t.AMP="&",t.PAREN_L="(",t.PAREN_R=")",t.SPREAD="...",t.COLON=":",t.EQUALS="=",t.AT="@",t.BRACKET_L="[",t.BRACKET_R="]",t.BRACE_L="{",t.PIPE="|",t.BRACE_R="}",t.NAME="Name",t.INT="Int",t.FLOAT="Float",t.STRING="String",t.BLOCK_STRING="BlockString",t.COMMENT="Comment"})(r||(r={}));class ie{constructor(e){const n=new V(r.SOF,0,0,0,0);this.source=e,this.lastToken=n,this.token=n,this.line=1,this.lineStart=0}get[Symbol.toStringTag](){return"Lexer"}advance(){return this.lastToken=this.token,this.token=this.lookahead()}lookahead(){let e=this.token;if(e.kind!==r.EOF)do if(e.next)e=e.next;else{const n=ae(this,e.end);e.next=n,n.prev=e,e=n}while(e.kind===r.COMMENT);return e}}function re(t){return t===r.BANG||t===r.DOLLAR||t===r.AMP||t===r.PAREN_L||t===r.PAREN_R||t===r.SPREAD||t===r.COLON||t===r.EQUALS||t===r.AT||t===r.BRACKET_L||t===r.BRACKET_R||t===r.BRACE_L||t===r.PIPE||t===r.BRACE_R}function A(t){return t>=0&&t<=55295||t>=57344&&t<=1114111}function g(t,e){return K(t.charCodeAt(e))&&j(t.charCodeAt(e+1))}function K(t){return t>=55296&&t<=56319}function j(t){return t>=56320&&t<=57343}function N(t,e){const n=t.source.body.codePointAt(e);if(n===void 0)return r.EOF;if(n>=32&&n<=126){const i=String.fromCodePoint(n);return i==='"'?`'"'`:`"${i}"`}return"U+"+n.toString(16).toUpperCase().padStart(4,"0")}function p(t,e,n,i,s){const a=t.line,c=1+n-t.lineStart;return new V(e,n,i,a,c,s)}function ae(t,e){const n=t.source.body,i=n.length;let s=e;for(;s<i;){const a=n.charCodeAt(s);switch(a){case 65279:case 9:case 32:case 44:++s;continue;case 10:++s,++t.line,t.lineStart=s;continue;case 13:n.charCodeAt(s+1)===10?s+=2:++s,++t.line,t.lineStart=s;continue;case 35:return oe(t,s);case 33:return p(t,r.BANG,s,s+1);case 36:return p(t,r.DOLLAR,s,s+1);case 38:return p(t,r.AMP,s,s+1);case 40:return p(t,r.PAREN_L,s,s+1);case 41:return p(t,r.PAREN_R,s,s+1);case 46:if(n.charCodeAt(s+1)===46&&n.charCodeAt(s+2)===46)return p(t,r.SPREAD,s,s+3);break;case 58:return p(t,r.COLON,s,s+1);case 61:return p(t,r.EQUALS,s,s+1);case 64:return p(t,r.AT,s,s+1);case 91:return p(t,r.BRACKET_L,s,s+1);case 93:return p(t,r.BRACKET_R,s,s+1);case 123:return p(t,r.BRACE_L,s,s+1);case 124:return p(t,r.PIPE,s,s+1);case 125:return p(t,r.BRACE_R,s,s+1);case 34:return n.charCodeAt(s+1)===34&&n.charCodeAt(s+2)===34?de(t,s):he(t,s)}if(C(a)||a===45)return ce(t,s,a);if($(a))return fe(t,s);throw d(t.source,s,a===39?`Unexpected single quote character ('), did you mean to use a double quote (")?`:A(a)||g(n,s)?`Unexpected character: ${N(t,s)}.`:`Invalid character: ${N(t,s)}.`)}return p(t,r.EOF,i,i)}function oe(t,e){const n=t.source.body,i=n.length;let s=e+1;for(;s<i;){const a=n.charCodeAt(s);if(a===10||a===13)break;if(A(a))++s;else if(g(n,s))s+=2;else break}return p(t,r.COMMENT,e,s,n.slice(e+1,s))}function ce(t,e,n){const i=t.source.body;let s=e,a=n,c=!1;if(a===45&&(a=i.charCodeAt(++s)),a===48){if(a=i.charCodeAt(++s),C(a))throw d(t.source,s,`Invalid number, unexpected digit after 0: ${N(t,s)}.`)}else s=R(t,s,a),a=i.charCodeAt(s);if(a===46&&(c=!0,a=i.charCodeAt(++s),s=R(t,s,a),a=i.charCodeAt(s)),(a===69||a===101)&&(c=!0,a=i.charCodeAt(++s),(a===43||a===45)&&(a=i.charCodeAt(++s)),s=R(t,s,a),a=i.charCodeAt(s)),a===46||$(a))throw d(t.source,s,`Invalid number, expected digit but got: ${N(t,s)}.`);return p(t,c?r.FLOAT:r.INT,e,s,i.slice(e,s))}function R(t,e,n){if(!C(n))throw d(t.source,e,`Invalid number, expected digit but got: ${N(t,e)}.`);const i=t.source.body;let s=e+1;for(;C(i.charCodeAt(s));)++s;return s}function he(t,e){const n=t.source.body,i=n.length;let s=e+1,a=s,c="";for(;s<i;){const h=n.charCodeAt(s);if(h===34)return c+=n.slice(a,s),p(t,r.STRING,e,s+1,c);if(h===92){c+=n.slice(a,s);const l=n.charCodeAt(s+1)===117?n.charCodeAt(s+2)===123?pe(t,s):ue(t,s):le(t,s);c+=l.value,s+=l.size,a=s;continue}if(h===10||h===13)break;if(A(h))++s;else if(g(n,s))s+=2;else throw d(t.source,s,`Invalid character within String: ${N(t,s)}.`)}throw d(t.source,s,"Unterminated string.")}function pe(t,e){const n=t.source.body;let i=0,s=3;for(;s<12;){const a=n.charCodeAt(e+s++);if(a===125){if(s<5||!A(i))break;return{value:String.fromCodePoint(i),size:s}}if(i=i<<4|I(a),i<0)break}throw d(t.source,e,`Invalid Unicode escape sequence: "${n.slice(e,e+s)}".`)}function ue(t,e){const n=t.source.body,i=U(n,e+2);if(A(i))return{value:String.fromCodePoint(i),size:6};if(K(i)&&n.charCodeAt(e+6)===92&&n.charCodeAt(e+7)===117){const s=U(n,e+8);if(j(s))return{value:String.fromCodePoint(i,s),size:12}}throw d(t.source,e,`Invalid Unicode escape sequence: "${n.slice(e,e+6)}".`)}function U(t,e){return I(t.charCodeAt(e))<<12|I(t.charCodeAt(e+1))<<8|I(t.charCodeAt(e+2))<<4|I(t.charCodeAt(e+3))}function I(t){return t>=48&&t<=57?t-48:t>=65&&t<=70?t-55:t>=97&&t<=102?t-87:-1}function le(t,e){const n=t.source.body;switch(n.charCodeAt(e+1)){case 34:return{value:'"',size:2};case 92:return{value:"\\",size:2};case 47:return{value:"/",size:2};case 98:return{value:"\b",size:2};case 102:return{value:"\f",size:2};case 110:return{value:`
`,size:2};case 114:return{value:"\r",size:2};case 116:return{value:"	",size:2}}throw d(t.source,e,`Invalid character escape sequence: "${n.slice(e,e+2)}".`)}function de(t,e){const n=t.source.body,i=n.length;let s=t.lineStart,a=e+3,c=a,h="";const l=[];for(;a<i;){const E=n.charCodeAt(a);if(E===34&&n.charCodeAt(a+1)===34&&n.charCodeAt(a+2)===34){h+=n.slice(c,a),l.push(h);const u=p(t,r.BLOCK_STRING,e,a+3,J(l).join(`
`));return t.line+=l.length-1,t.lineStart=s,u}if(E===92&&n.charCodeAt(a+1)===34&&n.charCodeAt(a+2)===34&&n.charCodeAt(a+3)===34){h+=n.slice(c,a),c=a+1,a+=4;continue}if(E===10||E===13){h+=n.slice(c,a),l.push(h),E===13&&n.charCodeAt(a+1)===10?a+=2:++a,h="",c=a,s=a;continue}if(A(E))++a;else if(g(n,a))a+=2;else throw d(t.source,a,`Invalid character within String: ${N(t,a)}.`)}throw d(t.source,a,"Unterminated string.")}function fe(t,e){const n=t.source.body,i=n.length;let s=e+1;for(;s<i;){const a=n.charCodeAt(s);if(q(a))++s;else break}return p(t,r.NAME,e,s,n.slice(e,s))}function Ee(t,e){return new xe(t,e).parseDocument()}class xe{constructor(e,n){const i=W(e)?e:new X(e);this._lexer=new ie(i),this._options=n}parseName(){const e=this.expectToken(r.NAME);return this.node(e,{kind:o.NAME,value:e.value})}parseDocument(){return this.node(this._lexer.token,{kind:o.DOCUMENT,definitions:this.many(r.SOF,this.parseDefinition,r.EOF)})}parseDefinition(){if(this.peek(r.BRACE_L))return this.parseOperationDefinition();const e=this.peekDescription(),n=e?this._lexer.lookahead():this._lexer.token;if(n.kind===r.NAME){switch(n.value){case"schema":return this.parseSchemaDefinition();case"scalar":return this.parseScalarTypeDefinition();case"type":return this.parseObjectTypeDefinition();case"interface":return this.parseInterfaceTypeDefinition();case"union":return this.parseUnionTypeDefinition();case"enum":return this.parseEnumTypeDefinition();case"input":return this.parseInputObjectTypeDefinition();case"directive":return this.parseDirectiveDefinition()}if(e)throw d(this._lexer.source,this._lexer.token.start,"Unexpected description, descriptions are supported only on type definitions.");switch(n.value){case"query":case"mutation":case"subscription":return this.parseOperationDefinition();case"fragment":return this.parseFragmentDefinition();case"extend":return this.parseTypeSystemExtension()}}throw this.unexpected(n)}parseOperationDefinition(){const e=this._lexer.token;if(this.peek(r.BRACE_L))return this.node(e,{kind:o.OPERATION_DEFINITION,operation:O.QUERY,name:void 0,variableDefinitions:[],directives:[],selectionSet:this.parseSelectionSet()});const n=this.parseOperationType();let i;return this.peek(r.NAME)&&(i=this.parseName()),this.node(e,{kind:o.OPERATION_DEFINITION,operation:n,name:i,variableDefinitions:this.parseVariableDefinitions(),directives:this.parseDirectives(!1),selectionSet:this.parseSelectionSet()})}parseOperationType(){const e=this.expectToken(r.NAME);switch(e.value){case"query":return O.QUERY;case"mutation":return O.MUTATION;case"subscription":return O.SUBSCRIPTION}throw this.unexpected(e)}parseVariableDefinitions(){return this.optionalMany(r.PAREN_L,this.parseVariableDefinition,r.PAREN_R)}parseVariableDefinition(){return this.node(this._lexer.token,{kind:o.VARIABLE_DEFINITION,variable:this.parseVariable(),type:(this.expectToken(r.COLON),this.parseTypeReference()),defaultValue:this.expectOptionalToken(r.EQUALS)?this.parseConstValueLiteral():void 0,directives:this.parseConstDirectives()})}parseVariable(){const e=this._lexer.token;return this.expectToken(r.DOLLAR),this.node(e,{kind:o.VARIABLE,name:this.parseName()})}parseSelectionSet(){return this.node(this._lexer.token,{kind:o.SELECTION_SET,selections:this.many(r.BRACE_L,this.parseSelection,r.BRACE_R)})}parseSelection(){return this.peek(r.SPREAD)?this.parseFragment():this.parseField()}parseField(){const e=this._lexer.token,n=this.parseName();let i,s;return this.expectOptionalToken(r.COLON)?(i=n,s=this.parseName()):s=n,this.node(e,{kind:o.FIELD,alias:i,name:s,arguments:this.parseArguments(!1),directives:this.parseDirectives(!1),selectionSet:this.peek(r.BRACE_L)?this.parseSelectionSet():void 0})}parseArguments(e){const n=e?this.parseConstArgument:this.parseArgument;return this.optionalMany(r.PAREN_L,n,r.PAREN_R)}parseArgument(e=!1){const n=this._lexer.token,i=this.parseName();return this.expectToken(r.COLON),this.node(n,{kind:o.ARGUMENT,name:i,value:this.parseValueLiteral(e)})}parseConstArgument(){return this.parseArgument(!0)}parseFragment(){const e=this._lexer.token;this.expectToken(r.SPREAD);const n=this.expectOptionalKeyword("on");return!n&&this.peek(r.NAME)?this.node(e,{kind:o.FRAGMENT_SPREAD,name:this.parseFragmentName(),directives:this.parseDirectives(!1)}):this.node(e,{kind:o.INLINE_FRAGMENT,typeCondition:n?this.parseNamedType():void 0,directives:this.parseDirectives(!1),selectionSet:this.parseSelectionSet()})}parseFragmentDefinition(){var e;const n=this._lexer.token;return this.expectKeyword("fragment"),((e=this._options)===null||e===void 0?void 0:e.allowLegacyFragmentVariables)===!0?this.node(n,{kind:o.FRAGMENT_DEFINITION,name:this.parseFragmentName(),variableDefinitions:this.parseVariableDefinitions(),typeCondition:(this.expectKeyword("on"),this.parseNamedType()),directives:this.parseDirectives(!1),selectionSet:this.parseSelectionSet()}):this.node(n,{kind:o.FRAGMENT_DEFINITION,name:this.parseFragmentName(),typeCondition:(this.expectKeyword("on"),this.parseNamedType()),directives:this.parseDirectives(!1),selectionSet:this.parseSelectionSet()})}parseFragmentName(){if(this._lexer.token.value==="on")throw this.unexpected();return this.parseName()}parseValueLiteral(e){const n=this._lexer.token;switch(n.kind){case r.BRACKET_L:return this.parseList(e);case r.BRACE_L:return this.parseObject(e);case r.INT:return this._lexer.advance(),this.node(n,{kind:o.INT,value:n.value});case r.FLOAT:return this._lexer.advance(),this.node(n,{kind:o.FLOAT,value:n.value});case r.STRING:case r.BLOCK_STRING:return this.parseStringLiteral();case r.NAME:switch(this._lexer.advance(),n.value){case"true":return this.node(n,{kind:o.BOOLEAN,value:!0});case"false":return this.node(n,{kind:o.BOOLEAN,value:!1});case"null":return this.node(n,{kind:o.NULL});default:return this.node(n,{kind:o.ENUM,value:n.value})}case r.DOLLAR:if(e)if(this.expectToken(r.DOLLAR),this._lexer.token.kind===r.NAME){const i=this._lexer.token.value;throw d(this._lexer.source,n.start,`Unexpected variable "$${i}" in constant value.`)}else throw this.unexpected(n);return this.parseVariable();default:throw this.unexpected()}}parseConstValueLiteral(){return this.parseValueLiteral(!0)}parseStringLiteral(){const e=this._lexer.token;return this._lexer.advance(),this.node(e,{kind:o.STRING,value:e.value,block:e.kind===r.BLOCK_STRING})}parseList(e){const n=()=>this.parseValueLiteral(e);return this.node(this._lexer.token,{kind:o.LIST,values:this.any(r.BRACKET_L,n,r.BRACKET_R)})}parseObject(e){const n=()=>this.parseObjectField(e);return this.node(this._lexer.token,{kind:o.OBJECT,fields:this.any(r.BRACE_L,n,r.BRACE_R)})}parseObjectField(e){const n=this._lexer.token,i=this.parseName();return this.expectToken(r.COLON),this.node(n,{kind:o.OBJECT_FIELD,name:i,value:this.parseValueLiteral(e)})}parseDirectives(e){const n=[];for(;this.peek(r.AT);)n.push(this.parseDirective(e));return n}parseConstDirectives(){return this.parseDirectives(!0)}parseDirective(e){const n=this._lexer.token;return this.expectToken(r.AT),this.node(n,{kind:o.DIRECTIVE,name:this.parseName(),arguments:this.parseArguments(e)})}parseTypeReference(){const e=this._lexer.token;let n;if(this.expectOptionalToken(r.BRACKET_L)){const i=this.parseTypeReference();this.expectToken(r.BRACKET_R),n=this.node(e,{kind:o.LIST_TYPE,type:i})}else n=this.parseNamedType();return this.expectOptionalToken(r.BANG)?this.node(e,{kind:o.NON_NULL_TYPE,type:n}):n}parseNamedType(){return this.node(this._lexer.token,{kind:o.NAMED_TYPE,name:this.parseName()})}peekDescription(){return this.peek(r.STRING)||this.peek(r.BLOCK_STRING)}parseDescription(){if(this.peekDescription())return this.parseStringLiteral()}parseSchemaDefinition(){const e=this._lexer.token,n=this.parseDescription();this.expectKeyword("schema");const i=this.parseConstDirectives(),s=this.many(r.BRACE_L,this.parseOperationTypeDefinition,r.BRACE_R);return this.node(e,{kind:o.SCHEMA_DEFINITION,description:n,directives:i,operationTypes:s})}parseOperationTypeDefinition(){const e=this._lexer.token,n=this.parseOperationType();this.expectToken(r.COLON);const i=this.parseNamedType();return this.node(e,{kind:o.OPERATION_TYPE_DEFINITION,operation:n,type:i})}parseScalarTypeDefinition(){const e=this._lexer.token,n=this.parseDescription();this.expectKeyword("scalar");const i=this.parseName(),s=this.parseConstDirectives();return this.node(e,{kind:o.SCALAR_TYPE_DEFINITION,description:n,name:i,directives:s})}parseObjectTypeDefinition(){const e=this._lexer.token,n=this.parseDescription();this.expectKeyword("type");const i=this.parseName(),s=this.parseImplementsInterfaces(),a=this.parseConstDirectives(),c=this.parseFieldsDefinition();return this.node(e,{kind:o.OBJECT_TYPE_DEFINITION,description:n,name:i,interfaces:s,directives:a,fields:c})}parseImplementsInterfaces(){return this.expectOptionalKeyword("implements")?this.delimitedMany(r.AMP,this.parseNamedType):[]}parseFieldsDefinition(){return this.optionalMany(r.BRACE_L,this.parseFieldDefinition,r.BRACE_R)}parseFieldDefinition(){const e=this._lexer.token,n=this.parseDescription(),i=this.parseName(),s=this.parseArgumentDefs();this.expectToken(r.COLON);const a=this.parseTypeReference(),c=this.parseConstDirectives();return this.node(e,{kind:o.FIELD_DEFINITION,description:n,name:i,arguments:s,type:a,directives:c})}parseArgumentDefs(){return this.optionalMany(r.PAREN_L,this.parseInputValueDef,r.PAREN_R)}parseInputValueDef(){const e=this._lexer.token,n=this.parseDescription(),i=this.parseName();this.expectToken(r.COLON);const s=this.parseTypeReference();let a;this.expectOptionalToken(r.EQUALS)&&(a=this.parseConstValueLiteral());const c=this.parseConstDirectives();return this.node(e,{kind:o.INPUT_VALUE_DEFINITION,description:n,name:i,type:s,defaultValue:a,directives:c})}parseInterfaceTypeDefinition(){const e=this._lexer.token,n=this.parseDescription();this.expectKeyword("interface");const i=this.parseName(),s=this.parseImplementsInterfaces(),a=this.parseConstDirectives(),c=this.parseFieldsDefinition();return this.node(e,{kind:o.INTERFACE_TYPE_DEFINITION,description:n,name:i,interfaces:s,directives:a,fields:c})}parseUnionTypeDefinition(){const e=this._lexer.token,n=this.parseDescription();this.expectKeyword("union");const i=this.parseName(),s=this.parseConstDirectives(),a=this.parseUnionMemberTypes();return this.node(e,{kind:o.UNION_TYPE_DEFINITION,description:n,name:i,directives:s,types:a})}parseUnionMemberTypes(){return this.expectOptionalToken(r.EQUALS)?this.delimitedMany(r.PIPE,this.parseNamedType):[]}parseEnumTypeDefinition(){const e=this._lexer.token,n=this.parseDescription();this.expectKeyword("enum");const i=this.parseName(),s=this.parseConstDirectives(),a=this.parseEnumValuesDefinition();return this.node(e,{kind:o.ENUM_TYPE_DEFINITION,description:n,name:i,directives:s,values:a})}parseEnumValuesDefinition(){return this.optionalMany(r.BRACE_L,this.parseEnumValueDefinition,r.BRACE_R)}parseEnumValueDefinition(){const e=this._lexer.token,n=this.parseDescription(),i=this.parseEnumValueName(),s=this.parseConstDirectives();return this.node(e,{kind:o.ENUM_VALUE_DEFINITION,description:n,name:i,directives:s})}parseEnumValueName(){if(this._lexer.token.value==="true"||this._lexer.token.value==="false"||this._lexer.token.value==="null")throw d(this._lexer.source,this._lexer.token.start,`${v(this._lexer.token)} is reserved and cannot be used for an enum value.`);return this.parseName()}parseInputObjectTypeDefinition(){const e=this._lexer.token,n=this.parseDescription();this.expectKeyword("input");const i=this.parseName(),s=this.parseConstDirectives(),a=this.parseInputFieldsDefinition();return this.node(e,{kind:o.INPUT_OBJECT_TYPE_DEFINITION,description:n,name:i,directives:s,fields:a})}parseInputFieldsDefinition(){return this.optionalMany(r.BRACE_L,this.parseInputValueDef,r.BRACE_R)}parseTypeSystemExtension(){const e=this._lexer.lookahead();if(e.kind===r.NAME)switch(e.value){case"schema":return this.parseSchemaExtension();case"scalar":return this.parseScalarTypeExtension();case"type":return this.parseObjectTypeExtension();case"interface":return this.parseInterfaceTypeExtension();case"union":return this.parseUnionTypeExtension();case"enum":return this.parseEnumTypeExtension();case"input":return this.parseInputObjectTypeExtension()}throw this.unexpected(e)}parseSchemaExtension(){const e=this._lexer.token;this.expectKeyword("extend"),this.expectKeyword("schema");const n=this.parseConstDirectives(),i=this.optionalMany(r.BRACE_L,this.parseOperationTypeDefinition,r.BRACE_R);if(n.length===0&&i.length===0)throw this.unexpected();return this.node(e,{kind:o.SCHEMA_EXTENSION,directives:n,operationTypes:i})}parseScalarTypeExtension(){const e=this._lexer.token;this.expectKeyword("extend"),this.expectKeyword("scalar");const n=this.parseName(),i=this.parseConstDirectives();if(i.length===0)throw this.unexpected();return this.node(e,{kind:o.SCALAR_TYPE_EXTENSION,name:n,directives:i})}parseObjectTypeExtension(){const e=this._lexer.token;this.expectKeyword("extend"),this.expectKeyword("type");const n=this.parseName(),i=this.parseImplementsInterfaces(),s=this.parseConstDirectives(),a=this.parseFieldsDefinition();if(i.length===0&&s.length===0&&a.length===0)throw this.unexpected();return this.node(e,{kind:o.OBJECT_TYPE_EXTENSION,name:n,interfaces:i,directives:s,fields:a})}parseInterfaceTypeExtension(){const e=this._lexer.token;this.expectKeyword("extend"),this.expectKeyword("interface");const n=this.parseName(),i=this.parseImplementsInterfaces(),s=this.parseConstDirectives(),a=this.parseFieldsDefinition();if(i.length===0&&s.length===0&&a.length===0)throw this.unexpected();return this.node(e,{kind:o.INTERFACE_TYPE_EXTENSION,name:n,interfaces:i,directives:s,fields:a})}parseUnionTypeExtension(){const e=this._lexer.token;this.expectKeyword("extend"),this.expectKeyword("union");const n=this.parseName(),i=this.parseConstDirectives(),s=this.parseUnionMemberTypes();if(i.length===0&&s.length===0)throw this.unexpected();return this.node(e,{kind:o.UNION_TYPE_EXTENSION,name:n,directives:i,types:s})}parseEnumTypeExtension(){const e=this._lexer.token;this.expectKeyword("extend"),this.expectKeyword("enum");const n=this.parseName(),i=this.parseConstDirectives(),s=this.parseEnumValuesDefinition();if(i.length===0&&s.length===0)throw this.unexpected();return this.node(e,{kind:o.ENUM_TYPE_EXTENSION,name:n,directives:i,values:s})}parseInputObjectTypeExtension(){const e=this._lexer.token;this.expectKeyword("extend"),this.expectKeyword("input");const n=this.parseName(),i=this.parseConstDirectives(),s=this.parseInputFieldsDefinition();if(i.length===0&&s.length===0)throw this.unexpected();return this.node(e,{kind:o.INPUT_OBJECT_TYPE_EXTENSION,name:n,directives:i,fields:s})}parseDirectiveDefinition(){const e=this._lexer.token,n=this.parseDescription();this.expectKeyword("directive"),this.expectToken(r.AT);const i=this.parseName(),s=this.parseArgumentDefs(),a=this.expectOptionalKeyword("repeatable");this.expectKeyword("on");const c=this.parseDirectiveLocations();return this.node(e,{kind:o.DIRECTIVE_DEFINITION,description:n,name:i,arguments:s,repeatable:a,locations:c})}parseDirectiveLocations(){return this.delimitedMany(r.PIPE,this.parseDirectiveLocation)}parseDirectiveLocation(){const e=this._lexer.token,n=this.parseName();if(Object.prototype.hasOwnProperty.call(L,n.value))return n;throw this.unexpected(e)}node(e,n){var i;return((i=this._options)===null||i===void 0?void 0:i.noLocation)!==!0&&(n.loc=new H(e,this._lexer.lastToken,this._lexer.source)),n}peek(e){return this._lexer.token.kind===e}expectToken(e){const n=this._lexer.token;if(n.kind===e)return this._lexer.advance(),n;throw d(this._lexer.source,n.start,`Expected ${Y(e)}, found ${v(n)}.`)}expectOptionalToken(e){return this._lexer.token.kind===e?(this._lexer.advance(),!0):!1}expectKeyword(e){const n=this._lexer.token;if(n.kind===r.NAME&&n.value===e)this._lexer.advance();else throw d(this._lexer.source,n.start,`Expected "${e}", found ${v(n)}.`)}expectOptionalKeyword(e){const n=this._lexer.token;return n.kind===r.NAME&&n.value===e?(this._lexer.advance(),!0):!1}unexpected(e){const n=e!=null?e:this._lexer.token;return d(this._lexer.source,n.start,`Unexpected ${v(n)}.`)}any(e,n,i){this.expectToken(e);const s=[];for(;!this.expectOptionalToken(i);)s.push(n.call(this));return s}optionalMany(e,n,i){if(this.expectOptionalToken(e)){const s=[];do s.push(n.call(this));while(!this.expectOptionalToken(i));return s}return[]}many(e,n,i){this.expectToken(e);const s=[];do s.push(n.call(this));while(!this.expectOptionalToken(i));return s}delimitedMany(e,n){this.expectOptionalToken(e);const i=[];do i.push(n.call(this));while(this.expectOptionalToken(e));return i}}function v(t){const e=t.value;return Y(t.kind)+(e!=null?` "${e}"`:"")}function Y(t){return re(t)?`"${t}"`:t}var y=new Map,w=new Map,z=!0,D=!1;function Q(t){return t.replace(/[\s,]+/g," ").trim()}function me(t){return Q(t.source.body.substring(t.start,t.end))}function Ne(t){var e=new Set,n=[];return t.definitions.forEach(function(i){if(i.kind==="FragmentDefinition"){var s=i.name.value,a=me(i.loc),c=w.get(s);c&&!c.has(a)?z&&console.warn("Warning: fragment with name "+s+` already exists.
graphql-tag enforces all fragment names across your application to be unique; read more about
this in the docs: http://dev.apollodata.com/core/fragments.html#unique-names`):c||w.set(s,c=new Set),c.add(a),e.has(a)||(e.add(a),n.push(i))}else n.push(i)}),P(P({},t),{definitions:n})}function Te(t){var e=new Set(t.definitions);e.forEach(function(i){i.loc&&delete i.loc,Object.keys(i).forEach(function(s){var a=i[s];a&&typeof a=="object"&&e.add(a)})});var n=t.loc;return n&&(delete n.startToken,delete n.endToken),t}function Ae(t){var e=Q(t);if(!y.has(e)){var n=Ee(t,{experimentalFragmentVariables:D,allowLegacyFragmentVariables:D});if(!n||n.kind!=="Document")throw new Error("Not a valid GraphQL document.");y.set(e,Te(Ne(n)))}return y.get(e)}function k(t){for(var e=[],n=1;n<arguments.length;n++)e[n-1]=arguments[n];typeof t=="string"&&(t=[t]);var i=t[0];return e.forEach(function(s,a){s&&s.kind==="Document"?i+=s.loc.source.body:i+=s,i+=t[a+1]}),Ae(i)}function _e(){y.clear(),w.clear()}function Ie(){z=!1}function ke(){D=!0}function Oe(){D=!1}var _={gql:k,resetCaches:_e,disableFragmentWarnings:Ie,enableExperimentalFragmentVariables:ke,disableExperimentalFragmentVariables:Oe};(function(t){t.gql=_.gql,t.resetCaches=_.resetCaches,t.disableFragmentWarnings=_.disableFragmentWarnings,t.enableExperimentalFragmentVariables=_.enableExperimentalFragmentVariables,t.disableExperimentalFragmentVariables=_.disableExperimentalFragmentVariables})(k||(k={}));k.default=k;export{k as g};
//# sourceMappingURL=index.734efbd9.js.map
