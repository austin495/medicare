<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    function prepareFormData($postData, $keys) {
        $data = [];
        foreach ($keys as $key) {
            $data[$key] = htmlspecialchars(trim($postData[$key] ?? ''));
        }
        return $data;
    }

    // $trackDriveData = [
    //     'lead_token' => $_POST['lead_token'],
    //     'traffic_source_id' => $_POST['traffic_source_id'],
    //     'caller_id' => $_POST['caller_id'],
    //     'first_name' => $_POST['first_name'],
    //     'last_name' => $_POST['last_name'],
    //     'email' => $_POST['email'],
    //     'dob' => $_POST['dob'],
    //     'state' => $_POST['state'],
    //     'city' => $_POST['city'],
    //     'zip' => $_POST['zip'],
    //     'source_url' => $_POST['source_url'],
    //     'ip_address' => $_POST['ip_address'],
    //     'original_lead_submit_date' => $_POST['original_lead_submit_date'],
    //     'trusted_form_cert_url' => $_POST['trusted_form_cert_url'],
    //     'jornaya_leadid' => $_POST['jornaya_leadid'],
    // ];

    // $trackDriveUrl = "https://evolvetech-innovations.trackdrive.com/api/v1/leads";
    // $ch = curl_init($trackDriveUrl);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($ch, CURLOPT_POST, true);
    // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($trackDriveData));
    // curl_setopt($ch, CURLOPT_HTTPHEADER, [
    //     'Content-Type: application/x-www-form-urlencoded',
    // ]);

    // $trackDriveResponse = curl_exec($ch);
    // $trackDriveHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    // $curlError = curl_error($ch);
    // curl_close($ch);

    // if ($curlError) {
    //     error_log("TrackDrive cURL Error: $curlError");
    //     echo json_encode(['status' => 'error', 'message' => "TrackDrive cURL Error: $curlError"]);
    //     exit;
    // }

    // if ($trackDriveHttpCode === 200) {
    //     $responseMessage = 'Existing Lead Modified';
    //     echo json_encode(['status' => 'success', 'message' => $responseMessage]);
    // } elseif ($trackDriveHttpCode === 201) {
    //     $responseMessage = 'New Lead Submitted';
    //     echo json_encode(['status' => 'success', 'message' => $responseMessage]);
    // } elseif ($trackDriveHttpCode === 422) {
    //     $responseMessage = 'DNC Lead';
    //     echo json_encode(['status' => 'error', 'message' => "$responseMessage $trackDriveResponse"]);
    // } else {
    //     $responseMessage = "TrackDrive API Error: $trackDriveResponse";
    //     echo json_encode(['status' => 'error', 'message' => $responseMessage]);
    // }

    // $responseDecoded = json_decode($trackDriveResponse, true);
    // $status = $responseDecoded['status'] ?? $responseMessage;
    // $success = $responseDecoded['success'] ?? ($trackDriveHttpCode === 200 || $trackDriveHttpCode === 201 || $trackDriveHttpCode === 422);

    // $minimalResponse = ['status' => $status, 'success' => $success];

    $googleSheetKeys = ['first_name', 'last_name', 'caller_id', 'email', 'dob', 'state', 'city', 'zip', 'xxTrustedFormToken', 'TrustedFormPingUrl', 'jornaya_leadid', 'ip_address', 'traffic_source_id', 'ip_region', 'ip_city', 'ip_country'];
    $googleSheetData = prepareFormData($_POST, $googleSheetKeys);
    // $googleSheetData['api_response'] = json_encode(['status' => $status, 'message' => $responseMessage]);
    // Add +1 before the phone number
    if (isset($googleSheetData['caller_id'])) {
        $googleSheetData['caller_id'] = '+1' . $googleSheetData['caller_id'];
    }

    $googleSheetUrl = 'https://script.google.com/macros/s/AKfycbxF-qYrIAEFGIPfoCfLPYU9p8_9-5CPlarkTogsd3JeWbdpdqKHsuEQYy8Y8oQkyMMD/exec';
    $postData = http_build_query($googleSheetData);
    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => $postData,
        ],
    ];
    $context = stream_context_create($options);
    $googleResult = file_get_contents($googleSheetUrl, false, $context);

    if ($googleResult === FALSE) {
        error_log('Failed to submit data to Google Sheets');
        echo json_encode(['status' => 'error', 'message' => 'Failed to submit data']);
        exit;
    } else {
        echo json_encode(['status' => 'success', 'message' => 'Data successfully submitted']);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <title>Easy Medicare Picks - Get a Quote</title>
    <link rel="icon" href="https://easymedicarepicks.com/wp-content/uploads/2024/10/Group-3694-150x150.png" sizes="32x32" />
    <link rel="icon" href="https://easymedicarepicks.com/wp-content/uploads/2024/10/Group-3694-300x300.png" sizes="192x192" />
    <link rel="apple-touch-icon" href="https://easymedicarepicks.com/wp-content/uploads/2024/10/Group-3694-300x300.png" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');

        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url('https://easymedicarepicks.com/wp-content/uploads/2025/01/image.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
        }

        .main {
            width: 100%;
        }

        .inner-wraper {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .inner-wraper .inner-2 {
            width: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px;
            border-radius: 30px;
            background: #1d0041;
            border: 1px solid #e0e0e0;
        }

        form {
            margin-top: 30px;
            width: 100%;
			border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            display: block;
            padding: 2px 8px;
            margin-bottom: 8px;
            color: #fff;
			font-family: 'Montserrat';
			font-size: 16px;
			font-weight: 500;
            margin-left: 15px;
            margin-bottom: -10px;
            background: rgb(255, 255, 255);
            border-radius: 5px;
            z-index: 1;
        }

        input {
            width: 100%;
            padding: 12px 12px 12px 12px;
            margin-bottom: 20px;
            box-sizing: border-box;
            border: 1px solid #fff;
            border-radius: 0px;
            background: transparent;
			color: #fff;
			font-family: 'Montserrat';
			font-size: 18px;
        }
		


        button {
            background-color: #FBAF03;
            color: #202020;
            padding: 15px 70px;
            border: none;
            border-radius: 7px;
            cursor: pointer;
            width: 100%;
            font-size: 20px;
            font-weight: 600;
            margin-top: 15px;
			transition: .3s;
            box-shadow: 0px 4px 25px 0px rgba(126.00000000000021, 209.99999999999994, 24.000000000000046, 0.4);
        }

        button:hover {
            background-color: #fff;
			color: #202020;
			transition: .3s;
        }

        .signature {
            text-align: center;
            color: #555;
            font-size: 10px;
        }
        .logo {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .top1 {
            font-family: 'Montserrat';
            font-size: 70px;
            font-weight: 300;
            text-align: center;
            color: #fff;
        }
        .top2 {
            font-family: 'Montserrat';
            font-size: 30px;
            font-weight: 700;
            text-align: center;
            color: #fff;
        }
        .top3 {
            font-family: 'Montserrat';
            font-size: 20px;
            font-weight: 400;
            text-align: center;
            color: #fff;
        }
        .form-content {
            width: 100%;
			display: flex;
			flex-direction: row;
			flex-wrap: wrap;
			justify-content: space-between;
			gap: 0px;
        }
		.form-content .FNAME, .form-content .LNAME, .form-content .PHONE, .form-content .EMAIL {
			width: 48%;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
		}
		.form-content .DOB {
			width: 100%;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
		}
		.form-content .STATE, .form-content .CITY, .form-content .ZIP {
			width: 31.5%;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
		}
        h1 {
            font-family: 'Montserrat';
            font-size: 20px;
            font-weight: 500;
            text-align: left;
            color: #c0c0c0;
            margin: 0;
        }
        .tcp p {
            font-family: 'Montserrat';
            font-size: 12px;
            font-weight: 400;
            color: #c0c0c0;
            line-height: 1.5em;
        }

        .tcp p a {
            color: #FBAF03;
        }
		
		@media only screen and (max-width: 600px) {
            body {
                padding: 50px 0;
            }
            .main {
                width: 100%;
            }
            .inner-wraper {
                flex-direction: column;
                height: auto;
            }
            .inner-wraper .inner-1 {
                width: 100%;
            }
            .inner-wraper .inner-2 {
                width: 85%;
            }
            .inner-wraper .inner-1 {
                height: 40vh;
            }
            .inner-wraper .inner-2 {
                padding: 20px;
                border-radius: 10px;
            }
            .inner-wraper .inner-2 .logo picture {
                width: 40% !important;
            }
            form {
                gap: 20px;
            }
			h1 {
				font-size: 16px;
			}
			.top2 {
				font-size: 24px;
				line-height: 1.2em;
			}
			.form-content .FNAME, .form-content .LNAME, .form-content .PHONE, .form-content .EMAIL {
				width: 100%;
			}
			.form-content .STATE, .form-content .CITY, .form-content .ZIP {
				width: 100%;
			}
            label {
                font-size: 14px;
            }
			input {
				padding: 12px;
				margin-bottom: 15px;
			}

			.tcp p {
				margin: 0 0 10px 0;
			}
			button {
				margin-top: 0px;
			}
		}
    </style>
	
	<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MRQQ73S5');</script>
<!-- End Google Tag Manager -->
</head>
<body>
	
	<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MRQQ73S5"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

    <div class="main">
    <div class="wraper">
        <div class="inner-wraper">
            <div class="inner-2">
                <div class="logo">
                    <picture 
                    style="display: flex;
                    flex-direction: column;
                    flex-wrap: nowrap;
                    margin: 0px 0px 20px 0px;
                    width: 25%;" >
                        <source type="image/webp" srcset="https://easymedicarepicks.com/wp-content/uploads/2024/10/Group-3657-2.png">
                        <img src="https://easymedicarepicks.com/wp-content/uploads/2024/10/Group-3657-2.png" alt="Logo" style="width: 100%"/>
                    </picture>
                </div>
                <span class="top2">Medicare Benefits Plans</span>
                <h1>Get Consultation Now!</h1>
                <form id="leadForm" action='' method='post'>
                    <div class="form-content">
                        <input type='hidden' value='313e1b73089f468a88166e3c5b931639' name='lead_token'>
                        <input type='hidden' value='9995' name='traffic_source_id'>
                        <input type='hidden' value='' name='source_url'>
                        <input type='hidden' id="ip-address" value='' name='ip_address'>
                        <input type='hidden' id="ip-region" value='' name='ip_region'>
                        <input type='hidden' id="ip-city" value='' name='ip_city'>
                        <input type='hidden' id="ip-country" value='' name='ip_country'>
                        <input type='hidden' id='timestamp' name='original_lead_submit_date' value=''>
                        <input type="hidden" id="trackdriveResponse" name="trackdrive_response" value="">

                        <div class="FNAME">
                            <!-- <label for="firstName">First Name: <span style="color: red;">*</span></label> -->
                            <input type="text" id="firstName" placeholder="Enter First Name" name="first_name" required>
                        </div>

                        <div class="LNAME">
                            <!-- <label for="lastName">Last Name: <span style="color: red;">*</span></label> -->
                            <input type="text" id="lastName" placeholder="Enter Last Name" name="last_name" required>
                        </div>
                        
                        <div class="PHONE">
                            <!-- <label for="phoneNumber">Phone Number: <span style="color: red;">*</span></label> -->
                            <input type="tel" id="phoneNumber" placeholder="Enter Phone Number" name="caller_id" required>
                        </div>
                        
                        <div class="EMAIL">
                            <!-- <label for="email">Email Address: <span style="color: red;">*</span></label> -->
                            <input type="email" id="email" placeholder="Enter Email Address" name="email" required>
                        </div>
                        
                        <div class="DOB">
                            <!-- <label for="dob">Date of Birth (MM-DD-YYYY): <span style="color: red;">*</span></label> -->
                            <input type="text" id="dob" placeholder="Enter Date of Birth" name="dob" required>
                        </div>

                        <div class="STATE">
                            <!-- <label for="state">State: <span style="color: red;">*</span></label> -->
                            <input type="text" id="state" placeholder="Enter State" name="state" required>
                        </div>
                        
                        <div class="CITY">
                            <!-- <label for="city">City: <span style="color: red;">*</span></label> -->
                            <input type="text" id="city" placeholder="Enter City" name="city" required>
                        </div>
                        
                        <div class="ZIP">
                            <!-- <label for="zip">Zip Code: <span style="color: red;">*</span></label> -->
                            <input type="text" id="zip" placeholder="Enter Zip Code" name="zip" required>
                        </div>
                        
                        
                        <input id="trusted_form_cert_url" name="trusted_form_cert_url" type="hidden" value=""/>
                        <input id="leadid_token" name="jornaya_leadid" type="hidden" value=""/>

                        <div class="tcp">
                            <p>By clicking "Submit", I agree to the Terms and Conditions and Privacy Policy of EasyMedicarePicks and authorize EasyMedicarePicks and its agents to contact me directly regarding Medicare and other insurance-related information at the phone number I have provided. I consent to receive calls and pre-recorded messages through an automated system, even if my number is on any Do Not Call list. I understand that my consent is not a condition of purchasing any goods or services, and I may revoke my consent at any time. Standard message and data rates may apply.</p>
                        </div>
                        
                        <button type="button" id="submitButton" class="assist" onclick="submitForm()">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <script id="LeadiDscript" type="text/javascript">
        (function() {
        var s = document.createElement('script');
        s.id = 'LeadiDscript_campaign';
        s.type = 'text/javascript';
        s.async = true;
        s.src = '//create.lidstatic.com/campaign/11209397-31bc-376b-ac50-52df4acc79c5.js?snippet_version=2&f=reset';
        var LeadiDscript = document.getElementById('LeadiDscript');
        LeadiDscript.parentNode.insertBefore(s, LeadiDscript);
        })();
        </script>
        <noscript><img src='//create.leadid.com/noscript.gif?lac=CF4996BF-EAEF-6727-187B-F7D19ACD91A7&lck=11209397-31bc-376b-ac50-52df4acc79c5&snippet_version=2' /></noscript>
        <!-- For Jornaya -->
        
        <!-- TrustedForm -->
        <script type="text/javascript">
        (function() {
        var tf = document.createElement('script');
        tf.type = 'text/javascript'; tf.async = true;
        tf.src = ("https:" == document.location.protocol ? 'https' : 'http') + "://api.trustedform.com/trustedform.js?field=trusted_form_cert_url&ping_field=TrustedFormPingUrl&l=" + new Date().getTime() + Math.random();
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(tf, s);
        })();
        </script>
        <noscript>
        <img src="https://api.trustedform.com/ns.gif" />
        </noscript>

    
    <!----------SCRIPT FOR DOB FORMAT-------->
<script>
    $(document).ready(function() {
		$.get("https://ipinfo.io?token=2bd961e828ebfa", function(response) {
			$("#ip-address").val(response.ip);
			$("#ip-region").val(response.region);
			$("#ip-city").val(response.city);
			$("#ip-country").val(response.country);
		});
		
		$("input[name='source_url']").val(window.location.href);
		
		const now = new Date();
		const formattedTimestamp = now.getFullYear() + '-' +
			('0' + (now.getMonth() + 1)).slice(-2) + '-' +
			('0' + now.getDate()).slice(-2) + ' ' +
			('0' + now.getHours()).slice(-2) + ':' +
			('0' + now.getMinutes()).slice(-2) + ':' +
			('0' + now.getSeconds()).slice(-2);
		$("#timestamp").val(formattedTimestamp);
		
        $("#dob").on("input", function() {
            var dobValue = $(this).val();
            var formattedDob = dobValue.replace(/\D/g, '').replace(/(\d{2})(\d{2})?(\d{0,4})?/, '$1-$2-$3').replace(/--/, '-');
            $(this).val(formattedDob);
        });

        $("#submitButton").on("click", function(e) {
            e.preventDefault();

            var dobValue = $("#dob").val();
            var dobParts = dobValue.split("-");
            if (dobParts.length === 3) {
                var yyyy = dobParts[2];
                var mm = dobParts[0];
                var dd = dobParts[1];
                var formattedDob = yyyy + "-" + mm + "-" + dd;
                $("#dob").val(formattedDob);
            }
            
            let valid = true;
            
            // Validate only visible and non-hidden fields
            $('#leadForm input').filter(":visible").each(function () {
                if ($(this).val().trim() === '') {
                    valid = false;
                    return false; // Exit each loop if a field is invalid
                }
            });

            if (!valid) {
                alert('Please fill out all required fields.');
                return;
            }

            let formData = $("#leadForm").serialize();

            $.ajax({
                url: '', // PHP file handling the POST request
                type: 'POST',
                data: formData,
                dataType: 'json', // Expect JSON response from server
                success: function (response) {
                    // Handle the response
                    if (response.status === "success") {
                        alert(response.message);
                        window.location.reload();
                    } else {
                        alert("Error: " + response.message); // Show error alert
                        window.location.reload();
                    }
                },
                error: function (xhr, status, error) {
                    // Handle AJAX errors
                    alert("AJAX Error: " + error);
                }
            });
        });
    });
</script>

<!-------Trim_Space---------------->
<script>
// Add event listeners to trim spaces from input fields
document.getElementById("firstName").addEventListener("input", function () {
    this.value = this.value.trim();
});

document.getElementById("lastName").addEventListener("input", function () {
    this.value = this.value.trim();
});

document.getElementById("dob").addEventListener("input", function () {
    this.value = this.value.trim();
});

document.getElementById("state").addEventListener("input", function () {
    this.value = this.value.trim();
});

document.getElementById("city").addEventListener("input", function () {
    this.value = this.value.trim();
});

document.getElementById("zip").addEventListener("input", function () {
    this.value = this.value.trim();
});
</script>

<!-- <script>
    // Disable right-click context menu
    window.addEventListener('contextmenu', function (e) {
        e.preventDefault();
    });
</script> -->

</body>
</html>