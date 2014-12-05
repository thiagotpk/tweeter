<?php
include ("db.php");

 if (isset($_GET["cods"])){
 	
 	$login = $_GET["cods"];
    $conexao = mysql_connect($server, $user, $senha) or die("Erro na conexão!");
    mysql_select_db($base);

    $sqluser = "select * from usuarios where login = '$login'";

    $resp = mysql_query($sqluser) or die ("erro de consulta");

    $consulta = mysql_fetch_array($resp);
    $codigo = $consulta["codigo"];



    if (empty($codigo)){
    	$sql = "select * from tweets";
    } else {
    	$sql = "
        SELECT *
                FROM (

                SELECT usuarios.nome, tweets.texto, tweets.codigo, tweets.data_hora_postagem
                FROM seguidores, usuarios, tweets
                WHERE seguidores.codigo_seguidor = $codigo
                AND usuarios.codigo = seguidores.codigo_seguido
                AND tweets.codigo_usuario = seguidores.codigo_seguido
                UNION
                SELECT usuarios.nome, tweets.texto, tweets.codigo,  tweets.data_hora_postagem
                FROM seguidores, usuarios, tweets
                WHERE seguidores.codigo_seguidor = $codigo
                AND usuarios.codigo = seguidores.codigo_seguidor
                AND tweets.codigo_usuario = seguidores.codigo_seguidor
                ) as tabela
                order by data_hora_postagem desc


               ";




          

    }

    sleep(1);

    $result = mysql_query($sql);
   
    $cont = mysql_affected_rows($conexao);

  if ($cont > 0) {
      
 $restweet = "<div class=\"panel-body\">";
      while ($linha = mysql_fetch_array($result)) {
  
        $restweet .= "<div class=\"col-lg-8 col-md-8 col-sm-8 \">";
        $restweet .= " <a href='#'><img src='imagens/tweet.png'/>Tweet de ".utf8_encode($linha["nome"])."</a><br> ";
        $restweet .= "<label for='tweet'>"."'".utf8_encode($linha["texto"])."'</label><br>";
	    	
        
	    $restweet .= "</div>";
	    $restweet .= "<div class=\"col-lg-8 col-md-8 col-sm-8\">";

        $restweet .= "<br></br>";
        
        $restweet .= "<form method=\"post\" action=\"retweetar.php\">";
        $restweet .= "<input type='hidden' name='codigo_usuario' value='".$codigo."'>";
        $restweet .= "<input type='hidden' name='codigo_tweet' value='".$linha["codigo"]."'>";
        $restweet .= "<input type='submit' class='btn btn-primary' value='Retweetar'>";
        $restweet .= "</form>";
        $restweet .= "<br>";
        $restweet .= "<form method=\"post\" action=\"curtir.php\">";
        $restweet .= "<input type='hidden' name='codigo_usuario' value='".$codigo."'>";
        $restweet .= "<input type='hidden' name='codigo_tweet' value='".$linha["codigo"]."'>";
        $restweet .= "<input type='submit' class='btn btn-primary' value='Curtir'>";
        $restweet .= "</form>";
        $restweet .= "<hr></hr>";


        $restweet .= "<br></br></div>";

	    }


       

	    echo $restweet .= "</div>";
   } else {
   	 echo "Não foram encontrados registros";
   }
                                   
 }




?>