<?php 
	$marc = isset($_GET["marc"]) ? $_GET["marc"] : "";
	$ord = isset($_GET["ord"]) ? "true" : "false";
	$difc = isset($_GET["difc"]) ? $_GET["difc"] : "";
	if( ($marc != "x" && $marc != "o" ) || ($difc != "3" && $difc != "2" && $difc != "1")){
		header("Location: index.html");
		exit();
	}
?>

<html>

<head>
	<title>Jogo da Terceira Idade MinMax</title>
	<style type="text/css">
		#jogo td {
			width: 80px;
			height: 80px;
			text-align: center;
			border-style: solid;
		}
		#config td {
			padding-bottom: 20px;
		}
		svg {
			width: 70px;
			height: 70px;
			vertical-align: middle;
		}
		line, circle {
			fill:none; 
			stroke:#000000; 
			stroke-width: 5;
		}
	</style>
	<script type="text/javascript" src="jquery-1.7.2.min.js"></script>
	<script type="text/javascript">
		var jogar = <?php echo $ord?>;
		var marca = "<?php echo $marc?>" == "x" ? true : false; // false:bola  true:cruz
		var jogadas = [['-','-','-'],['-','-','-'],['-','-','-']];
		var bola = "<svg xmlns=\"http://www.w3.org/2000/svg\" version=\"1.1\"><circle cx=\"35\" cy=\"35\" r=\"25\" /><circle cx=\"35\" cy=\"35\" r=\"25\" /></svg>";
		var cruz = "<svg xmlns=\"http://www.w3.org/2000/svg\" version=\"1.1\"><line x1=\"10\" y1=\"10\" x2=\"60\" y2=\"60\" /><line x1=\"60\" y1=\"10\" x2=\"10\" y2=\"60\" /></svg>";
		//var bola = new DOMParser().parseFromString("<circle cx=\"35\" cy=\"35\" r=\"25\" />", "text/xml").childNodes[0];
		function marcar(id, simbolo)
		{
			var linha = id%3;
			var coluna = (id - linha) / 3;
			if (jogadas[coluna][linha] == "-")
			{
				document.getElementById("p"+id).innerHTML = (simbolo ? cruz : bola);
				jogadas[coluna][linha] = (simbolo ? "x" : "o");
				return true;
			}
			return false;
		}
		function checaFim()
		{
			var i;
			var m0;
			var mi;
			//[0,1,2],[3,4,5],[6,7,8],[0,3,6],[1,4,7],[2,5,8],[0,4,8],[2,4,6]
			const simbols = ["x", "o"];
			for (i in simbols) {
				if (jogadas[0][0] == simbols[i]) {
					if (jogadas[0][1] == simbols[i]){
						if (jogadas[0][2] == simbols[i])
							return [0,1,2];
					}
					if (jogadas[1][0] == simbols[i]){
						if (jogadas[2][0] == simbols[i])
							return [0,3,6];
					}
					if (jogadas[1][1] == simbols[i]){
						if (jogadas[2][2] == simbols[i])
							return [0,4,8];
					}
				}
				if (jogadas[0][1] == simbols[i]) {
					if (jogadas[1][1] == simbols[i]){
						if (jogadas[2][1] == simbols[i])
							return [1,4,7];
					}
				}
				if (jogadas[0][2] == simbols[i]) {
					if (jogadas[1][2] == simbols[i]){
						if (jogadas[2][2] == simbols[i])
							return [2,5,8];
					}
					if (jogadas[1][1] == simbols[i]){
						if (jogadas[2][0] == simbols[i])
							return [2,4,6];
					}
				}
				if (jogadas[1][0] == simbols[i]) {
					if (jogadas[1][1] == simbols[i]){
						if (jogadas[1][2] == simbols[i])
							return [3,4,5];
					}
				}
				if (jogadas[2][0] == simbols[i]) {
					if (jogadas[2][1] == simbols[i]){
						if (jogadas[2][2] == simbols[i])
							return [6,7,8];
					}
				}
			}
			return null;
		}
		function checaVelha()
		{
			var i;
			var j;
			for (i in jogadas)
				for (j in jogadas[i])
					if (jogadas[i][j] == "-")
						return false;
			return true;
		}
		function destaca(ids)
		{
			if (ids == null)
				return false;
			var elem;
			var i;
			for (i in ids)
			{
				elem = (document.getElementById("p" + ids[i]).childNodes[0] != null ? document.getElementById("p" + ids[i]).childNodes[0] : []);
				if (elem.childNodes != null) {
					elem.childNodes[0].style.stroke = "#ff0000";
					if (elem.childNodes[1] != null)
						elem.childNodes[1].style.stroke = "#ff0000";
				}
			}
			return true;
		}
		function reiniciar()
		{
			for (var i = 0; i < 9; i++)
				document.getElementById("p"+i).innerHTML = "";
			jogadas = [['-','-','-'],['-','-','-'],['-','-','-']];
			document.getElementById("reinc").style.visibility = "hidden";
			document.getElementById("escap").style.visibility = "hidden";
			limpaAlerta();
			jogar = <?php echo $ord?>;
			if (!jogar)
				jogada();
		}
		function encerrar()
		{
			jogar = null;
			document.getElementById("reinc").style.visibility = "visible";
			document.getElementById("escap").style.visibility = "visible";
		}
		function alerta(msg, dep)
		{
			document.getElementById("aviso").innerHTML = msg;
			if (dep != null)
				document.getElementById("depurador").innerHTML = dep;
			//var tempo = setTimeout("limpaAlerta()", 2000);
		}
		
		function limpaAlerta () {document.getElementById("aviso").innerHTML = ""; document.getElementById("depurador").innerHTML = "";}
		function controle(id, ind)
		{
			if (jogar == null)
				return;
			if (ind) {
				if (jogar) {
					//alerta ("Aguarde...")
					return;
				}
				limpaAlerta();
				if (marcar(id, !marca)) {
					if (destaca(checaFim())){
						alerta("HUMANO BURRO!!!");
						encerrar();
						return;
					}
				} 
				else 
					return;
				if (checaVelha()){
					alerta("Um Oponente a Altura!");
					encerrar();
					return;
				}
				jogar = true;
			}
			else {
				if (!jogar) {
					alerta ("Aguarde...")
					return;
				}
				limpaAlerta();
				if (marcar(id, marca)) {
					if (destaca(checaFim())) {
						alerta("TENTE UM NÍVEL MAIS DIFICIL COVARDE!");
						encerrar();
						return;
					}
				} 
				else 
					return;
				if (checaVelha()){
					alerta("Um Oponente a Altura");
					encerrar();
					return;
				}
				jogar = false;
				jogada();
			}
		}
		$(document).ready(function() {
			jogada = function () {
				$.ajax({
					type: "POST",
					url: "MinMax.php",
					dataType: 'text',
					data: { json: JSON.stringify(jogadas), marc: (marca?"o":"x"), difc: <?php echo $difc;?> },
					// função para de sucesso
					success : function(resposta){
						if (resposta == "" || isNaN(parseInt(resposta))){
							alerta("<span color=\"#ff0000\">Erro:</span><br />" + "Falha na resposta do servidor", ((typeof resposta == "object")? resposta.responseText : resposta));
							encerrar();
						}
						else
							controle(parseInt(resposta),true);
						//alerta("<p color=\"#0000ff\">Sucesso:</p><br />" + ((typeof resposta == "object")? resposta.responseText : resposta));
					},
				// função para erros
					error: function(Msg){
						alerta("<p color=\"#ff0000\">Erro PHP</p><br />", ((typeof Msg == "object")? Msg.responseText : Msg));
					}
				});
			}
			if (!jogar)
				jogada();
		});
	</script>
</head>

<body>
	<div id="cabeçalho" align="center" style="margin-top: inherit;">
		<h1>Jogo da Terceira Idade MinMax</h1>
		<h3>Sistemas de Informação - Inteligência Artificial</h3>
		<h4>Marlom Marsal Marques</h4>
		<h4>Rubens Antônio Marcon</h4>
	</div>
	<br />
	<br />
	<table id="jogo" style="border-spacing: 0px; margin-left:auto; margin-right:auto;">
		<tr>
			<td id="p0" onClick="controle(0,false);" style="border-top-width: 0px; border-right-width: 2px; border-bottom-width: 2px; border-left-width: 0px;"></td>
			<td id="p1" onClick="controle(1,false);" style="border-top-width: 0px; border-right-width: 2px; border-bottom-width: 2px; border-left-width: 2px;"></td>
			<td id="p2" onClick="controle(2,false);" style="border-top-width: 0px; border-right-width: 0px; border-bottom-width: 2px; border-left-width: 2px;"></td>
		</tr>
		<tr>
			<td id="p3" onClick="controle(3,false);" style="border-top-width: 2px; border-right-width: 2px; border-bottom-width: 2px; border-left-width: 0px;"></td>
			<td id="p4" onClick="controle(4,false);" style="border-top-width: 2px; border-right-width: 2px; border-bottom-width: 2px; border-left-width: 2px;"></td>
			<td id="p5" onClick="controle(5,false);" style="border-top-width: 2px; border-right-width: 0px; border-bottom-width: 2px; border-left-width: 2px;"></td>
		</tr>
		<tr>
			<td id="p6" onClick="controle(6,false);" style="border-top-width: 2px; border-right-width: 2px; border-bottom-width: 0px; border-left-width: 0px;"></td>
			<td id="p7" onClick="controle(7,false);" style="border-top-width: 2px; border-right-width: 2px; border-bottom-width: 0px; border-left-width: 2px;"></td>
			<td id="p8" onClick="controle(8,false);" style="border-top-width: 2px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 2px;"></td>
		</tr>
	</table>
	<div align="center">
		<h3 id="aviso"></h3>
		<button id="reinc" onClick="reiniciar();"  style="visibility: hidden;">Reiniciar</button>
		<button id="escap" onClick="window.location = 'index.html';"  style="visibility: hidden;">Retornar</button>
	</div>
	<div id="depurador"></div>
</body>

</html>