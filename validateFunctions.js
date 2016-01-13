function validate_form(parForm)
{

   var mensagem = "<?=$msgERR_001?>" + "\n\n";
   var erro = false;
   var valor2 = "";
   var valor3 = "";
   
   for(var ind = 0; ind < fields.length; ind++)
   {

      resultado = true;
      flagEmpty = false;
      valor = document.forms[parForm].elements[fields[ind]].value;
      //alert(fieldsName[ind] + "[" + fields[ind] + "] = " + valor + "-" + fieldType[ind] + "->" + fieldSt[ind] + " ind = " + ind);
      if(valor.length==0)
      {
         if(fieldSt[ind]==1) flagEmpty = true;
      }else
      {
         switch (fieldType[ind])
         {
            case 1:
               resultado = validate_numero(valor, true);break;
            case 2:
               resultado = validate_numero(valor, false);break;
            case 3:
               resultado = validate_data(valor);break;
            case 4:
               resultado = validate_email(valor); break;
            case 5:
               valor = clearInt(valor);
               resultado = validate_cpf(valor);
               document.forms[parForm].elements[fields[ind]].value = valor;
               break;
            case 6:
               valor2 = document.forms[parForm].elements[fields[++ind]].value;
               valor3 = document.forms[parForm].elements[fields[++ind]].value;
               valor  = clearInt(valor);
               valor2 = clearInt(valor2);
               valor3 = clearInt(valor3);
               resultado = validate_cnpj(valor, valor2, valor3);
               document.forms[parForm].elements[fields[ind-2]].value = valor;
               document.forms[parForm].elements[fields[ind-1]].value = valor2;
               document.forms[parForm].elements[fields[ind]].value = valor3;
               break;
            case 7:
               resultado = validate_cep(valor, parForm, fields[ind]); break;
            case 8:
               resultado = ((valor.length)==0)? false: true; break;
          }
      }
      if(!resultado || flagEmpty)
      {
         mensagem += " -> " + fieldsName[ind] + "\n";
         erro = true;
      }
   }
   if(erro) alert(mensagem);
   return !erro;
}
function clearInt(numero)
{
   var aux_numero = "";
   for(j=0;j<numero.length;j++)
         if(numero.substr(j,1) >= "0" && numero.substr(j,1) <= "9") aux_numero += numero.substr(j,1);
   return aux_numero;
}
function validate_numero(numero, inteiro)
{
   var val = "";
   var flagPoint = false;

   if(numero.length==0) return false;
   
   exp = /,/g;
   numero = numero.replace(exp,".");

   if(inteiro)
   {
      for(j=0;j<numero.length;j++)
         if(numero.substr(j,1) < "0" || numero.substr(j,1) > "9") return false;
   } else
   {
     for(j=0;j<numero.length;j++)
     {
         val = numero.substr(j,1);
         if( val == "." )
         {
            if(flagPoint)
               return false;
            else
               flagPoint = true;
         } else
         {
            if( val < "0" || val > "9" ) return false;
         }
     }
   }

   return true;
}
function validate_data(data)
{
   /*var datePat = /^(\d{1,2})(\/|-|.)(\d{1,2})\2(\d{4})$/;
   var dateDiv = data.match(datePat);

   if(dateDiv==null) return false;
   var day = dateDiv[1];
   var mon = dateDiv[2];
   var year = dateDiv[4];

   var dayComp = (mon<8&&mon%2)||((mon>=8)&&!(mon%2)))? 31: 30;
   if(mon==2&&((year%4==0 || (year%100==0)&&(year%400==0))) dayComp = 29;

   if(day < 1 || day > diaComp || mon < 1 || mon > 12 || year < 1960) return false;
   */
   return true;
}
function validate_email(email)
{
   var chars = "@#$&[]()/\\\{}!^:'\"";
   var pat=/^(.+)@(.+)$/;
   var i;

   var emailDiv = email.match(pat);
   if(emailDiv == null) return false;
   
   var loginEmail = emailDiv[1];
   var domainEmail = emailDiv[2];
   
   for(i=0; i < chars.length; i++)
      if(loginEmail.indexOf(chars.substr(i,1)) != -1) return false;

   for(var i=0; i<chars.length; i++)
      if(domainEmail.indexOf(chars.substr(i,1)) != -1) return false;

   return true;
}
function validate_cpf(cpf)
{
   var cpf = new String(cpf);
   if(cpf.length != 11)
      return false
   else
   {
      var cpf1 = String(cpf);
      var cpf2 = cpf1.substr(cpf1.length-2, 2);
      var controle = "";
      var begin = 2;
      var end = 10;
      for(var i=1; i<=2;i++)
      {
         var soma = 0;
         for(j=begin; j<=end; j++)
            soma+= cpf1.substr((j-i-1),1)*(end+1+i-j);
         if(i==2) soma+=digito*2;
         digito = (soma * 10) %11;
         if(digito==10) digito = 0;
         controle+=digito;
         begin = 3;
         end = 11;
      }
      //alert(controle);
      if(controle != cpf2) return false;
   }
   return true;
}
function  validate_cnpj(cnpj, comp, digito)
{
   var aux_cnpj ="";

   if(cnpj.length != 8) return false;

   if(comp.length != 4) return false

   if(digito.length != 2) return false;

   var aux_cnpj = cnpj + comp;

   fator = "543298765432";
   controle = "";
   for(j=0; j<2 ; j++)
   {
      soma = 0;
      for(i=0;i<12;i++)
         soma += aux_cnpj.substr(i,1) * fator.substr(i,1);
      if(j==1) soma += dig  * 2;
      dig = (soma * 10) % 11;
      if(dig == 10) dig = 0;
      controle += dig;
      fator = "654329876543";
   }
   //alert(controle);
   if(controle != digito) return false;
   return true;
}
function validate_cep(cep, parForm, ele)
{
   if((cep.indexOf("-")!=-1 && cep.length != 9) || (cep.indexOf("-")==-1 && cep.length != 8)) return false;
   var pat = /((\d{5})(-)(\d{3}))|(\d{8})/;
   var cepDiv = cep.match(pat);
   if(cepDiv==null) return false;
   if(cep.length==9)
   {
      cep =  cep.substr(0,5)+cep.substr(6,3);
      document.forms[parForm].elements[ele].value = cep;
   }
   return true;
}
