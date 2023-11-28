<?php
session_start();

include("DBedcorp.php");
include("login.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['signup_submit'])) {
       
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $password = isset($_POST['pass']) ? $_POST['pass'] : '';
        $Paid = isset($_POST['Paid']) ? $_POST['Paid'] : '';
        $Date = isset($_POST['registration_date']) ? $_POST['registration_date'] : '';

        if (!empty($email) && !empty($password) && !is_numeric($email)) {
            // Hash the password before storing it in the database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            
            $query = "INSERT INTO signuplist.studentsignup (Email, Password, Paid, DateS) VALUES ('$email', '$hashed_password', '$Paid', '$Date')";
            mysqli_query($con, $query);

            echo "Successfully Registered, we will be in touch with you shortly to update your login status";
        } else {
            echo "Registration unsuccessful, please enter only valid details";
        }
    } elseif (isset($_POST['login_submit'])) {
       
        $emailog = $_POST['emaillog'];
        $passwordlog = isset($_POST['passlog']) ? $_POST['passlog'] : ''; // Add this line to capture the password
        $IP = $_SERVER['REMOTE_ADDR'];
        
        if (!empty($emailog) && !empty($passwordlog) && !is_numeric($emailog)) {
           
            $query = "INSERT INTO logincheck.loginrecords (email, IP) VALUES ('$emailog', '$IP')";
            mysqli_query($con, $query);
        
            
            $query = "SELECT * FROM signuplist.studentsignup WHERE Email = '$emailog' LIMIT 1";
        
            $result = mysqli_query($con, $query);
        
            if ($result && mysqli_num_rows($result) > 0) {
                $user_data = mysqli_fetch_assoc($result);
        
              
                if (password_verify($passwordlog, $user_data['Password']) && $user_data['Paid'] == "Yes") {
                    header("Location: https://edcorponlineco.wordpress.com/");
                    exit();
                }
            }
            echo "Incorrect email or password";
        } else {
            echo "Incorrect email or password";
        }
    }
}
?>

<!-- Your HTML code goes here -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Modern Login Page | AsmrProg</title>
</head>

<body>

    <div class="container" id="container">
        <div class="form-container sign-up">

            <form method="POST">
                <img src="https://edcorponlineco.files.wordpress.com/2023/11/20231120_142426_0000.png?resize=320%2C320"
                    alt="Your Image">
                <h1>Create Account</h1>

                <span>Use your email and a unique password to register:)</span>

                <input type="email" name="email" placeholder="Email">
                <input type="password" name="pass" placeholder="Password">
                <input type="hidden" name="Paid" value="no">
                <input type="hidden" name="registration_date" value="<?php echo date('Y/m/d'); ?>">
                <button name="signup_submit">Sign Up</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form method="POST">
                <img src="https://edcorponlineco.files.wordpress.com/2023/11/20231120_142426_0000.png?resize=320%2C320"
                    alt="Your Image">
                <h1>Sign In</h1>

                <span>Do not share your email and password to others to avoid being locked out of the system!</span>
                <input type="email" name="emaillog" placeholder="Email">
                <input type="password" name="passlog" placeholder="Password">
                <a href="#">Forgot Your Password?</a>
                <button name="login_submit">Sign In</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all of the site features</p>
                    <button class="hidden" id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hey Student:)</h1>
                    <p>Register with your personal details to use all of the site features</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const container = document.getElementById('container');
        const registerBtn = document.getElementById('register');
        const loginBtn = document.getElementById('login');

        registerBtn.addEventListener('click', () => {
            container.classList.add("active");
        });

        loginBtn.addEventListener('click', () => {
            container.classList.remove("active");
        });
    </script>
</body>

</html>
