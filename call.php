<?php

/**
 * @author solonalbuquerque@gmail.com
 * @copyright 2021
 */

// marca o local
setlocale(LC_CTYPE, 'pt_BR');


/**
 * Capturar informações detalhadas direto do IBGE.
 * Fonte: IBGE, Censo Demográfico 2010.
 * 
 * @access public
 * @author Solon Albuquerque
 * @param String $nome Nome da pessoa para verificar as informações
 * @param String $sexo Setar m para masculino e f para feminino
 * @return object
 */
function getIbge($nome, $sexo='m') {
    
    // travar entre m/f
    if($sexo!="m") $sexo = "f";
    
    // fazer a consulta via CURL
    $ch = curl_init("https://servicodados.ibge.gov.br/api/v1/censos/nomes/basica?nome={$nome}&sexo={$sexo}");
    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_RETURNTRANSFER => 1,
    ]);
    
    // tratar os dados
    $resposta = json_decode(curl_exec($ch), true);
    curl_close($ch);
    
    // é um print só para testes de verificar o resultado
    //die(print_r($resposta));
    
    // retornar o objeto da consulta
    return (object) $resposta[0];
    
}


/**
 * Definir qual o sexo a partir do nome
 * 
 * @access public
 * @author Solon Albuquerque
 * @param String $nome
 * @return object
 */
function defineSexo($nome='') {
    
    // remove acentos e caracteres especiais
    // referência: https://pt.stackoverflow.com/a/193583
    $nome = strtoupper(iconv('UTF-8', 'ASCII//TRANSLIT', trim(strip_tags($nome))));
    
    // salva definicao do nome
    $nomeOriginal = $nome;
    
    // trata o nome para pegar somente o primeiro nome
    $nome = current(explode(" ", $nome));
    
    // se não tiver, retorna vazio
    if($nome=="") return ["nome" => $nomeOriginal, "sexo" => null, "dados" => [], "mensagem" => "Nome não capturado"];
    
    /*
        Melhorias a serem realizadas:
        - colocar um banco de dados para consulta de resultado cacheado
        - se o resultado não estiver no banco, faz a consulta no ibge
        - salvar a consulta do ibge caso seja realizada
        - fazer um cron para atualizar os caches caso necessite utilizar as estatísticas e
    */
    
    // consulta o masculino
    $testeM = getIbge($nome, "m");
    
    // retorna caso o nome não tenha sinais de comparação (não é um nome teoricamente válido)
    if(!isset($testeM->freq) OR !is_numeric($testeM->freq))
        return ["nome" => $nomeOriginal, "sexo" => null, "dados" => [], "mensagem" => "Nome não encontrado para referência"];
        
    // consulta o feminino
    $testeF = getIbge($nome, "f");
        
    // verifica onde teve mais pessoas e retorna o mais provável
    
    // se a frequência masculina for maior que a feminina:
    if($testeM->freq > $testeF->freq)
        return ["nome" => $nomeOriginal, "sexo" => "m", "dados" => $testeM, "mensagem" => "Sucesso"];
    
    // se o sexo feminino for maior:
    return ["nome" => $nomeOriginal, "sexo" => "f", "dados" => $testeF, "mensagem" => "Sucesso"];
    
}

// vamos capturar o nome vindo do get
$nome = $_GET['nome'];
if($nome=="") $nome = urldecode($_REQUEST['nome']);

// vamos printar o nome na tela com o controle do cache
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');
header("Pragma: no-cache");

echo json_encode(defineSexo($nome));

// encerrar
exit;
