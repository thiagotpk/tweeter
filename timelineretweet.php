
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
    	
        $sql = "select texto,retweets.data_hora_postagem,nome FROM retweets,tweets,usuarios
                  WHERE retweets.data_hora_postagem = (SELECT max(retweets.data_hora_postagem)FROM retweets ) and
                 tweets.codigo = retweets.codigo_tweet and
                 retweets.codigo_usuario = usuarios.codigo and usuarios.codigo = $codigo";
    }

    sleep(1);

    $result = mysql_query($sql);
    $cont = mysql_affected_rows($conexao);

  if ($cont > 0) {
      
 $restweet = "<div class=\"panel-body\">";
      while ($linha = mysql_fetch_array($result)) {
  
        $restweet .= "<div class=\"col-lg-10 col-md-10 col-sm-10 \">";
      $restweet .= "<div class=\"col-lg-10 col-md-10 col-sm-10 \">";
        $restweet .= " <a href='#'><img src='imagens/retweet.png'/>Retweet de ".utf8_encode($linha["nome"])."</a><br> ";
        $restweet .= "<label for='tweet'>"."'".utf8_encode($linha["texto"])."'</label><br>";
        
	    $restweet .= "</div>";
	    $restweet .= "<div class=\"col-lg-8 col-md-8 col-sm-8\">";
        $restweet .= "<br></br>";
        
       
        $restweet .= "<hr></hr>";


        $restweet .= "<br></br></div>";

	    }

	    echo $restweet .= "</div>";
   } 
                                   
 }




?>