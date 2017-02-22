<?php
class dbRecord{
	var $idxreclen;
	var $id;
	var $len;
	var $ptr;
	var $handle;
	var $idxhandle;
	var $recs;
	var $pos;
	var $reclen;
	var $filepos;
	
	function __construct($name)
	{
// open and close data and index files to make sure they exist
		$this->handle = fopen ($name.".dat", "a+");
		$this->idxhandle = fopen($name.".idx", "a+");
		fclose($this->handle);
		fclose($this->idxhandle);

// open index file
		$this->idxreclen = 33;
		$this->idxhandle = fopen($name.".idx", "r+");

		fseek($this->idxhandle, 0, SEEK_END);
		$pos = ftell($this->idxhandle);
		if ($pos == 0)
		{
			$this->recs = 0;
			$this->UpdateRecordCount();
		}
		else
		{
			rewind($this->idxhandle);
			$this->recs = (int)fread($this->idxhandle, $this->idxreclen);
		}

		$this->handle = fopen ($name.".dat", "r+");
		$this->pos = 0;
	}
	
	function __destruct() {
		$this->Close();
	}

	function Write($obj, $id=0)
	{
		$record = serialize($obj);
		if ($id > 0)
		{
			$pos = $this->idxreclen * $id;
			$this->ReadIdx($id);

			fseek($this->handle, 0, SEEK_END);
			$rpos = ftell($this->handle);
			fwrite($this->handle, $record);
			$this->WriteIdx($id, $rpos, strlen($record));
		}
		else
		{
			$this->recs++;
			$id = $this->recs;

			fseek($this->handle, 0, SEEK_END);
			$rpos = ftell($this->handle);
			fwrite($this->handle, $record);
			$this->WriteIdx($id, $rpos, strlen($record));
			$this->UpdateRecordCount();
		}
		$this->id = $id;
		return $id;
	}
	function Close()
	{
		fclose($this->idxhandle);
		fclose($this->handle);
	}
	function Read($id)
	{
		$this->ReadIdx($id);
		fseek($this->handle, $this->filepos, SEEK_SET);
		$rec = fread($this->handle, $this->reclen);
		$obj = unserialize($rec);
		return $obj;
	}
	function ReadIdx($id)
	{
		$pos = $this->idxreclen * $id;
		fseek ($this->idxhandle, $pos, SEEK_SET);
		$idxrec = fread($this->idxhandle, $this->idxreclen);
		$idx = (int)substr($idxrec,0,11);
		if ($idx != $id)
		{
			echo "<p> error - id doesnt match<p>";
		}
		$this->filepos = (int)substr($idxrec,11,11);
		$this->reclen = (int)substr($idxrec,22,11);
		$this->pos = $pos;
	}
	
	function ReadNext($first = false)
	{
		if ($this->recs == 0)
		{
			Trace('No Records');
			return false;
		}
		if ($first)
		{
			$this->pos = 0;
		}
	
		$this->pos += $this->idxreclen;
		fseek($this->idxhandle, $this->pos, SEEK_SET);
		$str = fread($this->idxhandle, $this->idxreclen);
		if ($str)
		{
			$this->idx = (int)substr($str,0,11);
			$this->filepos = (int)substr($str,11,11);
			$this->reclen = (int)substr($str,22,11);
			
		trace ('pos = ' . $this->filepos);
		trace ('idx = ' . $this->idx);
					
			if ($this->reclen == 0)
			{
				return ($this->ReadNext());
			}
			fseek($this->handle, $this->filepos, SEEK_SET);
			$rec = fread($this->handle, $this->reclen);
			$obj = unserialize($rec);
			return $obj;
		}
		else
		{
			Trace("eof");
		}
		return false;
	}
		
	function WriteIdx($id, $pos, $len)
	{
		$idxpos = $this->idxreclen * $id;	
		fseek($this->idxhandle, $idxpos, SEEK_SET);
		$idxrec = sprintf("%11s%11s%11s", $id, $pos, $len);
		fwrite($this->idxhandle, $idxrec);
	}
	function UpdateRecordCount()
	{
		fseek($this->idxhandle, 0, SEEK_SET);
		$idxrec = sprintf("%33s", $this->recs);
		fwrite($this->idxhandle, $idxrec);
	}
	function Delete($id)
	{
		$pos = $this->filepos;
		$len = $this->reclen;
		$id = $this->id;
		
		$this->ReadIdx($id);
		$this->WriteIdx($id, $this->filepos, 0);
		$this->recs--;
		$this->UpdateRecordCount();
		
		$this->filepos = $pos;
		$this->reclen = $len;
		$this->id = $id;		
	}
	
	function LastID() {
		return $this->id;
	}
	
}

function Trace($str)
{
	echo "<p>$str</p>";
}

$prec = new dbRecord('places');
echo '<p>' . $prec->recs . ' records</p>';

for ($i=0;$i<$prec->recs;$i++)
{
	$p = $prec->ReadNext();
	var_dump($p);
}

