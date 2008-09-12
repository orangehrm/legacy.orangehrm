<?php
class Test{

	public function TestMe(){
		sleep(1); 
		return true ;
	
	}

}

$obj = new Test();
echo $obj->TestMe();
?>