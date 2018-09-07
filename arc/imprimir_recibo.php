<?php
session_start();
/*if(!isset($_SESSION['quincena'])){
	header('Location: ingreso.php');
}else{*/
require('../fpdf17/fpdf.php');
$pdf = new FPDF();
$pdf->AddPage('P','Letter');
$pdf->SetMargins(10,20,10);
$pdf->SetFont('Arial','',10);
$pdf->Image('../imagenes/encabezado_fontur.png',20,10,168,25,'PNG');
//$pdf->Image('../imagenes/FirmaDigitalCarnet.png',67,195,90,35,'PNG');
$pdf->Cell(195,30,'',0,1,'C');
$pdf->SetFont('Arial','B',10);
$pdf->Cell(195,5,'RECIBO DE PAGO',0,1,'C');
$pdf->Cell(195,5,utf8_decode('PERÍODO CORRESPONDIENTE DEL '.$_SESSION['fecha1'].' AL '.$_SESSION['fecha2']),0,1,'C');
$pdf->SetFont('Arial','',10);
$pdf->Cell(195,15,'',0,1,'C');
$pdf->Cell(195,0,'',1,1,'L');
$pdf->Cell(50,5,'UNIDAD:',0,0,'L');$pdf->SetFont('Arial','B',10);$pdf->Cell(145,5,$_SESSION['gerencia'],0,1,'L');$pdf->SetFont('Arial','',10);
$pdf->Cell(50,5,utf8_decode('CÉDULA DE IDENTIDAD:'),0,0,'L');$pdf->SetFont('Arial','B',10);$pdf->Cell(145,5,$_SESSION['ci'],0,1,'L');$pdf->SetFont('Arial','',10);
$pdf->Cell(50,5,'APELLIDOS Y NOMBRES:',0,0,'L');$pdf->SetFont('Arial','B',10);$pdf->Cell(145,5,$_SESSION['apellidosynombres'],0,1,'L');$pdf->SetFont('Arial','',10);
$pdf->Cell(50,5,'CARGO:',0,0,'L');$pdf->SetFont('Arial','B',10);$pdf->Cell(145,5,strtoupper($_SESSION['cargo']),0,1,'L');$pdf->SetFont('Arial','',10);
$pdf->Cell(50,5,utf8_decode('FECHA DE EMISIÓN:'),0,0,'L');$pdf->SetFont('Arial','B',10);$pdf->Cell(145,5,strtoupper($_SESSION['fechaahora']),0,1,'L');$pdf->SetFont('Arial','',10);
$pdf->Cell(195,0,'',1,1,'L');
$pdf->Cell(195,30,'',0,1,'C');
$pdf->Cell(195,1,'',1,1,'C');
$pdf->SetFont('Arial','B',10);
$pdf->Cell(96,5,'CONCEPTOS',1,0,'L');$pdf->Cell(33,5,'ASIGNACIONES',1,0,'C');$pdf->Cell(33,5,'DEDUCCIONES',1,0,'C');$pdf->Cell(33,5,'NETO',1,1,'C');
$pdf->SetFont('Arial','',10);
$pdf->Cell(195,1,'',1,1,'C');
$pdf->Cell(96,5,'SUELDO QUINCENAL',0,0,'L');$pdf->Cell(33,5,$_SESSION['sueldobase'],0,0,'R');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
if($_SESSION['diferenciaquincenal']!=''&&$_SESSION['diferenciaquincenal']!='0,00'&&$_SESSION['diferenciaquincenal']!='0'){
$pdf->Cell(96,5,'DIFERENCIA DE SUELDO POR ENCARGADURIA',0,0,'L');$pdf->Cell(33,5,$_SESSION['diferenciaquincenal'],0,0,'R');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['primaantiguedad']!=''&&$_SESSION['primaantiguedad']!='0,00'&&$_SESSION['primaantiguedad']!='0'){
$pdf->Cell(96,5,'PRIMA POR ANTIGUEDAD',0,0,'L');$pdf->Cell(33,5,$_SESSION['primaantiguedad'],0,0,'R');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['primajerarquia']!=''&&$_SESSION['primajerarquia']!='0,00'&&$_SESSION['primajerarquia']!='0'){
$pdf->Cell(96,5,'PRIMA DE JERARQUIA',0,0,'L');$pdf->Cell(33,5,$_SESSION['primajerarquia'],0,0,'R');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['primaprofesional']!=''&&$_SESSION['primaprofesional']!='0,00'&&$_SESSION['primaprofesional']!='0'){
$pdf->Cell(96,5,'PRIMA PROFESIONALIZACION',0,0,'L');$pdf->Cell(33,5,$_SESSION['primaprofesional'],0,0,'R');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
$pdf->Cell(96,5,'PRIMA HOGAR',0,0,'L');$pdf->Cell(33,5,$_SESSION['primahogar'],0,0,'R');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
if($_SESSION['bonoproductividad']!=''&&$_SESSION['bonoproductividad']!='0,00'&&$_SESSION['bonoproductividad']!='0'){
$pdf->Cell(96,5,'BONO PRODUCTIVIDAD',0,0,'L');$pdf->Cell(33,5,$_SESSION['bonoproductividad'],0,0,'R');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['bonofindeaño']!=''&&$_SESSION['bonofindeaño']!='0,00'&&$_SESSION['bonofindeaño']!='0'){
$pdf->Cell(96,5,'BONO FIN DE AÑO',0,0,'L');$pdf->Cell(33,5,$_SESSION['bonofindeaño'],0,0,'R');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['bonounico']!=''&&$_SESSION['bonounico']!='0,00'&&$_SESSION['bonounico']!='0'){
$pdf->Cell(96,5,'BONO UNICO',0,0,'L');$pdf->Cell(33,5,$_SESSION['bonounico'],0,0,'R');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['bonoevaluacion']!=''&&$_SESSION['bonoevaluacion']!='0,00'&&$_SESSION['bonoevaluacion']!='0'){
$pdf->Cell(96,5,'BONO EFICIENCIA',0,0,'L');$pdf->Cell(33,5,$_SESSION['bonoevaluacion'],0,0,'R');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['diferenciadesueldo']!=''&&$_SESSION['diferenciadesueldo']!='0,00'&&$_SESSION['diferenciadesueldo']!='0'){
$pdf->Cell(96,5,'DIFERENCIA SUELDO PENDIENTE',0,0,'L');$pdf->Cell(33,5,$_SESSION['diferenciadesueldo'],0,0,'R');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['diferenciaencargaduria']!=''&&$_SESSION['diferenciaencargaduria']!='0,00'&&$_SESSION['diferenciaencargaduria']!='0'){
$pdf->Cell(96,5,'DIF. SUELDO ENCARGADURIA PENDIENTE',0,0,'L');$pdf->Cell(33,5,$_SESSION['diferenciaencargaduria'],0,0,'R');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['primaantiguedadpendiente']!=''&&$_SESSION['primaantiguedadpendiente']!='0,00'&&$_SESSION['primaantiguedadpendiente']!='0'){
$pdf->Cell(96,5,'PRIMA POR ANTIGUEDAD PENDIENTE',0,0,'L');$pdf->Cell(33,5,$_SESSION['primaantiguedadpendiente'],0,0,'R');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['primajerarquiapendiente']!=''&&$_SESSION['primajerarquiapendiente']!='0,00'&&$_SESSION['primajerarquiapendiente']!='0'){
$pdf->Cell(96,5,'PRIMA DE JERARQUIA PENDIENTE',0,0,'L');$pdf->Cell(33,5,$_SESSION['primajerarquiapendiente'],0,0,'R');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['primaprofesionalpendiente']!=''&&$_SESSION['primaprofesionalpendiente']!='0,00'&&$_SESSION['primaprofesionalpendiente']!='0'){
$pdf->Cell(96,5,'PRIMA PROFESIONALIZACION PENDIENTE',0,0,'L');$pdf->Cell(33,5,$_SESSION['primaprofesionalpendiente'],0,0,'R');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['primahogarpendiente']!=''&&$_SESSION['primahogarpendiente']!='0,00'&&$_SESSION['primahogarpendiente']!='0'){
$pdf->Cell(96,5,'PRIMA HOGAR PENDIENTE',0,0,'L');$pdf->Cell(33,5,$_SESSION['primahogarpendiente'],0,0,'R');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['primaproductividadpendiente']!=''&&$_SESSION['primaproductividadpendiente']!='0,00'&&$_SESSION['primaproductividadpendiente']!='0'){
$pdf->Cell(96,5,'BONO PRODUCTIVIDAD PENDIENTE',0,0,'L');$pdf->Cell(33,5,$_SESSION['primaproductividadpendiente'],0,0,'R');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['diferenciadesueldopendiente']!=''&&$_SESSION['diferenciadesueldopendiente']!='0,00'&&$_SESSION['diferenciadesueldopendiente']!='0'){
$pdf->Cell(96,5,'DIFERENCIA SUELDO PENDIENTE',0,0,'L');$pdf->Cell(33,5,$_SESSION['diferenciadesueldopendiente'],0,0,'R');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['diferenciasueldoencargaduriapendiente']!=''&&$_SESSION['diferenciasueldoencargaduriapendiente']!='0,00'&&$_SESSION['diferenciasueldoencargaduriapendiente']!='0'){
$pdf->Cell(96,5,'DIF. SUELDO ENCARGADURIA PENDIENTE '.date('Y')-1,0,0,'L');$pdf->Cell(33,5,$_SESSION['diferenciasueldoencargaduriapendiente'],0,0,'R');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['primaantiguedadpendienteanterior']!=''&&$_SESSION['primaantiguedadpendienteanterior']!='0,00'&&$_SESSION['primaantiguedadpendienteanterior']!='0'){
$pdf->Cell(96,5,'PRIMA DE ANTIGUEDAD PENDIENTE '.date('Y')-1,0,0,'L');$pdf->Cell(33,5,$_SESSION['primaantiguedadpendienteanterior'],0,0,'R');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['primajerarquiapendientependienteanterior']!=''&&$_SESSION['primajerarquiapendientependienteanterior']!='0,00'&&$_SESSION['primajerarquiapendientependienteanterior']!='0'){
$pdf->Cell(96,5,'PRIMA DE JERARQUIA PENDIENTE '.date('Y')-1,0,0,'L');$pdf->Cell(33,5,$_SESSION['primajerarquiapendientependienteanterior'],0,0,'R');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['primaprofesionalpendientependienteanterior']!=''&&$_SESSION['primaprofesionalpendientependienteanterior']!='0,00'&&$_SESSION['primaprofesionalpendientependienteanterior']!='0'){
$pdf->Cell(96,5,'PRIMA PROFESIONALIZACION PENDIENTE '.date('Y')-1,0,0,'L');$pdf->Cell(33,5,$_SESSION['primaprofesionalpendientependienteanterior'],0,0,'R');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['primahogarpendientependienteanterior']!=''&&$_SESSION['primahogarpendientependienteanterior']!='0,00'&&$_SESSION['primahogarpendientependienteanterior']!='0'){
$pdf->Cell(96,5,'PRIMA HOGAR PENDIENTE '.date('Y')-1,0,0,'L');$pdf->Cell(33,5,$_SESSION['primahogarpendientependienteanterior'],0,0,'R');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['bonovacacional']!=''&&$_SESSION['bonovacacional']!='0,00'&&$_SESSION['bonovacacional']!='0'){
$pdf->Cell(96,5,'BONO VACACIONAL CAUSADO',0,0,'L');$pdf->Cell(33,5,$_SESSION['bonovacacional'],0,0,'R');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['bonovacacionalanterior']!=''&&$_SESSION['bonovacacionalanterior']!='0,00'&&$_SESSION['bonovacacionalanterior']!='0'){
$pdf->Cell(96,5,'BONO VACACIONAL '.date('Y')-1,0,0,'L');$pdf->$pdf->Cell(33,5,$_SESSION['bonovacacionalanterior'],0,0,'R');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['becaescolartercerpago']!=''&&$_SESSION['becaescolartercerpago']!='0,00'&&$_SESSION['becaescolartercerpago']!='0'){
$pdf->Cell(96,5,'BECA ESCOLAR',0,0,'L');$pdf->$pdf->Cell(33,5,$_SESSION['becaescolartercerpago'],0,0,'R');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['guarderia']!=''&&$_SESSION['guarderia']!='0,00'&&$_SESSION['guarderia']!='0'){
$pdf->Cell(96,5,'GUARDERIA',0,0,'L');$pdf->$pdf->Cell(33,5,$_SESSION['guarderia'],0,0,'R');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['becaescolartercerpagorezagados']!=''&&$_SESSION['becaescolartercerpagorezagados']!='0,00'&&$_SESSION['becaescolartercerpagorezagados']!='0'){
$pdf->Cell(96,5,'BECA ESCOLAR REZAGADOS',0,0,'L');$pdf->Cell(33,5,$_SESSION['becaescolartercerpagorezagados'],0,0,'R');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['diferenciainteresesfideicomiso']!=''&&$_SESSION['diferenciainteresesfideicomiso']!='0,00'&&$_SESSION['diferenciainteresesfideicomiso']!='0'){
$pdf->Cell(96,5,'DIF. INTERESES FIDEICOMISO '.date('Y')-1,0,0,'L');$pdf->Cell(33,5,$_SESSION['diferenciainteresesfideicomiso'],0,0,'R');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['diasadicionales']!=''&&$_SESSION['diasadicionales']!='0,00'&&$_SESSION['diasadicionales']!='0'){
$pdf->Cell(96,5,'DIAS ADICIONALES PRESTACIONES',0,0,'L');$pdf->Cell(33,5,$_SESSION['diasadicionales'],0,0,'R');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['deduccionessso']!=''&&$_SESSION['deduccionessso']!='0,00'&&$_SESSION['deduccionessso']!='0'){
$pdf->Cell(96,5,'SEGURO SOCIAL',0,0,'L');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'-'.$_SESSION['deduccionessso'],0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['deduccionespie']!=''&&$_SESSION['deduccionespie']!='0,00'&&$_SESSION['deduccionespie']!='0'){
$pdf->Cell(96,5,'DEDUCCIONES P.I.E.',0,0,'L');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'-'.$_SESSION['deduccionespie'],0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['deduccionesfaov']!=''&&$_SESSION['deduccionesfaov']!='0,00'&&$_SESSION['deduccionesfaov']!='0'){
$pdf->Cell(96,5,'FONDO DE AHORRO (FAOV)',0,0,'L');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'-'.$_SESSION['deduccionesfaov'],0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['islr']!=''&&$_SESSION['islr']!='0,00'&&$_SESSION['islr']!='0'){
$pdf->Cell(96,5,'ISLR',0,0,'L');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'-'.$_SESSION['islr'],0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['plandeahorroquincenal']!=''&&$_SESSION['plandeahorroquincenal']!='0,00'&&$_SESSION['plandeahorroquincenal']!='0'){
$pdf->Cell(96,5,'PLAN DE AHORRO',0,0,'L');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'-'.$_SESSION['plandeahorroquincenal'],0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['deduccionfjp']!=''&&$_SESSION['deduccionfjp']!='0,00'&&$_SESSION['deduccionfjp']!='0'){
$pdf->Cell(96,5,'FONDO DE JUBILACION Y PENSION',0,0,'L');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'-'.$_SESSION['deduccionfjp'],0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['sindicato']!=''&&$_SESSION['sindicato']!='0,00'&&$_SESSION['sindicato']!='0'){
$pdf->Cell(96,5,'SINUTRAFONTUR',0,0,'L');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'-'.$_SESSION['sindicato'],0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['deduccionprestamoplandeahorro']!=''&&$_SESSION['deduccionprestamoplandeahorro']!='0,00'&&$_SESSION['deduccionprestamoplandeahorro']!='0'){
$pdf->Cell(96,5,'PRESTAMO PLAN DE AHORRO',0,0,'L');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'-'.$_SESSION['deduccionprestamoplandeahorro'],0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['ajustesso']!=''&&$_SESSION['ajustesso']!='0,00'&&$_SESSION['ajustesso']!='0'){
$pdf->Cell(96,5,'AJUSTE S.S.O',0,0,'L');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'-'.$_SESSION['ajustesso'],0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['ajustepie']!=''&&$_SESSION['ajustepie']!='0,00'&&$_SESSION['ajustepie']!='0'){
$pdf->Cell(96,5,'AJUSTE P.I.E',0,0,'L');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'-'.$_SESSION['ajustepie'],0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['ajustefaov']!=''&&$_SESSION['ajustefaov']!='0,00'&&$_SESSION['ajustefaov']!='0'){
$pdf->Cell(96,5,'AJUSTE FAOV',0,0,'L');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'-'.$_SESSION['ajustefaov'],0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['ajustefjp']!=''&&$_SESSION['ajustefjp']!='0,00'&&$_SESSION['ajustefjp']!='0'){
$pdf->Cell(96,5,'AJUSTE F.J.P',0,0,'L');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'-'.$_SESSION['ajustefjp'],0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['ajustessoanterior']!=''&&$_SESSION['ajustessoanterior']!='0,00'&&$_SESSION['ajustessoanterior']!='0'){
$pdf->Cell(96,5,'AJUSTE S.S.O '.date('Y')-1,0,0,'L');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'-'.$_SESSION['ajustessoanterior'],0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['ajustepieanterior']!=''&&$_SESSION['ajustepieanterior']!='0,00'&&$_SESSION['ajustepieanterior']!='0'){
$pdf->Cell(96,5,'AJUSTE P.I.E '.date('Y')-1,0,0,'L');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'-'.$_SESSION['ajustepieanterior'],0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['ajustefaovanterior']!=''&&$_SESSION['ajustefaovanterior']!='0,00'&&$_SESSION['ajustefaovanterior']!='0'){
$pdf->Cell(96,5,'AJUSTE FAOV '.date('Y')-1,0,0,'L');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'-'.$_SESSION['ajustefaovanterior'],0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['ajustefjpanterior']!=''&&$_SESSION['ajustefjpanterior']!='0,00'&&$_SESSION['ajustefjpanterior']!='0'){
$pdf->Cell(96,5,'AJUSTE F.J.P '.date('Y')-1,0,0,'L');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'-'.$_SESSION['ajustefjpanterior'],0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['retencionjudicialquincenal']!=''&&$_SESSION['retencionjudicialquincenal']!='0,00'&&$_SESSION['retencionjudicialquincenal']!='0'){
$pdf->Cell(96,5,'EMBARGO (PENSION ALIMENTICIA)',0,0,'L');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'-'.$_SESSION['retencionjudicialquincenal'],0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['descuentodianolaborado']!=''&&$_SESSION['descuentodianolaborado']!='0,00'&&$_SESSION['descuentodianolaborado']!='0'){
$pdf->Cell(96,5,'DESCUENTO DIA NO LABORADO',0,0,'L');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'-'.$_SESSION['descuentodianolaborado'],0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
if($_SESSION['inavi']!=''&&$_SESSION['inavi']!='0,00'&&$_SESSION['inavi']!='0'){
$pdf->Cell(96,5,'INAVI',0,0,'L');$pdf->Cell(33,5,'',0,0,'R');$pdf->Cell(33,5,'-'.$_SESSION['inavi'],0,0,'R');$pdf->Cell(33,5,'',0,1,'R');
}
$pdf->Cell(96,5,'',1,0,'L');$pdf->Cell(33,5,$_SESSION['totalasignaciones'],1,0,'R');$pdf->Cell(33,5,'-'.$_SESSION['totaldeducciones'],1,0,'R'); $pdf->SetFont('Arial','B',10);$pdf->Cell(33,5,$_SESSION['totalacobrar'],1,1,'R');$pdf->SetFont('Arial','',10);
$pdf->Output();
session_destroy();
/*}*/
?>