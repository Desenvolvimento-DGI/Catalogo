<?php
//------------------------------------------------------------------------------
//    Strings
//------------------------------------------------------------------------------
// Exibi��o
$matSatCod =  array (2, "CB2", "L3");

$matSatName = array (2, "Cbers", "Landsat");

$matOption = array(1=> "Include", "Search");

$matCompanyType = array(12, "Comercializa��o de Dados", "Empresa Estatal", "Empresa Privada",
            "Educa��o B�sica", "Educa��o Superior", "Pesquisa", "Governo Municipal",
            "Governo Estadual", "Governo Federal", "Consultoria", "ONG", "Outro");

$matActivity = array(15, "Agricultura", "Biologia", "Cartografia", "Meio Ambiente",
            "Educa��o", "Floresta", "Geografia", "Geologia", "Sa�de", "Hidrologia",
            "Processamento de Imagens", "Planejamento", "Socioeconomia", "Transportes", "Outro");

$matState = array(28, " ", "AC", "AL", "AP", "AM", "BA", "DF", "CE", "ES", "GO",
                           "MA", "MT", "MS", "MG", "PA", "PB", "PE", "PI", "PR",
                           "RJ", "RN", "RO", "RR", "RS", "SC", "SE", "SP", "TO");

$matUserType =  array(5, "Gerente", "Internet", "Funcionario INPE", "Pessoa Fisica/Jur�dica", "Estrangeiro");

$matStatus =  array(4, "Authorized", "Not Authorized", "Locked", "Canceled");

//$matOrderUser = array(4, "Usu�rio", "Nome", "Organiza��o", "Cidade");
$matOrderUser = array(2, "User", "User Name");

$matOrderReq = array(3, "Request", "Date", "User");

$matReqItemStatus = array(9,  "Waiting Authorization", "Waiting Production", "In Production",
                              "Waiting Midia Recording", "Waiting for Dispatch", "Done",
                              "Rejected", "Production Failure", "Production Error");
$matReqStatus= array(6, "Waiting for Update", "Not Ready", "Production Ended", "Done",
                         "Waiting Production", "Production Failure");

//Interna - Base de Dados
/*$matCompanyType = array(2, "Comercio", "Empresa Estatal", "Empresa Privada",
            "Educacao Basica", "Educacao Superior", "Pesquisa", "Governo Municipal",
            "Governo Estadual", "Consultoria", "ONG", "Outro");

$matActivityDis = array(15, "Agricultura", "Biologia", "Cartografia", "Meio Ambiente",
            "Educacao", "Floresta", "Geografia", "Geologia", "Saude", "Hidrologia",
            "Processamento de Imagens", "Planejamento", "Socioeconomia", "Transportes", "Outro");
*/
$matOrderUserInt = array(4, "User.userId", "User.fullName", "User.name", "Address.city");
$matOrderReqInt = array(3, "ReqId", "ReqDate", "UserId");
$matReqItemStatusInt = array(9,  'A', 'B', 'C', 'D', 'E', 'F', 'Y', 'Z', 'W');
$matReqStatusInt = array(6,  'A', 'B', 'C', 'D', 'Y', 'Z');
?>
