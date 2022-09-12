<?php 
	//Contem o estado atual do tabuleiro
	$jogadas = isset($_POST["json"]) ? json_decode(strip_tags($_POST["json"])) : "";
	//Indica qual é sua marca de jogar ('x' ou 'o')
	$marca = isset($_POST["marc"]) ? strip_tags($_POST["marc"]) : "";
	//Indica dificuldade
	$dificuldade = isset($_POST["difc"]) ? strip_tags($_POST["difc"]) : "";
	
	//EU MUDEI SEU CÓDIGO, ACHE O ERRO. GABRIEL LORENZON WETTERS

	//classe para trabalhar o minimax
class MiniMax{
	public $tabuleiro;
	public $heuristica;
	public $nivel;
}

$i;
$j;

//funcao analisa quem ganha
function Analisa($objeto, $nivel){
	global $marca;
	if($objeto->tabuleiro[0][0] == "x" && $objeto->tabuleiro[0][1] == "x" && $objeto->tabuleiro[0][2] == "x"){
		return ($marca == "o" ? 1 : -1);
	}
	if($objeto->tabuleiro[1][0] == "x" && $objeto->tabuleiro[1][1] == "x" && $objeto->tabuleiro[1][2] == "x"){
		return ($marca == "o" ? 1 : -1);
	}
	if($objeto->tabuleiro[2][0] == "x" && $objeto->tabuleiro[2][1] == "x" && $objeto->tabuleiro[2][2] == "x"){
		return ($marca == "o" ? 1 : -1);
	}
	if($objeto->tabuleiro[0][0] == "x" && $objeto->tabuleiro[1][0] == "x" && $objeto->tabuleiro[2][0] == "x"){
		return ($marca == "o" ? 1 : -1);
	}
	if($objeto->tabuleiro[0][1] == "x" && $objeto->tabuleiro[1][1] == "x" && $objeto->tabuleiro[2][1] == "x"){
		return ($marca == "o" ? 1 : -1);
	}
	if($objeto->tabuleiro[0][2] == "x" && $objeto->tabuleiro[1][2] == "x" && $objeto->tabuleiro[2][2] == "x"){
		return ($marca == "o" ? 1 : -1);
	}
	if($objeto->tabuleiro[0][0] == "x" && $objeto->tabuleiro[1][1] == "x" && $objeto->tabuleiro[2][2] == "x"){
		return ($marca == "o" ? 1 : -1);
	}
	if($objeto->tabuleiro[2][0] == "x" && $objeto->tabuleiro[1][1] == "x" && $objeto->tabuleiro[0][2] == "x"){
		return ($marca == "o" ? 1 : -1);
	}	
	if($objeto->tabuleiro[0][0] == "o" && $objeto->tabuleiro[0][1] == "o" && $objeto->tabuleiro[0][2] == "o"){
		return ($marca == "o" ? -1 : 1);
	}
	if($objeto->tabuleiro[1][0] == "o" && $objeto->tabuleiro[1][1] == "o" && $objeto->tabuleiro[1][2] == "o"){
		return ($marca == "o" ? -1 : 1);
	}
	if($objeto->tabuleiro[2][0] == "o" && $objeto->tabuleiro[2][1] == "o" && $objeto->tabuleiro[2][2] == "o"){
		return ($marca == "o" ? -1 : 1);
	}
	if($objeto->tabuleiro[0][0] == "o" && $objeto->tabuleiro[1][0] == "o" && $objeto->tabuleiro[2][0] == "o"){
		return ($marca == "o" ? -1 : 1);
	}
	if($objeto->tabuleiro[0][1] == "o" && $objeto->tabuleiro[1][1] == "o" && $objeto->tabuleiro[2][1] == "o"){
		return ($marca == "o" ? -1 : 1);
	}
	if($objeto->tabuleiro[0][2] == "o" && $objeto->tabuleiro[1][2] == "o" && $objeto->tabuleiro[2][2] == "o"){
		return ($marca == "o" ? -1 : 1);
	}
	if($objeto->tabuleiro[0][0] == "o" && $objeto->tabuleiro[1][1] == "o" && $objeto->tabuleiro[2][2] == "o"){
		return ($marca == "o" ? -1 : 1);
	}
	if($objeto->tabuleiro[2][0] == "o" && $objeto->tabuleiro[1][1] == "o" && $objeto->tabuleiro[0][2] == "o"){
		return ($marca == "o" ? -1 : 1);
	}
	if($nivel == 0){
		return 0;
	}
	return "";
}

$op = array();
$posição;
$escolha = array();


function MinMax($objeto, $simbolo, $nivel){
	global $posição;
	global $escolha;
	global $marca;
	if($nivel == 0 || (Analisa($objeto, $nivel) != "")){
		return Analisa($objeto, $nivel);
	}
	else{
		global $posição;
		if($simbolo==$marca){
			$objeto->heuristica = 2;
			for($i=0;$i<3;$i++){
				for($j=0;$j<3;$j++){
					if($objeto->tabuleiro[$i][$j] == "-"){
						$objeto->tabuleiro[$i][$j] = $simbolo;
						if($nivel == $objeto->nivel){
							$posição = $i*3+$j;
							array_push($escolha, $posição);
						}
						$heuristicaAjuda = MinMax(clone $objeto, ($marca == "o" ? "x" : "o"), $nivel-1);
						$objeto->heuristica = min($objeto->heuristica, $heuristicaAjuda);
						if($objeto->nivel == $nivel){
							if($heuristicaAjuda == 1){
								array_pop($escolha);
							}
							if($heuristicaAjuda == -1){
								array_push($escolha, $posição);
							}
						}
						if($objeto->nivel == $nivel){
							$posição = 0;
						}
						if($objeto->heuristica == -1){
							return -1;
						}
						$objeto->tabuleiro[$i][$j] = "-";
					}
				}
			}
			return $objeto->heuristica;
		}
		else{
			$objeto->heuristica = -2;
			for($i=0;$i<3;$i++){
				for($j=0;$j<3;$j++){
					if($objeto->tabuleiro[$i][$j] == "-"){
						$objeto->tabuleiro[$i][$j] = $simbolo;
						$objeto->heuristica = max($objeto->heuristica, MinMax(clone $objeto, $marca, $nivel-1));
						if($objeto->heuristica == 1){
							return 1;
						}
						$objeto->tabuleiro[$i][$j] = "-";
					}
				}
			}
			return $objeto->heuristica;
		}
	}
}


$objeto = new MiniMax();
$objeto->tabuleiro = $jogadas;
$nivel = 0;
$aleatorio = array();
for($i=0; $i<3; $i++){
	for($j=0; $j<3;$j++){
		if($jogadas[$i][$j] == "-"){
			$nivel = $nivel + 1;
			array_push($aleatorio, $i*3+$j);
		}
	}
}
$objeto->nivel = $nivel;
if ($nivel == 9)
	$escolha = array(0,1,2,3,4,4,4,4,4,4,5,6,7,8);
else{
	if($dificuldade == 3){
		MinMax($objeto, $marca, $nivel);
	}
	else{
		if($dificuldade == 2){
			if($nivel >= 3){
				MinMax($objeto, $marca, $nivel);
			}
			else{
				$escolha = $aleatorio;
			}
		}
		else{
			if($nivel >= 5){
				MinMax($objeto, $marca, $nivel);
			}
			else{
				$escolha = $aleatorio;
			}
		}
	}
}

if (count($escolha) > 0) {
	//gerar um número aleatório entre 0 e o número de elementos do array
	$i = rand(0,count($escolha)-1);
	//gerado...
	$j = (string) $escolha[$i];
}
echo $j;
?>
