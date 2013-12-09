<?php
class SkypeMarkov
{
	private $chain = array();
	private $names = array();
	private $text;

	public function __construct($textFile, $order=1)
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
		$this->text = strtolower($this->text);
		$this->createChain($order);
	}

	private function createChain($order)
	{
		$words = explode(" ", $this->text);

		$currentWord = "";
		//Get first word of chain
		for($i = 0; $i <= $order-1; $i++)
			$currentWord .= " ". $words[$i];

		//Build rest of chain
		for($i = $order; $i < count($words); $i++)
		{
			$nextWord = "";

			for($c = $i; $c <= $i+$order-1; $c++)
			{
				if($c < count($words))
					$nextWord .= " ". $words[$c];
			}
			$i += $order;

			$this->chain[$currentWord][] = $nextWord;
			$currentWord = $nextWord;
		}
	}

	public function generate($numWords)
	{
		$randName = array_rand($this->names);
		$name = "<span style='font-weight:bold;font-size:13pt'>". $this->names[$randName] .":</span> ";
		$output = "";
		
		$nextWord = array_rand($this->chain);

		for($i = 0; $i < $numWords; $i++)
		{
			$output .= $nextWord ." ";
			$nextWord = $this->chain[$nextWord][array_rand($this->chain[$nextWord])];
		}
		return $name . ucfirst(trim($output));
	}
}
?>