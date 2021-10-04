<?php

/*
 * Ritorna i valori "Sì" o "No" in base al contenuto dell'argomento
 */
function true_false($arg1) {
    if($arg1 == "t")
	$arg1 = "Sì";
    else if ($arg1 == "f")
	$arg1 = "No";
    else if(strtolower($arg1) == "si")
	$arg1 = "t";
    else if(strtolower($arg1) == "no")
	$arg1 = "f";

    return $arg1;

}

/*
 * Prende in input un float (xxxx.yyy) e ritorna un numero formattato in
 * stile Euro (xxxx,yy)
 */
function formatta_euro($arg1) {
    $arg1 = number_format($arg1, 2, ',', '.');

    return $arg1;
}

/*
 * Il contrario della funzione precedente
 */
function formatta_float($arg1) {
    $arg1 = floatval(str_replace(',', '.', str_replace('.', '', $arg1)));

    return $arg1;
}

/*
 * Prende in input la data formattata da Postgres e la restituisce in formato
 * gg/mm/aaaa
 */
function converti_data($arg1) {
    if(!empty($arg1))
    	$arg1 = date("d/m/Y", strtotime($arg1));

    return $arg1;
}

/*
 * E' la funzione ucwords() rivista per lasciare gli articoli e le preposizioni
 * minuscole. Ottima per i nomi dei comuni, ad esempio
 */
function my_ucwords($s) {
        $a=strtolower($s);
        $s=ucfirst($a);
        for($x=0; $x<strlen($s)-1; $x++)
            if(!ctype_alpha($s[$x])) $s[$x+1]=strtoupper($s[$x+1]);

        //Lascia minuscoli articoli, preposizioni, congiunzioni
        $minuscole=array("il", "lo", "la", "i", "gli", "le",               
                 "un", "uno", "una",                        
                 "e",  "d", "l", "s", "un",                    
                 "di", "a", "da", "in", "con", "su", "per", "tra", "fra",
                 "del", "dello", "della", "dei", "degli", "delle", "dell'",
                 "a", "al", "allo", "alla", "ai", "agli", "alle", "all\'",
                 "da", "dal", "dallo", "dalla", "dai", "dagli", "dalle", "dall'",
                 "in", "nel", "nello", "nella", "nei", "negli", "nelle",
                 "con", "col", "collo", "colla", "coi", "cogli", "colle",
                 "su", "sul", "sullo", "sulla", "sui", "sugli", "sulle", "sull'",
                 "per", "pel", "pei");
        
        foreach($minuscole as $value)
            {
            $pos=strpos($a, $value);
            if( ( $pos>0 && $pos<strlen($s)-1 && !ctype_alpha($a[$pos-1]) && !ctype_alpha($a[$pos+1]) )
            ||  ( $pos==strlen($s)-1 && !ctype_alpha($a[$pos-1]) ) )           
                $s[$pos]=strtolower($s[$pos]);
            }

        return $s;
}

function my_ucwords_essential($s) {
        $a=strtolower($s);
        $s=ucfirst($a);
        for($x=0; $x<strlen($s)-1; $x++)
            if(!ctype_alpha($s[$x])) $s[$x+1]=strtoupper($s[$x+1]);

        return $s;
}

/*
 * Sostituisco le accentate apostrofate con le accentate reali
 */
function accentate_html($arg1, $qs = ENT_COMPAT, $charset = 'ISO-8859-1') 
{ 
    $search = array('ì', 'è', 'é', 'ò', 'à', 'ù'); 
    $replace = array('&igrave;', '&egrave;', '&eacute;', '&ograve;', '&agrave;', '&ugrave;'); 
     
    $arg1 = str_replace($search, $replace, $arg1); 
    $arg1 = htmlentities($arg1, $qs, $charset, false); 
     
	return $arg1;
}

function accentate_doc($arg1) {
	$arg1 = str_replace("&agrave;", "à", $arg1);
	$arg1 = str_replace("&egrave;", "è", $arg1);
	$arg1 = str_replace("&acute;", "é", $arg1);
	$arg1 = str_replace("&igrave;", "ì", $arg1);
	$arg1 = str_replace("&ograve;", "ò", $arg1);
	$arg1 = str_replace("&ugrave;", "ù", $arg1);

	return $arg1;
}

?>
