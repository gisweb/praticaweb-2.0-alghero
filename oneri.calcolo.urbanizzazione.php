<?php
//if(!defined("comune")) return;
//define("comune",true);
//require ("1_parametri.php");
require_once "login.php";
$db = new sql_db(DB_HOST.":".DB_PORT,DB_USER,DB_PWD,DB_NAME, false);
if(!$db->db_connect_id)  die( "Impossibile connettersi al database");

$pratica=$_REQUEST['pratica'];
$sql="SELECT numero,coalesce(data_prot,data_presentazione) as data from pe.avvioproc where pratica=$pratica";
$db->sql_query($sql);
$numero=$db->sql_fetchfield('numero');
//$dataoneri=$db->sql_fetchfield('data');
$dataoneri=date("d/m/Y");
$query="SELECT * FROM oneri.parametri where '$dataoneri'::date BETWEEN datein AND coalesce(dateed,CURRENT_DATE);";

$result=$db->sql_query($query);
//if(!$result){echo "SQL Error - ".mysql_error()."<br>".$query;return;}
$row = $db->sql_fetchrow($result);
$costo_base=$row['costo_base'];
$qbase  = $row['quota_base'];
$classe = $row['classe_comune'];
$quota= $row['corrispettivo'];
$delibera=$row['delibera'];
$oggi=date("d-m-Y");	

$sql="SELECT case when (not coalesce(piva,'')='') then coalesce(ragsoc,'') else coalesce(cognome,'')||' '||coalesce(nome,'') end as nominativo FROM pe.soggetti WHERE richiedente=1 and pratica=$pratica;";
$db->sql_query($sql);
$ris=$db->sql_fetchlist('nominativo');
$nominativi=implode('; ',$ris);
$sql="SELECT coalesce(via,'')||' '||coalesce(civico,'') as indirizzi FROM pe.indirizzi WHERE pratica=$pratica;";
$db->sql_query($sql);
$ris=$db->sql_fetchlist('indirizzi');
$indirizzi=implode('; ',$ris);

?>
<html>
	<head>
		<script src="../js/LoadLibs.js"></script>
        <script src="../js/java_albe.js"></script>
	
<script language="JavaScript">
	
	function check_int($field) {
		if (($field *1)+(1*1)==1) {	confirm('Campo Intestatario obbligatorio!!!');
									setTimeout(function() {document.autoForm.int.focus()}, 0);
									return false;	}
		
		
		$splitted=$field.split(' ');
		
		
		
		if ($splitted.length==1) {		
									confirm('Inserire nome e cognome!!!');
									
									
									document.autoForm.pe.value="";
									setTimeout(function() {document.autoForm.int.focus()}, 0);
									//document.autoForm.getElementById('pra').focus()
									return false;
								
								}
					
		
	
	}

	</script>


	
<link rel="stylesheet" type="text/css" href="../css/simple.css" />
<link rel="stylesheet" type="text/css" href="../css/theme.css" />
<script language="javascript" type="text/javascript">

function crea() {
var sendData={};
sendData['m1']=$('#pe').val();
sendData['m2']=$('#da_od').val();
sendData['m3']=$('#int').val();
sendData['m4']=$('#ind').val();
sendData['m5']=$('#n_alloggi1').val();
sendData['m6']=$('#n_alloggi2').val();
sendData['m7']=$('#n_alloggi3').val();
sendData['m8']=$('#n_alloggi4').val();
sendData['m9']=$('#n_alloggi5').val();
sendData['m10']=$('#su_1').val();
sendData['m11']=$('#su_2').val();
sendData['m12']=$('#su_3').val();
sendData['m13']=$('#su_4').val();
sendData['m14']=$('#su_5').val();
sendData['m27']=$('#snr_1').val();
sendData['m28']=$('#snr_2').val();
sendData['m29']=$('#snr_3').val();
sendData['m30']=$('#snr_4').val();
sendData['m42']=$('#zona').val();
sendData['m43']=$('#vol1').val();
sendData['m46']=$('#car1').val();
sendData['m47']=$('#car2').val();
sendData['m48']=$('#car3').val();
sendData['m49']=$('#car4').val();
sendData['m50']=$('#car5').val();
sendData['m67']=$('#sk_n_tab').val();
sendData['m68']=$('#sk_a_tab').val();
sendData['m72']=$('#c_base').val();
sendData['m75']=$('#costo_doc_1').val();
sendData['m77']=$('#costo_doc_2').val();
sendData['m79']=$('#costo_doc_3').val();
sendData['m80']=$('#pe').val();
sendData['m81']=$('#per_1').val();
sendData['m82']=$('#per_2').val();
sendData['m83']=$('#per_3').val();
sendData['m84']=$('#per_4').val();
sendData['m85']=$('#per_5').val();
sendData['m88']=$('#primaria').val();
sendData['m89']=$('#secondaria').val();
sendData['pratica']=<?php echo $pratica;?>;


$.ajax({
	url:'create_f.php',
	type:'POST',
	data: sendData,
	success:function(data, textStatus, jqXHR){
		$('#praticaFrm').submit();
	}
});
return;
//
//s = "m1="+m1 + "&m2="+m2 + "&m3="+m3 + "&m4="+m4 + "&m5="+m5 + "&m6="+m6 + "&m7="+m7 + "&m8="+m8 + "&m9="+m9;
//
//
//
//s = s + "&m10="+m10 + "&m11="+m11 + "&m12="+m12 + "&m13="+m13 + "&m14="+m14 + "&m27="+m27 + "&m28="+m28 + "&m29="+m29 + "&m30="+m30 + "&m42="+m42;					
//s = s + "&m43="+m43 + "&m46="+m46 + "&m47="+m47 + "&m48="+m48 + "&m49="+m49 + "&m50="+m50 + "&m67="+m67 + "&m68="+m68 + "&m72="+m72 + "&m75="+m75 + "&m77="+m77;					
//s = s + "&m79="+m79 + "&m80="+m80 + "&m81="+m81 + "&m82="+m82 + "&m83="+m83 + "&m84="+m84 + "&m85="+m85 + "&m88="+m88 + "&m89="+m89;										
//confirm(s);				

					
//					
//strURL='create_f.php?'+s;
//
//
//
//
//var xmlH = false;
//var self = this;
//
//if (window.XMLHttpRequest) {self.xmlH = new XMLHttpRequest();}
//else if (window.ActiveXObject) {self.xmlH = new ActiveXObject("Microsoft.XMLHTTP");}
//
//self.xmlH.open('GET', strURL, true);
//self.xmlH.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
//self.xmlH.send();
//
//
//
//
//
//
//
//
//
//
//
//self.xmlH.onreadystatechange = function() {		/*Gli stai di una richiesta possono essere 5
//												* 0 - UNINITIALIZED
//												* 1 - LOADING
//												* 2 - LOADED
//												* 3 - INTERACTIVE
//												* 4 - COMPLETE*/
//
//												//Se lo stato � completo 
//												if (self.xmlH.readyState == 4) {//alert(self.xmlHttpReq.responseText);
//																					aggiornaPagina_1(self.xmlH.responseText);}
//												/* Aggiorno la pagina con la risposta ritornata dalla precendete richiesta dal web server.Quando la richiesta � terminata il responso della richiesta � disponibie come responseText.*/
//
//
//												} 
//
//
//
//
//
//
////confirm(strURL);
}

//function aggiornaPagina_1(stringa){
//document.getElementById("link_down").innerHTML = stringa;
//}



</script>
<script language="javascript" type="text/javascript">
function startCalc_su(){
	//interval = setInterval("calc()",1);
	calc();
}
function round(numero){
	arrotondato=numero.toFixed(2);
	return arrotondato;
}
function calc(){
//////////////////////////////////////////////////////////////////////////////////////////////////
//						CALCOLO PRIMA TABELLA													//
//////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////
	//calcolo la somma della SU//
	/////////////////////////////
	one = $('#su_1').val();
	two = $('#su_2').val(); 
	tree = $('#su_3').val(); 
	four = $('#su_4').val(); 
	five = $('#su_5').val(); 
	sum_su = (one * 1) + (two * 1) + (tree * 1) +(four * 1) + (five * 1);
	sum_su=round(sum_su);
	$('#su_tot').val(sum_su);
	//////////////////////////////
	//calcolo i rapporti SU/STOT//
	//////////////////////////////
	r1= round(one / sum_su);
	r2= round(two / sum_su);
	r3= round(tree / sum_su);
	r4= round(four / sum_su);
	r5= round(five / sum_su);
	$('#r_1').val(r1);
	$('#r_2').val(r2);
	$('#r_3').val(r3);
	$('#r_4').val(r4);
	$('#r_5').val(r5);
	////////////////////////////////////////
	//calcolo % incremento sulle superfici//
	///////////////////////////////////////
	i1 = round (100 * 0 * r1);
	i2 = round (100 * 0.05 * r2);
	i3 = round (100 * 0.15 * r3);
	i4 = round (100 * 0.30 * r4);
	i5 = round (100 * 0.50 * r5);
	$('#i1').val(i1);
	$('#i2').val(i2);
	$('#i3').val(i3);
	$('#i4').val(i4);
	$('#i5').val(i5);
	////////////////////////////////////////
	//calcolo  incremento 1//
	///////////////////////////////////////
	if (sum_su==0)	{incremento1=round(0);}
	else {incremento1 = round((i1 * 1) + (i2 * 1) + (i3 * 1) + (i4 * 1) + (i5 * 1));}
	$('#incr1').val(incremento1);
		
////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////////
//						CALCOLO SECONDA TABELLA													//
//////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////
	//calcolo totale snr 1//
	///////////////////////////////////////
	snr_one = $('#snr_1').val();
	snr_two = $('#snr_2').val(); 
	snr_tree = $('#snr_3').val(); 
	snr_four = $('#snr_4').val(); 
	sum_snr = (snr_one * 1) + (snr_two * 1) + (snr_tree * 1) + (snr_four * 1);
	sum_snr=round(sum_snr);
	$('#snr_tot').val(sum_snr);
	////////////////////////////////////////
	//calcolo snr / su * 100//
	///////////////////////////////////////
	if (sum_su==0)	{
		snr_su=0;
	}
	else {
		snr_su=round(sum_snr/sum_su*100);
	}
	$('#snr_per').val(snr_su);
	////////////////////////////////////////
	//calcolo percentuale di incremento//
	///////////////////////////////////////
	if (snr_su<50) {
		incremento2=round(0);
	}
	else if (snr_su<75) {
		incremento2=round(10);
	}
	else if (snr_su<100) {
		incremento2=round(20);
	}
	else {
		incremento2=round(30);
	}
	
	$('#incr2').val(incremento2);
	
//////////////////////////////////////////////////////////////////////////////////////////////////
//						CALCOLO TERZA TABELLA													//
//////////////////////////////////////////////////////////////////////////////////////////////////


	//////////////////////////////////////////
	///incremento caratteristiche ricorrenti//
	//////////////////////////////////////////
	c_car1 = $('#car1').val();
	c_car2 = $('#car2').val();
	c_car3 = $('#car3').val();
	c_car4 = $('#car4').val();
	c_car5 = $('#car5').val();
	if (c_car1==1) {c_c1=10;}
	else {c_c1=0;}
	if (c_car2==1) {c_c2=10;}
	else {c_c2=0;}
	if (c_car3==1) {c_c3=10;}
	else {c_c3=0;}
	if (c_car4==1) {c_c4=10;}
	else {c_c4=0;}
	if (c_car5==1) {c_c5=10;}
	else {c_c5=0;}
	incremento3 = (c_c1 * 1) +  (c_c2 * 1) +(c_c3 * 1) +(c_c4 * 1) + (c_c5 * 1);
	$('#c1').val(c_c1);
	$('#c2').val(c_c2);
	$('#c3').val(c_c3);
	$('#c4').val(c_c4);
	$('#c5').val(c_c5);
	$('#incr3').val(incremento3);
	//////////////////////////////////////////////
	///incremento totale, classe e maggiorazione//
	//////////////////////////////////////////////
	incr_totale=(incremento1 *1) + (incremento2 * 1) +(incremento3 * 1);
	$('#incr_tot').val(incr_totale);

	if (incr_totale<5) {cla=1;mag=0;}
	if (incr_totale>=5 && incr_totale<10) {cla=2;mag=5;}
	if (incr_totale>=10 && incr_totale<15) {cla=3;mag=10;}
	if (incr_totale>=15 && incr_totale<20) {cla=4;mag=15;}
	if (incr_totale>=20 && incr_totale<25) {cla=5;mag=20;}
	if (incr_totale>=25 && incr_totale<30) {cla=6;mag=25;}
	if (incr_totale>=30 && incr_totale<35) {cla=7;mag=30;}
	if (incr_totale>=35 && incr_totale<40) {cla=8;mag=35;}
	if (incr_totale>=40 && incr_totale<45) {cla=9;mag=40;}
	if (incr_totale>=45 && incr_totale<50) {cla=10;mag=45;}
	if (incr_totale>=50) {cla=11;mag=50;}
	$('#classe').val(cla);
	$('#maggiorazione').val(mag);
	
	
	
	/////////////////////////////////////
	///calcolo superfici ragguagliate//
	////////////////////////////////////
	snr60=round(sum_snr*0.6);
	scomp=(sum_su*1)+(snr60*1);
	$('#s_tab').val(sum_su);
	$('#snr_tab').val(sum_snr);
	$('#snr60_1').val(snr60);
	scomp=round(scomp);
	$('#sc_1').val(scomp);
	///////////////////////////////////////
	///calcolo superfici non residenziali//
	//////////////////////////////////////
	sk_n = $('#sk_n_tab').val();
	sk_a = $('#sk_a_tab').val();
	sk_60 = (sk_a * 0.6);
	sk_60 = round(sk_60);
	sk_t=(sk_n * 1) + (sk_60 * 1);
	$('#sk_a60_1').val(sk_60);
	$('#sk_t_1').val(round(sk_t));
	//document.write(sum_su);
	///////////////
	///calcolo K//
	///////////////
	if (sum_su==0)	{c_k=0;}
	else {c_k = round( sk_t / sum_su *100);}
	$('#k_1').val(c_k);
	//////////////////////////////
	///calcolo costo costruzione//
	/////////////////////////////
	if (c_k <= 25) {
		costo_base = $('#c_base').val();
		costo_maggiorato =round ( costo_base * ( 1 + (mag/100)));
		costo=round((scomp + sk_t) * costo_maggiorato);
		$('#c_base_mag').val(costo_maggiorato);
		$('#costo_d').val(costo);
		avv="Poiche' k <= 25, il quadro sottostante non deve essere compilato."
		$('#avviso').val(avv);
		$('#costo_doc_1').val('0.00');
		$('#costo_doc_2').val("0.00");
		$('#contributo1').val("0.00");
		$('#contributo2').val("0.00");
	}
	else {
					//costo_base = 0;
		costo_maggiorato =round ( costo_base * ( 1 + (mag/100)));
		costo=round((scomp + sk_t) * costo_maggiorato);
		$('#c_base_mag').val(costo_maggiorato);
		$('#costo_d').val(costo);
		avv="N.B. - Poiche' k > 25 il costo di costruzione deve essere calcolato secondo lo schema che segue:"
		$('#avviso').val(avv);
		doc_1 = $('#costo_doc_1').val();
		doc_2 = $('#costo_doc_2').val();
		contr_1=round(doc_1*0.07);
		contr_2=round(doc_2*0.04);
		$('#contributo1').val(contr_1);
		$('#contributo2').val(contr_2);
	}
	doc_3 = $('#costo_doc_3').val();
	
	if (doc_3==""){
		$('#contributo3').val('0.00');
	}
	else{
		$('#contributo3').val(round(doc_3*0.05));
					}
					
	//////////////////////////////
	/// calcolo urbanizzazioni ///
	//////////////////////////////
	calc_primaria = $('#primaria').val();
	calc_secondaria = $('#secondaria').val();
	u1_u2=$('#zona').val();
	splitted=u1_u2.split(";");
	zona_urb=splitted [0];
	u1 = splitted [1];
	if(isNaN(u1) && u1!='N.A.') u1=/\((\d+(\.\d+)?)\)/.exec(u1)[1]; 

	u2=splitted[2];
	v1=$('#vol1').val();
	if (calc_primaria==1){
		ur1=round(u1*v1);
	}
	else{
		ur1=round(0);
	}
	if (calc_secondaria==1) {
		ur2=round(u2*v1);
	}
	else {
		ur2=0;
	}
	if (u1.slice(0,1)==0) {
		u1=u1.substr(1,4);
	}
	if (u2.slice(0,1)==0) {
		u2=u2.substr(1,4);
	}
	$('#u1').val(u1);
	$('#u2').val(u2);
	$('#urb1').val(ur1);
	$('#urb2').val(ur2);
	////////////////////////////////////////////
	///calcolo percentuale e costo costruzione//
	///////////////////////////////////////////
	p_1 = $('#per_1').val();
	p_2 = $('#per_2').val();				
	p_3 = $('#per_3').val();
	p_4 = $('#per_4').val();
	p_5 = $('#per_5').val();			
	p_t= (p_1 * 1) + (p_2 * 1) + (p_3 * 1) + (p_4 * 1) + (p_5 * 1)
	$('#per_tot').val(p_t);				
	costo_costruzione = (costo * p_t / 100);
	costo_costruzione = round (costo_costruzione);
	$('#oneri_costr').val(costo_costruzione);
	if (c_k <= 25)  {
		oneri_costruzione = costo_costruzione;
	}
	else {
		oneri_costruzione= round( (contr_1 * 1) + (contr_2 * 1) + (costo_costruzione *1));
	}
	$('#costruzione1').val(oneri_costruzione);
	urban_tot = round ((ur1 * 1) + (ur2 * 1));			
	$('#urbanizzazione1').val(urban_tot);
	$('#totale_oneri').val(round((urban_tot *1) + ( oneri_costruzione * 1)));
	//////////////////
	///calcolo rate//
	//////////////////
	r1 = (urban_tot * 0.5)  + (oneri_costruzione * 0.3);
	r2 = (urban_tot * 0.25) + (oneri_costruzione * 0.3);	
	r3 = (urban_tot * 0.25) + (oneri_costruzione * 0.4);						
	fides = (urban_tot *1) +  (oneri_costruzione * 1) - (r1	* 1);
	$('#rata1').val(round(r1));	
	$('#rata2').val(round(r2));
	$('#rata3').val(round(r3));
	$('#fide').val(round(fides));
}
function stopCalc_su(){
	//clearInterval(interval);
}
</script>
	</head>
	<body>
	<h4>DETERMINAZIONE DEGLI ONERI CONCESSORI NUOVE COSTRUZIONI</h4>
	<form id="autoForm" name="autoForm">
	<table width="635" >
	<tr><td colspan="7"><br></td><tr>
	<tr><td colspan="7" class="testost" align="center">Tabella Oneri Bucalossi</td></tr>
	
	
	
	
	<tr><td colspan="7"><br></td></tr>
	<tr>
	<td class="testost">P.E.</td>
	<td align="center" class="testost_odd_calc"><input size="10" class="testimp_odd_calc" id="pe" value="<?php echo $numero;?>" type="text" readonly="readonly"></td>
	<td colspan="2" align="right" class="testost">Intestatario</td>
	<td colspan="3" align="center" class="testost_odd_calc"><input size="45" class="testimp_odd_calc" id="int" value="<?php echo $nominativi;?>" readonly="readonly" type="text"></td>
	</tr>
	<tr>
	<td class="testost">Data</td>
		<td align="center" class="testost_odd_calc">
            <input size="10" value="<?php echo $oggi;?>" class="testimp_odd_calc" id="da_od" id="da_od" type="text">
            <script>
                $('#da_od').datepicker({
                    dateFormat:'dd-mm-yy',
                    changeMonth: true,
                    changeYear: true
                });
            </script>
    </td>
	<td colspan="2" align="right" class="testost">Indirizzo</td>
	<td colspan="3" align="center" class="testost_odd_calc"><input size="45" class="testimp_odd_calc" value="<?php echo $indirizzi;?>" id="ind" readonly="readonly" type="text"></td>
	</tr>
	<tr><td colspan="7"><br><br> </td></tr>
	<tr>
		<td width="70" align="center" class="testost">Classe di sup.</td>
		<td width="10" align="center" class="testost">Alloggi</td>
		<td width="10" align="center" class="testost">Sup. utile</td>
		<td width="10" align="center" class="testost">Rap. SU</td>
		<td width="5" align="center" class="testost">Incr. art. 5</td>
		<td width="10" align="center" class="testost">% incr </td>
		<td width="10" align="center" class="testost">Incremento </td>
	</tr>
	
	
	<tr>
		<td align="center" class="testost"> < 95        </td>
		<td align="center" class="testost_odd_calc"><input size="10" class="testimp_odd_calc" id="n_alloggi1" type="text"></td>
		<td align="center" class="testost_odd_calc"><input size="10" class="testimp_odd_calc" id="su_1" type="text" onKeyUp="calc();"></td>
		<td align="center" class="testost_odd"><input size="10" readonly="readonly" class="testimp_odd" id="r_1"> </td>
		<td align="center" class="testost_odd">0 </td>
		<td align="center" class="testost_odd"><input size="10" readonly="readonly" class="testimp_odd" id="i1"> </td>
	</tr>
	<tr>
		<td align="center" class="testost"> >95 <=110  </td>
		<td align="center" class="testost_even_calc"><input size="10" class="testimp_even_calc" align="center"  id="n_alloggi2" type="text"/></td>
		<td align="center" class="testost_even_calc"><input size="10" class="testimp_even_calc" id="su_2" type="text" onKeyUp="calc();"></td>
		<td align="center" class="testost_even"><input size="10" readonly="readonly" class="testimp_even" id="r_2"></td>
		<td align="center" class="testost_even">5 </td>
		<td align="center" class="testost_even"><input size="10" readonly="readonly" class="testimp_even" id="i2" </td>
	</tr>
	<tr>
		<td align="center" class="testost"> >110 <=130 </td>
		<td align="center" class="testost_odd_calc"><input size="10" class="testimp_odd_calc" align="center"  id="n_alloggi3" type="text"/></td>
		<td align="center" class="testost_odd_calc"><input size="10" class="testimp_odd_calc" id="su_3" type="text" onKeyUp="calc();"></td>
		<td align="center" class="testost_odd"><input size="10" readonly="readonly" class="testimp_odd" id="r_3"></td>
		<td align="center" class="testost_odd">15 </td>
		<td align="center" class="testost_odd"><input size="10" readonly="readonly" class="testimp_odd" id="i3" ></td>
	</tr>
	<tr>
		<td align="center" class="testost"> >130 <=160 </td>
		<td align="center" class="testost_even_calc"><input size="10" class="testimp_even_calc" align="center" size="5"  id="n_alloggi4" type="text"/></td>
		<td align="center" class="testost_even_calc"><input size="10" class="testimp_even_calc" id="su_4" type="text" onKeyUp="calc();"></td>
		<td align="center" class="testost_even"><input size="10" readonly="readonly" class="testimp_even" id="r_4"></td>
		<td align="center" class="testost_even">30 </td>
		<td align="center" class="testost_even"><input size="10" readonly="readonly" class="testimp_even" id="i4"></td>
	</tr>
	<tr>
		<td align="center" class="testost"> >160     </td>
		<td align="center" class="testost_odd_calc"><input size="10" class="testimp_odd_calc" align="center"  id="n_alloggi5" type="text"/></td>
		<td align="center" class="testost_odd_calc"><input size="10" class="testimp_odd_calc" id="su_5" type="text" onKeyUp="calc();"/></td>
		<td align="center" class="testost_odd"><input size="10" readonly="readonly" class="testimp_odd" id="r_5"></td>
		<td align="center" class="testost_odd">50 </td>
		<td align="center" class="testost_odd"><input size="10" readonly="readonly" class="testimp_odd" id="i5"></td>
	</tr>
	
	<tr>
		<td align="center" class="testost1"></td>
		<td align="center" class="testost_even">Totale SU</td>
		<td align="center" class="testost_even"><input size="10" class="testimp_even" id="su_tot" readonly="readonly" type="text"></td>
		<td align="center" class="testimp1"></td>
		<td align="center" class="testimp1"></td>
		<td align="right"  class="testost_even">i1</td>
		<td align="center" class="testost_even"><input size="10" class="testimp_even" id="incr1" readonly="readonly" type="text"></td>
	</tr>
	
	<tr><td colspan="7"><br><br> </td></tr>

	<tr>
		<td colspan="4" align="center" class="testost">Destinazioni</td>
		<td align="center" class="testost">Superfici (mq)</td>
	</tr>
	<tr>
		<td colspan="4" align="center" class="testost">Cantinole, soffitte, locali motore, ascensore, cabine idriche, lavatoi comuni, centrali termiche ed altri locali a stretto servizio delle residenze</td>
		<td align="center" class="testost_odd_calc"><input size="10" class="testimp_odd_calc" id="snr_1" type="text" onKeyUp="calc();"/></td>
	</tr>
	<tr>
		<td colspan="4" align="center" class="testost">Autorimesse</td>
		<td align="center" class="testost_even_calc"><input size="10" class="testimp_even_calc" id="snr_2" type="text" onKeyUp="calc();"/></td>
	</tr>
	<tr>
		<td colspan="4" align="center" class="testost">Androni d'ingresso e porticati liberi</td>
		<td align="center" class="testost_odd_calc"><input size="10" class="testimp_odd_calc" id="snr_3" type="text" onKeyUp="calc();"/></td>
	</tr>
	<tr>
		<td colspan="4" align="center" class="testost">Logge e balconi</td>
		<td align="center" class="testost_even_calc"><input size="10" class="testimp_even_calc" id="snr_4" type="text" onKeyUp="calc();"/></td>
	</tr>
	<tr>
		<td colspan="4" align="center" class="testost">Totale</td>
		<td align="center" class="testost_odd"><input size="10" class="testimp_odd" id="snr_tot" readonly="readonly" type="text"/></td>
	</tr>
	<tr>
		<td colspan="4" align="center" class="testost">(SNR/SU)*100</td>
		<td align="center" class="testimp_even"><input size="10" class="testimp_even" id="snr_per" readonly="readonly" type="text"/></td>
	</tr>	
	<tr>	
		<td align="center" ></td>
		<td align="center" ></td>
		<td align="center" ></td>
		<td align="center" ></td>
		<td align="center" ></td>
		
		<td align="right"  class="testost_odd">i2</td>
		<td align="center" class="testost_odd"><input size="10" class="testimp_odd" id="incr2" readonly="readonly" type="text"></td>
	
	</tr>
		<tr><td colspan="7"><br><br> </td></tr>
    		

	<tr>
		<td colspan="4" align="center" class="testost">Caratteristiche particolari</td>
		<td align="center" class="testost">Ipotesi</td>
		<td align="center" class="testost">%incremento</td>
	</tr>
	<tr>
		<td colspan="4" align="center" class="testost_odd">Piu'di un ascensore per ogni scala se questa serve meno di sei piani soprelevati</td>
		<td align="center" class="testost_odd_calc"> <select class="testimp_odd_calc" id="car1" onChange="calc();";>
													<option value="1" >Si</option>
													<option value="0" selected >No</option>
											</select>
		</td>
		<td align="center" class="testost_odd"><input class="testimp_odd" readonly="readonly" size="10" class="testimp" id="c1" type="text" ></td>
	</tr>
	<tr>
		<td colspan="4" align="center" class="testost_even">Scala di servizio non prescritta da leggi o regolamenti o imposta da necessita' di prevenzione di infortuni o di incendi</td>
		<td align="center" class="testost_even_calc"> <select class="testimp_even_calc" id="car2" onChange="calc();";>
													<option value="1" >Si</option>
													<option value="0" selected >No</option>
											</select>
		</td>
		<td align="center" class="testost_even"><input readonly="readonly" size="10" class="testimp_even" id="c2" type="text" ></td>
	</tr>
	<tr>
		<td colspan="4" align="center" class="testost_odd">Altezza libera netta di piano superiore a metri 3,00 o a quella minima prescritta da norme regolamentari. Per ambienti con altezze diverse si fa riferimento all'altezza media ponderale.</td>
		<td align="center" class="testost_odd_calc"> <select class="testimp_odd_calc" id="car3" onChange="calc();";>
													<option value="1" >Si</option>
													<option value="0" selected >No</option>
											</select>
		</td>
		<td align="center" class="testost_odd"><input readonly="readonly" size="10" class="testimp_odd" id="c3" type="text" ></td>
	</tr>
	<tr>
		<td colspan="4" align="center" class="testost_even">Piscina coperta o scoperta quando sia a servizio di uno o piu' edifici comprendenti meno di 15 unita' immobiliari.</td>
		<td align="center" class="testost_even_calc"> <select class="testimp_even_calc" class="testimp_odd" id="car4" onChange="calc();";>
													<option value="1" >Si</option>
													<option value="0" selected >No</option>
											</select>
		</td>
	<td align="center" class="testost_even"><input readonly="readonly" size="10" class="testimp_even" id="c4" type="text" ></td>
	</tr>
	<tr>
		<td colspan="4" align="center" class="testost_odd">Alloggi di custodia a servizio di uno o piu' edifici comprendenti meno di 15 unita' immobiliari.</td>
		<td align="center" class="testost_odd_calc"> <select class="testimp_odd_calc" id="car5" onChange="calc();";>
													<option value="1" >Si</option>
													<option value="0" selected >No</option>
											</select>
		</td>
		<td align="center" class="testost_odd"><input readonly="readonly" size="10" class="testimp_odd" id="c5" type="text" ></td>
	</tr>
	<tr>
	     <td colspan="5"></td>
		 <td align="right"  class="testost_even">i3</td>
		 <td align="center" class="testimp_even"><input size="10" class="testimp_even" id="incr3" readonly="readonly" type="text"></td>
	</tr>
	<tr><td colspan="7"><br><br></td></tr>
	<tr>
	     <td colspan="5"></td>
		 <td  align="center"  class="testost_odd">Totale degli incrementi</td>
	</tr>
	<tr>
	     <td colspan="5"></td>
		 <td align="right"  class="testost_even">i1 + i2 + i3 =</td>
		 <td align="center" class="testimp_even"><input size="10" class="testimp_even" id="incr_tot" readonly="readonly" type="text"></td>
	</tr>
	
	
	<tr><td colspan="7"><br></td></tr>
	<tr>
	     <td colspan="5"></td>
		 <td align="center"  class="testost_odd">Classe edificio</td>
		 <td align="center"  class="testost_odd">Maggiorazione %</td>
	</tr>
	<tr>
		 <td colspan="5"></td>
		 <td align="center" class="testimp_even"><input size="10" class="testimp_even" id="classe" readonly="readonly" type="text"></td>
		 <td align="center" class="testimp_even"><input size="10" class="testimp_even" id="maggiorazione" readonly="readonly" type="text"></td>
	</tr>
		
	<tr><td colspan="7" ><br><br></td></tr>
	<tr>
		<td colspan="3" align="center" class="testost">Superfici residenziali e relativi servizi ed accessori</td>
		<td></td>
		<td colspan="3" align="center" class="testost">Superfici per attivita' turistiche, commerciali, direzionali e relativi accessori</td>
	</tr>
	<tr>
		<td  align="center" class="testost">Sigla</td>
		<td  align="center" class="testost">Denominazione</td>
		<td  align="center" class="testost">Superficie (mq)</td>
		<td></td>
		<td  align="center" class="testost">Sigla</td>
		<td  align="center" class="testost">Denominazione</td>
		<td  align="center" class="testost">Superficie (mq)</td>
	</tr>
	<tr>
		<td  align="center" class="testost_odd">Su</td>
		<td  align="center" class="testost_odd">Superficie utile abitabile</td>
		<td  align="center" class="testost_odd"><input size="10" class="testimp_odd" id="s_tab" readonly="readonly" type="text"></td>
		<td></td>
		<td  align="center" class="testost_odd">Sn</td>
		<td  align="center" class="testost_odd">Superficie netta non residenziale</td>
		<td  align="center" class="testost_odd_calc"><input size="10" class="testimp_odd_calc" id="sk_n_tab" type="text" onKeyUp="calc();" ></td>
	</tr>
	<tr>
		<td  align="center" class="testost_even">Snr</td>
		<td  align="center" class="testost_even">Superficie netta non residenziale</td>
		<td  align="center" class="testost_even"><input size="10" class="testimp_even" id="snr_tab" readonly="readonly" type="text"></td>
		<td></td>
		<td  align="center" class="testost_even">Sa</td>
		<td  align="center" class="testost_even">Superficie accessori</td>
		<td  align="center" class="testost_even_calc"><input size="10" class="testimp_even_calc" id="sk_a_tab"  type="text" onKeyUp="calc();"></td>
	
	</tr>
	<tr>
		<td  align="center" class="testost_odd">60% Snr</td>
		<td  align="center" class="testost_odd">Superficie ragguagliata</td>
		<td  align="center" class="testost_odd"><input size="10" class="testimp_odd" id="snr60_1" readonly="readonly" type="text"></td>
		<td></td>
		<td  align="center" class="testost_odd">60% Sa</td>
		<td  align="center" class="testost_odd">Superficie ragguagliata</td>
		<td  align="center" class="testost_odd"><input size="10" class="testimp_odd" id="sk_a60_1" readonly="readonly" type="text"></td>
	
	
	</tr>
	<tr>
		<td  align="center" class="testost_even">Sc</td>
		<td  align="center" class="testost_even">Superficie complessiva</td>
		<td  align="center" class="testost_even"><input size="10" class="testimp_even" id="sc_1" readonly="readonly" type="text"></td>
		<td></td>
		<td  align="center" class="testost_even">St</td>
		<td  align="center" class="testost_even">Superficie totale non residenziale</td>
		<td  align="center" class="testost_even"><input size="10" class="testimp_even" id="sk_t_1" readonly="readonly" type="text"></td>
	</tr>
	
	
	
	<tr><td colspan="7"><br><br></td></tr>
	<tr>
		<td  colspan="6" align="center" class="testost_odd">Rapporto K fra la superficie destinata ad attivita' turistiche, commerciali, direzionali e relativi accessori ed Su:</td>
		<td  align="center" class="testost_odd"><input size="10" class="testimp_odd" id="k_1" readonly="readonly" type="text"></td>
	</tr>
	<tr><td colspan="7"><br><br></td></tr>
	<tr>
		<td  colspan="5" align="left" class="testost_odd">Costo massimo a mq. dell'edilizia agevolata Euro / mq</td>
		<td  colspan="2" align="center" class="testost_odd"><input size="10" class="testimp_odd" value="<?php echo $costo_base;?>" id="c_base" readonly="readonly" type="text"/></td>
	</tr>
	<tr>
		<td  colspan="5" align="left" class="testost_even">Costo a mq. di costruzione maggiorato Euro / mq</td>
		<td  colspan="2" align="center" class="testost_even"><input size="10" class="testimp_even" id="c_base_mag" readonly="readonly" type="text"/></td>
	</tr>
	<tr>
		<td  colspan="5" align="left" class="testost_odd">Costo D di costruzione Euro</td>
		<td  colspan="2" align="center" class="testost_odd"><input size="10" class="testimp_odd" id="costo_d" readonly="readonly" type="text"/></td>
	</tr>
	<tr><td colspan="7"><br><br></td></tr>
	

	<tr>
	<td  colspan="7" align="left" class="testost_odd"><input size="100" class="testimp_odd" id="avviso" readonly="readonly" type="text"></td>
	</tr>
	<tr><td colspan="7"><br><br></td></tr>
	<tr>
		<td  colspan="2" align="left" class="testost_odd">Costo di costruzione documentato zona F EURO</td>
		<td  align="center" class="testost_odd_calc"><input size="10" class="testimp_odd_calc" id="costo_doc_1" type="text" onKeyUp="calc();"/></td>
		<td  colspan="2" align="left" class="testost_odd">Contributo 7%</td>
		<td  colspan="1" align="right" class="testost_odd">Totale:</td>
		<td  align="center" class="testost_odd"><input size="10" class="testimp_odd" id="contributo1" readonly="readonly" type="text"/></td>
	</tr>
	<tr>
		<td  colspan="2" align="left" class="testost_even">Costo di costruzione documentato Aree urbane EURO</td>
		<td  align="center" class="testost_even_calc"><input size="10" class="testimp_even_calc" id="costo_doc_2"  type="text" onKeyUp="calc();"/></td>
		<td  colspan="2" align="left" class="testost_even">Contributo 4%</td>
		<td  colspan="1" align="right" class="testost_even">Totale:</td>
		<td  align="center" class="testost_even"><input size="10" class="testimp_even" id="contributo2" readonly="readonly" type="text"/></td>
	</tr>
	
	<tr><td colspan="7"><br><br></td></tr>
		<tr>
		<td  colspan="7" align="left" class="testost">Solo per interventi su edifici esistenti</td>
	</tr>
	
	<tr>
		<td  colspan="2" align="left" class="testost_odd">Costo di costruzione documentato</td>
		<td  align="center" class="testost_odd_calc"><input size="10" class="testimp_odd_calc" id="costo_doc_3" type="text" onKeyUp="calc();" /></td>
		<td  colspan="2" align="left" class="testost_odd">Contributo 5%</td>
		<td  colspan="1" align="right" class="testost_odd">Totale:</td>
		<td  align="center" class="testost_odd"><input size="10" class="testimp_odd" id="contributo3" readonly="readonly" type="text"/></td>
	</tr>
	<tr><td colspan="7"><br><br></td></tr>
	
	
	
	<tr>
		<td  colspan="7" align="left" class="testost">Determinazione del contributo sul costo di costruzione (Q) per le residenze</td>
	</tr>
	<tr>
		<td  colspan="2" align="left" class="testost_odd">Quota base</td>
		<td  colspan="4" align="left" class="testost_odd"></td>
		<td  align="center" class="testost_odd"><input size="8" class="testimp_odd" value="<?php echo $qbase;?>" id="per_1"  type="text" onKeyUp="calc();"/></td>
	</tr>
		<tr>
		<td  colspan="2" align="left" class="testost_even">Ubicazione</td>
		<td  colspan="4" align="left" class="testost_even">Classe 1</td>
		<td  align="center" class="testost_even"><input size="8" class="testimp_even" value="<?php echo $classe;?>" id="per_2"  type="text" onKeyUp="calc();"/></td>
	</tr>
	</tr>
		<tr>
		<td  colspan="2" align="left" class="testost_odd">Caratteristica</td>
		<td  colspan="4" align="left" class="testost_odd">lusso 2%  --  medio 0,5%  --  economico popolare 0%</td>
		<td  align="center" class="testost_odd_calc"><select class="testimp_odd_calc" id="per_3" onChange="calc();";>
													<option value="2" > 2 %</option>
													<option value="0.5" selected > 0.5 %</option>
													<option value="0" selected > 0 %</option>
											</select>
		</td>
	</tr>
	</tr>
		<tr>
		<td  colspan="2" align="left" class="testost_even">Ubicazione</td>
		<td  colspan="4" align="left" class="testost_even">ville mono-plurifamiliari 2%  --  medio 0,5%  --  edifici a torre in linea, a schiera e tipologie tradizionali dei centri rurali sardi 0%</td>
		<td  align="center" class="testost_even_calc"><select class="testimp_even_calc" id="per_4" onChange="calc();">
													<option value="2" > 2 %</option>>
													<option value="0.5" selected > 0.5 %</option>
													<option value="0" selected > 0 %</option>
											</select>
		</td>
	</tr>
	<tr>
		<td  colspan="2" align="left" class="testost_odd">Destinazione</td>
		<td  colspan="4" align="left" class="testost_odd">Zone A-B 0%  --  C-D 0,5%  --  E 2%  --  F 2%</td>
		<td  align="center" class="testimp_odd_calc"><select class="testimp_odd_calc" id="per_5" onChange="calc();";>
													<option value="2" > 2 %</option>>
													<option value="0.5" selected > 0.5 %</option>
													<option value="0" selected > 0 %</option>
											</select>
		</td>
	</tr>
	<tr>
		<td  colspan="6" align="right" class="testost_even">Percentuale P</td>
		<td  align="center" class="testost_even"><input size="8" class="testimp_even" id="per_tot" readonly="readonly"/></td>
	</tr>
	<tr><td colspan="7"><br></td></tr>
	<tr>
		<td  colspan="6" align="right" class="testost_odd">Oneri costo di costruzione </td>
		<td  align="center" class="testost_odd"><input size="10" class="testimp_odd" id="oneri_costr" readonly="readonly"/></td>
	</tr>
    <tr><td colspan="7"><br></td></tr>
	<tr>
		<td  colspan="6" align="left" class="testost">Determinazione oneri di urbanizzazione</td>
	</tr>
	<tr>
	<td colspan="2" align="center" class="testost_even">Zona urbanistica di P.R.G.</td>
	<td align="center" class="testost_even_calc">

	<select class="testimp_even_calc" id="zona" onChange="calc();">


	<?php
	$query="SELECT zona, c_1, c_2 FROM oneri.tabella_b ORDER BY id";
	$result=$db->sql_query($query);
    //echo $sql;

	while ($row = $db->sql_fetchrow($result)) 
		{
								$zona=$row['zona'];
								$u1=$row['c_1'];
								$u2=$row['c_2'];
								?> 
								
								<option value="<?php echo $zona.';'.$u1.';'.$u2;?>"><?php echo $zona;?></option>
															
								<?php
								
			
		
		}
	
	?>
	</select>
	</td>
		<td colspan="2" align="center" class="testost_even">Volumetria in progetto residenziale (mc)</td>
		<td align="center" class="testost_even_calc"><input size="10" class="testimp_even_calc" id="vol1" type="text" onKeyUp="startCalc_su();"></td>
	
	</tr>
			
	<tr>
		<td colspan="2" align="center" class="testost_odd">ONERI URBANIZZAZIONE PRIMARIA</td>
		<td align="center" class="testost_odd_calc"> <select class="testimp_odd_calc" id="primaria" onChange="calc();">
													<option value="1" selected >Si</option>>
													<option value="0" >No</option>
											</select>
		</td>
		<td colspan="2" align="center" class="testost_odd">ONERI URBANIZZAZIONE SECONDARIA</td>
		<td align="center" class="testost_odd_calc"> <select class="testimp_odd_calc" id="secondaria" onChange="calc();"";>
													<option value="1" selected >Si</option>>
													<option value="0" >No</option>
											</select>
		</td>
	</tr>	
	
	<tr>	
		<td colspan="2" align="center" class="testost_odd">Oneri U1 (Euro/mc)</td>
		<td align="center" class="testost_odd_calc"><input size="10" readonly="readonly" class="testimp_odd_calc" id="u1" type="text" onChange="calc();"/></td>
		<td colspan="2" align="center" class="testost_odd">Oneri U2 (Euro/mc)</td>
		<td align="center" class="testost_odd_calc"><input readonly="readonly" size="10" class="testimp_odd_calc" id="u2" type="text" onChange="calc();"/></td>
	
	</tr>	
	<tr>	
		<td colspan="2" align="center" class="testost_even">Urb. primaria (Euro)</td>
		<td align="center" class="testost_even_calc"><input size="10" class="testimp_even_calc" readonly="readonly" id="urb1" type="text" onChange="calc();"/></td>
		<td colspan="2" align="center" class="testost_even">Urb. Secondaria (Euro)</td>
		<td align="center" class="testost_even_calc"><input readonly="readonly" size="10" class="testimp_even_calc" id="urb2" type="text" onChange="calc();"/></td>
	</tr>
	<tr><td colspan="7"><br><br></td></tr>
	<tr>	
		<td colspan="2" align="center" class="testost_odd">Oneri costo di costruzione</td>
		<td align="center" class="testost_odd"><input size="10" class="testimp_odd" readonly="readonly" id="costruzione1" type="text" readonly="readonly"></td>
		<td colspan="1" align="center" class="testost_odd">Rata 1</td>
		<td align="center" class="testost_odd"><input size="10" class="testimp_odd" readonly="readonly" id="rata1" type="text" readonly="readonly"></td>
		<td colspan="1align="center" class="testost_odd">Fidejussione</td>
		<td align="center" class="testost_odd"><input size="10" class="testimp_odd" readonly="readonly" id="fide" type="text" readonly="readonly"></td>
	</tr>
	<tr>	
		<td colspan="2" align="center" class="testost_even">Oneri costo di urbanizzazione</td>
		<td align="center" class="testost_even"><input size="10" class="testimp_even" readonly="readonly" id="urbanizzazione1" type="text" readonly="readonly"></td>
		<td colspan="1" align="center" class="testost_even">Rata 2</td>
		<td align="center" class="testost_even"><input size="10" class="testimp_even" readonly="readonly" id="rata2" type="text" readonly="readonly"></td>
		<td colspan="2"></td>
	</tr>
	<tr>	
		<td colspan="2" align="center" class="testost_odd">Totale</td>
		<td align="center" class="testost_odd"><input size="10" class="testimp_odd" readonly="readonly" id="totale_oneri" type="text" readonly="readonly"></td>
		<td colspan="1" align="center" class="testost_odd">Rata 3</td>
		<td align="center" class="testost_odd"><input size="10" class="testimp_odd" readonly="readonly" id="rata3" type="text" readonly="readonly"></td>
	</tr>
	<tr><td colspan="7"><br><br></td></tr>
	
	<!--<tr><td colspan="2"><input class="blueButton" type="button" value="Crea File" onClick="crea();" onFocus="startCalc_su();"></td><td colspan="3"><div id="link_down">Qui apparira' il link</div></td></tr>-->
	<tr>
		<td colspan="2">
			<div id="back_btn"></div>
			<div id="print_btn"></div>
			<script>
				$('#back_btn').button({
					'label':'Indietro',
					'icons':{
						'primary':''
					}
				}).click(function(){
					$('#praticaFrm').submit();
				});
				$('#print_btn').button({
					'label':'Salva',
					'icons':{
						'primary':'ui-icon-disk'
					}
				}).click(function(){
					crea();
				});
			</script>
		
		</td>
	</tr>
	<?php
	//<tr><td colspan="7"></td></tr>
	//<tr><td></td><td><input  type="submit" value="Download File" onClick="return (confirm ('sei sicuro?'));"></td><td><div id="link_down"></div></td></tr>
	
	//$folder2="../images/icone/";
	//$ico_down=$folder2."download.ico";
	//$link="create_file.php";	
	//$link="down.php?code=".$row['downcode'];						
	//echo '<tr><td align="center" class="testost_even"  colspan="2"><img  src="'.$ico_down.'" align="bottom"</img>DOWNLOAD<a href="'.$link.'"> HERE</a></td></tr>';
	//echo '<tr><td align="right"  colspan="1"><img  src="'.$ico_down.'" align="bottom"</img></td><td align="left">DOWNLOAD<a href="'.$link.'"> QUI</a></td></tr>';
	//echo '<tr><td colspan="7"></td></tr>';

	?>
</table>
	</form>
	<form id="praticaFrm" action="praticaweb.php" method="POST">
		<input id="pratica" name="pratica" type="hidden" value="<?php echo $pratica?>"/>
		<input id="mode" name="mode" type="hidden" value="view"/>
		<input id="azione" name="azione" type="hidden" value="Annulla"/>
		<input id="active_form" name="active_form" type="hidden" value="oneri.importi.php"/>
	</form>
</body>
</html>
	
	<?php
	/*
	onclick="showProgress()"
	
	
	
	<input type=text name="firstBox" value="" onFocus="startCalc();" onBlur="stopCalc();"> 
	<input type=text name="secondBox" value="" onFocus="startCalc();" onBlur="stopCalc();">
	<input type=text name="thirdBox">
	</form>
	*/

	
	
	
	

function datechange ($date)
	{
	$year=substr ($date,0,4);
	$month=substr($date,5,2);
	$day=substr($date,8,2);
	$moddate=$day.'-'.$month.'-'.$year;
	return $moddate;
	}
function downcode()
	{
	$alfa=array('A','B','C','D','E','F','G','H','I','L','M','N','O','P','Q','R','S','T','U','V','Z');
	$code_down=mktime();
	$rand=rand(10,50);
	$rand_alfa1=rand(0,count($alfa)-1);
	$rand_alfa2=rand(0,count($alfa)-1);
	echo $code_down.' '. $rand;
	$code_down=$alfa[$rand_alfa1].floor($code[$i]/$rand).$alfa[$rand_alfa2];
	return $code_down;
	}
	
?>
