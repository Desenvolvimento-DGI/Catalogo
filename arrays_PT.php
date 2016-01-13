<?php
//------------------------------------------------------------------------------
//    Strings
//------------------------------------------------------------------------------
// Exibição
$matSatCod =  array (2, "CB2", "L3");

$matSatName = array (2, "Cbers", "Landsat");

$matOption = array(1=> "Incluir", "Pesquisar");

$matCompanyType = array(12, "Comercializa&ccedil;&atilde;o de Dados", "Empresa Estatal", "Empresa Privada",
            "Educação Básica", "Educa&ccedil;&atilde;o Superior", "Pesquisa", "Governo Municipal",
            "Governo Estadual", "Governo Federal", "Consultoria", "ONG", "Outro");

$matActivity = array(15, "Agricultura", "Biologia", "Cartografia", "Meio Ambiente",
            "Educa&ccedil;&atilde;o", "Floresta", "Geografia", "Geologia", "Saúde", "Hidrologia",
            "Processamento de Imagens", "Planejamento", "Socioeconomia", "Transportes", "Outro");

$matState = array(28, " ", "AC", "AL", "AP", "AM", "BA", "DF", "CE", "ES", "GO",
                           "MA", "MT", "MS", "MG", "PA", "PB", "PE", "PI", "PR",
                           "RJ", "RN", "RO", "RR", "RS", "SC", "SE", "SP", "TO");

$matUserType =  array(5, "Gerente", "Internet", "Funcionario INPE", "Pessoa Fisica/Jurídica", "Estrangeiro");

$matStatus =  array(4, "Autorizado", "N&atilde;o Autorizado", "Bloqueado", "Cancelado");

//$matOrderUser = array(4, "Usuário", "Nome", "Organização", "Cidade");
$matOrderUser = array(2, "Usu&aacute;rio", "Nome Usu&aacute;rio");

$matOrderReq = array(3, "Pedido", "Data", "Usu&aacute;rio");

$matReqItemStatus = array(9,  "Aguardando Autoriza&ccedil;&atilde;o", "Aguardando Produ&ccedil;&atilde;o", "Em Produ&ccedil;&atilde;o",
                              "Aguardando Grava&ccedil;&atilde;o Mídia", "Aguardando Despacho", "Despachado",
                              "Rejeitado", "Falha na Produ&ccedil;&atilde;o", "Problema na Produ&ccedil;&atilde;o");
$matReqStatus= array(6, "Aguardando Autoriza&ccedil;&atilde;o", "Aberto", "Produzido", "Fechado",
                         "Aguardando Produ&ccedil;&atilde;o", "Problema");
$matDepartment = array(7, "AMZ - Programa da Azmaz&ocirc;nia", "DGI- Divis&atilde;o de Gera&ccedil;&atilde;o de Imagens",
                           "DMA- Divis&atilde;o de Clima e Meio Ambiente", "DMD - Divis&atilde;o de Modelagem e Desenvolvimento",
                           "DPI - Divis&atilde;o de Processamento de Imagens", "DSA - Divis&atilde;o de Sat&eacute;lites e Sistemas Ambientais",
                           "DSR - Divis&atilde;o de Sensoriamento Remoto");
                           
                        
//Interna - Base de Dados
/*$matCompanyType = array(2, "Comercio", "Empresa Estatal", "Empresa Privada",
            "Educacao Basica", "Educacao Superior", "Pesquisa", "Governo Municipal",
            "Governo Estadual", "Consultoria", "ONG", "Outro");

$matActivityDis = array(15, "Agricultura", "Biologia", "Cartografia", "Meio Ambiente",
            "Educacao", "Floresta", "Geografia", "Geologia", "Saude", "Hidrologia",
            "Processamento de Imagens", "Planejamento", "Socioeconomia", "Transportes", "Outro");
*/
$matOrderUserInt = array(4, "User.userId", "User.fullName", "Company.name", "Address.city");
$matOrderReqInt = array(3, "ReqId", "ReqDate", "UserId");
$matReqItemStatusInt = array(9,  'A', 'B', 'C', 'D', 'E', 'F', 'Y', 'Z', 'W');
$matReqStatusInt = array(6,  'A', 'B', 'C', 'D', 'Z', 'Y');
?>
