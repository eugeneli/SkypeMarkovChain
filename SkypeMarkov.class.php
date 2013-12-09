<?php
class SkypeMarkov
{
	private $chain = array();
	private $names = array();
	private $text;

	public function __construct($textFile)
	{
		$fileHandler = fopen($textFile, "r");
		while (($line = fgets($fileHandler)) !== false)
		{
			$newLine = "";

			//Remove time stamp
			if(substr($line, 0, 1) == "[")
			{
				$endBracketPos = strpos($line, "]");
				$newLine .= substr($line, $endBracketPos+1);
			}

			//Get and remove names
			$colonPos = strpos($newLine, ":");
			array_push($this->names, substr($newLine, 0, $colonPos));
			$this->text .= substr($newLine, $colonPos+1);
		}

		$this->createChain();
	}

	private function createChain()
	{
		$words = explode(" ", $this->text);	
		for($i = 0; $i < count($words); $i++)
		{
			$word = $words[$i];
			if($i+1 < count($words))
				$nextWord = $words[$i + 1];
			$this->chain[$word][] = $nextWord;
		}
	}

	public function generate($numWords)
	{
		$randName = array_rand($this->names);
		$output = "<span style='font-weight:bold;font-size:13pt'>". $this->names[$randName] .":</span> ";
		
		$nextWord = array_rand($this->chain);

		for($i = 0; $i < $numWords; $i++)
		{
			$output .= $nextWord ." ";
			$nextWord = $this->chain[$nextWord][array_rand($this->chain[$nextWord])];
		}
		return $output;
	}
}
?>