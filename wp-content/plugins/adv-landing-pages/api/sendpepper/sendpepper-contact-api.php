<?php
// Construct contact data in XML format
$data_string = <<<STRING
<contact>
<Group_Tag name="Contact Information">
<field name="First Name">$name_field</field>
<field name="E-Mail">$email_field</field>
</Group_Tag>
<Group_Tag name="Sequences and Tags">
<field name="Contact Tags">$sendpepper_contact_tags</field>
</Group_Tag>
</contact>
STRING;

$data_string = urlencode(urlencode($data_string));

//Set your request type and construct the POST request
$reqType= "add";
$postargs = "appid=".$sendpepper_api_id."&key=".$sendpepper_api_key."&return_id=1&reqType=".$reqType. "&data=" . $data_string;
$request = "https://api.moon-ray.com/cdata.php";

//Start the curl session and send the data
$session_req = curl_init($request);
curl_setopt ($session_req, CURLOPT_POST, true);
curl_setopt ($session_req, CURLOPT_POSTFIELDS, $postargs);
curl_setopt($session_req, CURLOPT_HEADER, false);
curl_setopt($session_req, CURLOPT_RETURNTRANSFER, true);

//Store the response from the API for confirmation or to process data
$response_req = curl_exec($session_req);

//Close the session
curl_close($session_req);