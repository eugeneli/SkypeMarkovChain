<?php
//Example index.php

include("SkypeMarkov.class.php");

if(!isset($_SESSION['markov']))
{
	$markov = new SkypeMarkov("Chatlog.txt", true);
	$_SESSION['markov'] = $markov;
}
else
	$markov = $_SESSION['markov'];

echo $markov->generate(mt_rand(5,20));
?>