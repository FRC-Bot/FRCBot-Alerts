<?php
 
 // This part receive Json match informations from a TheBlueAlliance Webhook and save it in a text variable
 $input = @file_get_contents("php://input");


// decode from json to table
 $jsoninput = json_decode($input);

 //$type = $jsoninput->{'message_type'}; // Make sure this webhook is about a match_score
 $evkey = $jsoninput->{'message_data'}->{'match'}->{'event_key'}; // Gets the event key
 $complvl = $jsoninput->{'message_data'}->{'match'}->{'comp_level'}; // Gets the competition level EX: Q, F, ...
 $matchnb = $jsoninput->{'message_data'}->{'match'}->{'match_number'}; // Gets the match number
 $setnb = $jsoninput->{'message_data'}->{'match'}->{'set_number'}; // Gets the number of the match
 
 //blue
 $scoreblue = $jsoninput->{'message_data'}->{'match'}->{'alliances'}->{'blue'}->{'score'}; Gets the score for blue
 $bluet1 = $jsoninput->{'message_data'}->{'match'}->{'alliances'}->{'blue'}->{'teams'}[0]; Gets the team numbers in this alliance
 $bluet2 = $jsoninput->{'message_data'}->{'match'}->{'alliances'}->{'blue'}->{'teams'}[1]; Gets the team numbers in this alliance
 $bluet3 = $jsoninput->{'message_data'}->{'match'}->{'alliances'}->{'blue'}->{'teams'}[2]; Gets the team numbers in this alliance
 
 //red
 $scorered = $jsoninput->{'message_data'}->{'match'}->{'alliances'}->{'red'}->{'score'}; Gets the score for red
 $redt1 = $jsoninput->{'message_data'}->{'match'}->{'alliances'}->{'red'}->{'teams'}[0]; Gets the team numbers in this alliance
 $redt2 = $jsoninput->{'message_data'}->{'match'}->{'alliances'}->{'red'}->{'teams'}[1]; Gets the team numbers in this alliance
 $redt3 = $jsoninput->{'message_data'}->{'match'}->{'alliances'}->{'red'}->{'teams'}[2]; Gets the team numbers in this alliance
 
 //Write the text that will be tweeted
 $answer =  "Match result for #{$evkey} {$complvl}{$setnb}m{$matchnb}. Score Blue: {$scoreblue}pts by {$bluet1}, {$bluet2} and {$bluet3}. Red: {$scorered}pts by {$redt1}, {$redt2} and {$redt3}";
 
 error_log($answer); // Dump the answer in the error log. 

 
 $sentdata = [ 'value1' => $answer ];
 
 
 // This part sends a tweet to @FRCBotAlerts on twitter I am doing it with IFTTT beacause it is simpler!
 $ch = curl_init('https://maker.ifttt.com/trigger/tweetnotif/with/key/(ifttt private key)');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($sentdata));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_exec($ch);
curl_close($ch);
?>
