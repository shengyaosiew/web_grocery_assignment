<?php
require_once './config_session.inc.php';
require_once './signup_view.inc.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="signup.css">

    <style>
        .sign-up {
            display: none; /* Initially hide the sign-up div */
            transition: opacity 0.5s; /* Add a transition for smooth appearance */
        }

        .show-sign-up {
            display: block; /* Show the sign-up div when this class is applied */
        }
    </style>

</head>

<body>
<!--------------------------------- select roles ---------------------------------------------------------------------------------------------------------------->
    <div class="select-role">
        <div class="select-role-container">
            <div class="companyLogo">
                <img src="./images/heyday_icon.png" alt="heyday_icon">
                <p>SIGN UP</p>
            </div>

            <div class="rolesLogo">
                <a id="seller">
                    <img src="./images/seller.png" alt="seller_icon">
                    <p>FARMER (SELLER)</p>
                </a>

                <a id="buyer">
                    <img src="./images/buyer.png" alt="buyer_icon">
                    <p>SUPERMARKET (BUYER)</p>
                </a>
            </div>

            <p class="askSignIn">ALREADY A USER? <a href="login.php" target="_self">SIGN IN</a></p>

            
            <script>
                //Using AJAX TO SUBMIT FORM
                const sellerLink = document.getElementById('seller');
                const buyerLink = document.getElementById('buyer');

                sellerLink.addEventListener('click', (event) => {
                    event.preventDefault(); // Prevent default form submission
                    buyerLink.classList.add('disabled'); // Disable the buyer link
                    document.querySelector('input[name="usertype"]').value = 'seller';
                    submitFormWithAjax();
                });

                buyerLink.addEventListener('click', (event) => {
                    event.preventDefault(); // Prevent default form submission
                    sellerLink.classList.add('disabled'); // Disable the seller link
                    document.querySelector('input[name="usertype"]').value = 'buyer';
                    submitFormWithAjax();
                });

                function submitFormWithAjax() {
                    const selectedRole = document.querySelector('input[name="usertype"]').value;

                    const formData = new FormData();
                    formData.append('usertype', selectedRole);

                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', './includes/signup.inc.php');
                    xhr.send(formData);

                    xhr.onload = function() {
                        if (xhr.status === 200) {
                        // Process server response here
                        console.log(xhr.responseText);
                        } else {
                        console.error('Error submitting form:', xhr.status);
                        }
                    };
                }
            </script>

        </div>
    </div>
<!--------------------------------- Sign Up ---------------------------------------------------------------------------------------------------------------->
    <div class="sign-up">
        <div class="sign-up-container">
            <div class="top">
                <h3>SIGN UP AS BUYER</h3>
            </div>
            
            <form action="./signup.inc.php" method="POST">
                <div class="main" id="contactInfo">
                    <label>1. YOUR CONTACT INFORMATION:</label>
                    <input type="text" name="fullname" placeholder="FULL NAME:" required>
                    <input type="email" name="email" placeholder="E-MAIL ADDRESS:" required>
                    <input type="tel" name="phonenumber" placeholder="PHONE NUMBER:" required>

                    <!-------Roles selection from the top --------------------------------------------------------------------------------------------------------> 
                    <input type="hidden" name="usertype">
                </div>

                <div class="main" id="personalInfo">
                    <label>2. YOUR PERSONAL INFORMATION:</label>
                    <input type="text" name="country" placeholder="COUNTRY:" required>
                    <input type="text" name="postcode" placeholder="POSTAL CODE:" required>
                    <input type="text" name="state" placeholder="STATE:" required>
                    <input type="text" name="city" placeholder="CITY:" required>
                    <textarea name="addressline" placeholder="ADDRESS LINE:" required></textarea>
                </div>

                <div class="main" id="createAccount">
                    <label>3. CREATE YOUR ACCOUNT:</label>
                    <input type="text" name="username" placeholder="USERNAME:" required>
                    <input type="password" name="password" placeholder="PASSWORD:" required>
                    <input type="text" name="verificationcode" placeholder="VERIFICATION CODE:" required>
                    <label style="color: white; font-weight: 600;">*This verification code is for password recovery!</label>
                </div>

                <input type="submit" value="SIGN UP">
            </form>
            
            <p class="askSignIn">ALREADY A USER? <a href="login.php" target="_self">SIGN IN</a></p>
        </div>
    </div>

    <script>
        const sellerDiv = document.getElementById('seller');
        const buyerDiv = document.getElementById('buyer');
        const signUpDiv = document.querySelector('.sign-up');
        const signUpHeading = document.querySelector('.sign-up-container h3'); 

        sellerDiv.addEventListener('click', (event) => {
            signUpDiv.classList.add('show-sign-up');
            signUpHeading.textContent = 'SIGN UP AS SELLER'; // Change the heading text
            scrollToSignUp();
        });

        buyerDiv.addEventListener('click', (event) => {
            signUpDiv.classList.add('show-sign-up');
            signUpHeading.textContent = 'SIGN UP AS BUYER'; // Change the heading text
            scrollToSignUp();
        });

        function scrollToSignUp() {
            const signUpDiv = document.querySelector('.sign-up');
            signUpDiv.scrollIntoView({ behavior: "smooth" });
        }
    </script>

    
</body>
</html>

<?php
    check_signup_errors();
?>