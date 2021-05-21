<?php
    /** fichier		 : api/Payload.class.php
	    description  : Class pour effectuer les conversions du payload
	    author       : Philippe SIMIER Lycée Touchard Le Mans
		
	**/
	
class Payload{


	
	function __construct($val) {
        $this->strHex = $val;
    }


    /** 
	 * Methode pour convertir une chaine hexa 4 octets en float
	 * @param  string endianness false  -> "big_endian" true -> little_endian
	 * @return float
	 */
	
	public function to_float( $endianness=false ) {
		
		$hex = sscanf($this->strHex, "%02x%02x%02x%02x");
		$binarydata = implode('', array_map('chr', $hex));
		if ($endianness === false)
			$array = unpack("G", $binarydata);
		else
			$array = unpack("g", $binarydata);  // little-endian

		return $array[1];
	}
	
	/** 
	 * Methode pour convertir une chaine hexa 1 octets en int8
	 * @return int8
	 */
	
	public function to_int8() {
		
		$hex = sscanf($this->strHex, "%02x");
		$binarydata = implode('', array_map('chr', $hex));
		return unpack("c", $binarydata)[1];

	}
	
	/** 
	 * Methode pour convertir une chaine hexa 1 octets en uint8
	 * @return uint8
	 */
	
	public function to_uint8() {
		
		$hex = sscanf($this->strHex, "%02x");
		$binarydata = implode('', array_map('chr', $hex));
		return unpack("C", $binarydata)[1];

	}

    /** 
	 * Methode pour convertir une chaine hexa 2 octets en int16
	 * @param  string endianness false  -> "big_endian" true -> little_endian
	 * @return uint16
	 */
	
	public function to_int16($endianness=false) {
		
		$hex = sscanf($this->strHex, "%02x%02x");
		$binarydata = implode('', array_map('chr', $hex));
		if ($endianness === false)
			return unpack("s", $binarydata)[1];
		else
			return unpack("S", $binarydata)[1];
    
	}
	
	/** 
	 * Methode pour convertir une chaine hexa 2 octets en uint16
	 * @param  string endianness false  -> "big_endian" true -> little_endian
	 * @return uint16
	 */
	
	public function to_uint16($endianness=false) {
		
		$hex = sscanf($this->strHex, "%02x%02x");
		$binarydata = implode('', array_map('chr', $hex));
		if ($endianness === false)
			return unpack("n", $binarydata)[1];
		else
			return unpack("v", $binarydata)[1];
    
	}
	
	/** 
	 * Methode pour convertir une chaine hexa 4 octets en int32
	 * @param  string endianness false  -> "big_endian" true -> little_endian
	 * @return uint16
	 */
	
	public function to_int32($endianness=false) {
		
		$hex = sscanf($this->strHex, "%02x%02x%02x%02x");
		$binarydata = implode('', array_map('chr', $hex));
		if ($endianness === false)
			return unpack("N", $binarydata)[1];
		else
			return unpack("V", $binarydata)[1];
    
	}
	
	

	// déclaration des propriétés
    private $strHex;




}