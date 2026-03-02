<?php
$error_message = "";

// 1. Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 2. Safely grab the submitted form data
    $number = htmlspecialchars($_POST['data']['number']);
    $name = htmlspecialchars($_POST['data']['name']);
    $date = htmlspecialchars($_POST['data']['date']);
    $batch_time = htmlspecialchars($_POST['data']['batch-time']);

    // 3. Prepare the data exactly how SheetDB expects it (as a JSON array)
    $payload = json_encode(array(
        "data" => array(
            array(
                "number" => $number,
                "name" => $name,
                "date" => $date,
                "batch-time" => $batch_time
            )
        )
    ));

    // 4. Send the data to SheetDB using PHP cURL
    $url = 'https://sheetdb.io/api/v1/334jme01cbqzr';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Accept: application/json',
        'Content-Type: application/json'
    ));

    $result = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // 5. Check if SheetDB saved it successfully (HTTP 201 Created or 200 OK)
    if ($httpcode == 201 || $httpcode == 200) {
        header("Location: landingpg.php?student_name=" . urlencode($name));
        exit(); 
    } else {
        $error_message = "Something went wrong connecting to the database. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up - Rhythm Verse Dance Studio</title>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;500;700;900&display=swap');

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', sans-serif;
        }

        /* Dark, energetic background with an overlay and a dance silhouette image */
        body {
            background: linear-gradient(135deg, rgba(15, 12, 41, 0.85) 0%, rgba(48, 43, 99, 0.85) 50%, rgba(36, 36, 62, 0.85) 100%), 
                        url('https://images.unsplash.com/photo-1547153760-18fc86324498?q=80&w=1920&auto=format&fit=crop') no-repeat center center/cover;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            color: #ffffff;
        }

        /* Glassmorphism Card Effect */
        .container {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 45px 40px;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 480px;
        }

        /* High-impact typography */
        h1 { 
            font-size: 32px; 
            font-weight: 900; 
            margin-bottom: 5px; 
            text-align: center; 
            text-transform: uppercase;
            letter-spacing: 2px;
            background: linear-gradient(45deg, #00f2fe, #4facfe, #8E54E9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        p.subtitle { 
            color: #b3b3b3; 
            font-size: 14px; 
            text-align: center; 
            margin-bottom: 25px; 
            letter-spacing: 0.5px;
        }

        .error-message {
            background-color: rgba(255, 77, 77, 0.2);
            color: #ff4d4d;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid rgba(255, 77, 77, 0.3);
            text-align: center;
            margin-bottom: 25px;
            font-size: 14px;
            font-weight: 500;
        }

        /* Modern input labels */
        label { 
            display: block; 
            color: #e0e0e0; 
            font-weight: 500; 
            margin-bottom: 8px; 
            font-size: 13px; 
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Sleek, underline-only inputs */
        input[type=text], input[type=tel], input[type=date], select {
            width: 100%; 
            padding: 12px 15px; 
            margin-bottom: 25px;
            background-color: rgba(0, 0, 0, 0.3); 
            border: none;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 6px 6px 0 0;
            font-size: 15px; 
            color: #ffffff;
            transition: all 0.3s ease;
        }

        /* Dropdown options need a dark background so they are readable */
        select option {
            background-color: #24243e;
            color: #ffffff;
        }

        /* Neon Glow Focus Effect */
        input[type=text]:focus, input[type=tel]:focus, input[type=date]:focus, select:focus {
            background-color: rgba(0, 0, 0, 0.5); 
            border-bottom: 2px solid #00f2fe;
            outline: none; 
            box-shadow: 0 10px 10px -10px rgba(0, 242, 254, 0.5);
        }

        /* Adjust date picker icon for dark mode in supported browsers */
        ::-webkit-calendar-picker-indicator {
            filter: invert(1);
            opacity: 0.7;
            cursor: pointer;
        }

        /* Button Layout */
        .clearfix { 
            display: flex; 
            justify-content: space-between; 
            gap: 15px; 
            margin-top: 20px; 
        }
        
        button {
            width: 100%; 
            padding: 15px; 
            border: none; 
            border-radius: 8px;
            font-size: 15px; 
            font-weight: 700; 
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        button:active { transform: scale(0.97); }

        /* Secondary Button (Cancel) */
        .cancelbtn { 
            background-color: transparent; 
            color: #b3b3b3; 
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .cancelbtn:hover { 
            background-color: rgba(255, 255, 255, 0.05); 
            color: #ffffff;
        }

        /* Primary Neon Button (Register) */
        .signupbtn { 
            background: linear-gradient(45deg, #8E54E9, #4facfe); 
            color: white; 
            box-shadow: 0 4px 15px rgba(142, 84, 233, 0.4); 
        }
        .signupbtn:hover { 
            box-shadow: 0 6px 20px rgba(79, 172, 254, 0.6);
            filter: brightness(1.1);
        }

        .terms-text {
            color: #a0a0a0;
            font-size: 12px;
            text-align: center;
            margin-top: -5px;
        }

        a { color: #00f2fe; text-decoration: none; font-weight: 500; }
        a:hover { text-decoration: underline; text-shadow: 0 0 8px rgba(0, 242, 254, 0.5); }

        @media screen and (max-width: 480px) {
            .container { padding: 35px 25px; border-radius: 0; border: none; background: transparent; box-shadow: none; backdrop-filter: none; }
            .clearfix { flex-direction: column; }
            body { align-items: flex-start; }
        }
    </style>
</head>
<body>
    <form action="" method="post" id="registration-form">
        <div class="container">
            <h1>Rhythm Verse</h1>
            <p class="subtitle">Secure your spot for a demo class.</p>
            
            <?php if(!empty($error_message)): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>
      
            <label for="number">Phone Number</label>
            <input type="tel" id="number" placeholder="Enter your mobile" name="data[number]" required>
      
            <label for="name">Student Name</label>
            <input type="text" id="name" placeholder="Enter your full name" name="data[name]" required>
            
            <label for="date">Registration Date</label>
            <input type="date" id="date" name="data[date]" required>
            
            <label for="batch-time">Select Batch Time</label>
            <select id="batch-time" name="data[batch-time]" required>
                <option value="" disabled selected>Choose a time slot</option>
                <option value="8 to 9AM">8:00 AM to 9:00 AM</option>
                <option value="2 to 3PM">2:00 PM to 3:00 PM</option>
                <option value="4 to 5PM">4:00 PM to 5:00 PM</option>
                <option value="5 to 6PM">5:00 PM to 6:00 PM</option>
                <option value="6 to 7PM">6:00 PM to 7:00 PM</option>
                <option value="7 to 8PM">7:00 PM to 8:00 PM</option>
                <option value="8 to 9PM">8:00 PM to 9:00 PM</option>
                <option value="9 to 10PM">9:00 PM to 10:00 PM</option>
            </select>

            <p class="terms-text">By registering, you agree to our <a href="#">Terms & Privacy</a>.</p>
      
            <div class="clearfix">
                <button type="button" class="cancelbtn" onclick="document.getElementById('registration-form').reset()">Reset</button>
                <button type="submit" class="signupbtn" id="submit-btn">Register Now</button>
            </div>
        </div>
    </form>

    <script>
        const form = document.getElementById('registration-form');
        form.addEventListener("submit", function() {
            document.getElementById('submit-btn').innerHTML = "PROCESSING...";
        });
    </script>
</body>
</html>
